<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Reward;
use Illuminate\Support\Facades\Mail;

/** UCD300 Manage Membership Status, UCD600 Manage Reward, UCD800 Generate Notification, UCD900 View Report */
class AdminFeaturesTest extends LoyaltyTestCase
{
    // ── UCD300 ──
    public function test_admin_can_archive_and_reactivate_customer(): void
    {
        $this->asAdmin()->post('/admin/manage-membership/change-status', ['customerID' => 'CUS001'])
            ->assertRedirect('/admin/manage-membership');
        $c = Customer::find('CUS001');
        $this->assertSame('inactive', $c->status);
        $this->assertNotNull($c->archivedAt);

        $this->asAdmin()->post('/admin/manage-membership/change-status', ['customerID' => 'CUS001']);
        $c->refresh();
        $this->assertSame('active', $c->status);
        $this->assertNull($c->archivedAt);
    }

    public function test_membership_search_with_no_match_shows_message(): void
    {
        $this->asAdmin()->get('/admin/manage-membership?search=zzzz')
            ->assertOk()
            ->assertSee('No members found matching your criteria.');
    }

    public function test_non_admin_cannot_change_membership_status(): void
    {
        $this->asCashier()->post('/admin/manage-membership/change-status', ['customerID' => 'CUS001'])
            ->assertRedirect('/my-login');
        $this->assertSame('active', Customer::find('CUS001')->status);
    }

    // ── UCD600 ──
    public function test_admin_can_add_reward(): void
    {
        $this->asAdmin()->post('/admin/manage-rewards/add', [
            'rewardName' => 'Sketchbook', 'description' => 'A5', 'pointRequired' => 80, 'stock' => 10, 'status' => 'active',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('Reward', ['rewardID' => 'RWD005', 'rewardName' => 'Sketchbook', 'stock' => 10]);
    }

    public function test_reward_with_zero_points_or_negative_stock_is_rejected(): void
    {
        $this->asAdmin()->post('/admin/manage-rewards/add', [
            'rewardName' => 'Bad', 'pointRequired' => 0, 'stock' => -1, 'status' => 'active',
        ])->assertSessionHasErrors(['pointRequired', 'stock']);
        $this->assertDatabaseMissing('Reward', ['rewardName' => 'Bad']);
    }

    public function test_update_reward_adds_to_existing_stock(): void
    {
        $this->asAdmin()->post('/admin/manage-rewards/update', [
            'rewardID' => 'RWD004', 'rewardName' => 'Umbrella', 'pointRequired' => 200, 'stock' => 5, 'status' => 'active',
        ])->assertSessionHas('success');

        $this->assertSame(20, (int) Reward::find('RWD004')->stock); // 15 + 5
    }

    public function test_admin_can_archive_reward(): void
    {
        $this->asAdmin()->post('/admin/manage-rewards/archive', ['rewardID' => 'RWD002']);
        $this->assertSame('inactive', Reward::find('RWD002')->status);
    }

    // ── UCD800 ──
    public function test_send_without_recipients_shows_srs_error(): void
    {
        $this->asAdmin()->post('/admin/generate-notification/send', [
            'subject' => 'Promo', 'messageContent' => 'Hi {name}', 'action' => 'send', 'recipients' => [],
        ])->assertSessionHas('error', 'Select at least one recipient.');
    }

    public function test_missing_subject_or_message_shows_srs_errors(): void
    {
        $this->asAdmin()->post('/admin/generate-notification/send', [
            'subject' => '', 'messageContent' => 'Hi', 'action' => 'send',
        ])->assertSessionHasErrors(['subject' => 'Subject is required']);

        $this->asAdmin()->post('/admin/generate-notification/send', [
            'subject' => 'Promo', 'messageContent' => '', 'action' => 'send',
        ])->assertSessionHasErrors(['messageContent' => 'Message is required']);
    }

    public function test_notification_is_personalised_and_sent_to_selected_customers(): void
    {
        Mail::fake();

        $this->asAdmin()->post('/admin/generate-notification/send', [
            'subject' => 'Hello {name}', 'messageContent' => 'You have {points} points',
            'action' => 'send', 'recipients' => ['CUS001'],
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('Notification', [
            'customerID' => 'CUS001', 'subject' => 'Hello Alice Tan',
            'messageContent' => 'You have 150 points', 'status' => 'sent',
        ]);
    }

    public function test_admin_can_save_draft(): void
    {
        $this->asAdmin()->post('/admin/generate-notification/send', [
            'subject' => 'Draft', 'messageContent' => 'Later', 'action' => 'draft',
        ])->assertSessionHas('success', 'Draft saved successfully.');
        $this->assertDatabaseHas('Notification', ['subject' => 'Draft', 'status' => 'draft']);
    }

    // ── UCD900 ──
    public function test_report_tabs_load(): void
    {
        foreach (['activity', 'redemptions', 'points'] as $tab) {
            $this->asAdmin()->get("/admin/generate-report?tab={$tab}")->assertOk();
        }
    }

    public function test_top_earners_counts_referral_points_once(): void
    {
        // Alice: 3 purchases (10 pts each) + 1 referral (5 pts) = 35, not 45
        $cashier = $this->asCashierServing('CUS001');
        foreach ([1, 2, 3] as $_) {
            $cashier->post('/cashier/manage-points/add', ['totalPrice' => '10']);
        }
        $this->post('/register', $this->validRegistration(['referral_code' => 'ALICE01']));

        $response = $this->asAdmin()->get('/admin/generate-report?tab=points');
        $alice = collect($response->viewData('topEarners'))->firstWhere('customerID', 'CUS001');
        $this->assertSame(35, (int) $alice->points);
    }
}
