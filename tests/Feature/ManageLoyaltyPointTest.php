<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Reward;

/** UCD700 – Manage Loyalty Point (cashier) */
class ManageLoyaltyPointTest extends LoyaltyTestCase
{
    public function test_only_cashier_can_open_manage_points(): void
    {
        $this->asAdmin()->get('/cashier/manage-points')->assertForbidden();
        $this->asCashier()->get('/cashier/manage-points')->assertOk();
    }

    public function test_search_finds_customer_by_phone(): void
    {
        $this->asCashier()->post('/cashier/manage-points/search', ['search' => '012-3456789'])
            ->assertRedirect('/cashier/manage-points');
        $this->assertSame('CUS001', session('cashier_customer_id'));
    }

    public function test_unknown_phone_shows_error(): void
    {
        $this->asCashier()->post('/cashier/manage-points/search', ['search' => '000-0000000'])
            ->assertSessionHas('error', 'Customer not found. Please check the phone number.');
    }

    public function test_archived_customer_cannot_be_found(): void
    {
        $this->asCashier()->post('/cashier/manage-points/search', ['search' => '013-5551234'])
            ->assertSessionHas('error', 'Customer not found. Please check the phone number.');
    }

    public function test_purchase_adds_points_rm1_equals_1_point_rounded_down(): void
    {
        $this->asCashierServing('CUS001')
            ->post('/cashier/manage-points/add', ['totalPrice' => '45.90'])
            ->assertSessionHas('success');

        $this->assertSame(195, (int) Customer::find('CUS001')->currentPoints); // 150 + 45
        $this->assertDatabaseHas('PurchaseTransaction', [
            'customerID' => 'CUS001', 'cashierID' => 'CSH001', 'pointEarned' => 45,
        ]);
    }

    public function test_zero_or_negative_purchase_is_rejected(): void
    {
        $this->asCashierServing('CUS001')
            ->post('/cashier/manage-points/add', ['totalPrice' => '0'])
            ->assertSessionHasErrors('totalPrice');
        $this->assertSame(150, (int) Customer::find('CUS001')->currentPoints);
    }

    public function test_redeem_deducts_points_and_stock_and_records_redemption(): void
    {
        $this->asCashierServing('CUS001')
            ->post('/cashier/manage-points/redeem', ['rewardID' => 'RWD001']) // 100 pts
            ->assertSessionHas('success');

        $this->assertSame(50, (int) Customer::find('CUS001')->currentPoints);
        $this->assertSame(49, (int) Reward::find('RWD001')->stock);
        $this->assertDatabaseHas('Redemption', ['customerID' => 'CUS001', 'rewardID' => 'RWD001']);
    }

    public function test_redeem_with_insufficient_points_is_refused(): void
    {
        $this->asCashierServing('CUS001')
            ->post('/cashier/manage-points/redeem', ['rewardID' => 'RWD003']) // 500 pts, has 150
            ->assertSessionHas('error');

        $this->assertSame(150, (int) Customer::find('CUS001')->currentPoints);
        $this->assertDatabaseCount('Redemption', 0);
    }

    public function test_archived_reward_cannot_be_redeemed(): void
    {
        Reward::where('rewardID', 'RWD001')->update(['status' => 'inactive']);

        $this->asCashierServing('CUS001')
            ->post('/cashier/manage-points/redeem', ['rewardID' => 'RWD001'])
            ->assertSessionHas('error', 'This reward is no longer available.');
        $this->assertDatabaseCount('Redemption', 0);
    }

    public function test_out_of_stock_reward_cannot_be_redeemed(): void
    {
        Reward::where('rewardID', 'RWD001')->update(['stock' => 0]);

        $this->asCashierServing('CUS001')
            ->post('/cashier/manage-points/redeem', ['rewardID' => 'RWD001'])
            ->assertSessionHas('error', 'Reward is out of stock.');
    }

    public function test_cashier_can_reset_customer_password(): void
    {
        $this->asCashierServing('CUS001')
            ->post('/cashier/manage-points/reset-password', ['newPassword' => 'newpass1'])
            ->assertSessionHas('success');

        // Customer can now log in with the new password
        $this->post('/logout');
        $this->post('/my-login', ['username' => 'customer1', 'password' => 'newpass1', 'role' => 'customer'])
            ->assertRedirect('/customer-dashboard');
    }
}
