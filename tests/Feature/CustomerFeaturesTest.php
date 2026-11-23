<?php

namespace Tests\Feature;

use App\Models\Customer;

/** UCD400 Manage Customer Profile, UCD500 View Point & Reward */
class CustomerFeaturesTest extends LoyaltyTestCase
{
    private function asCustomer1(): static
    {
        return $this->withSession(['user_id' => 'CUS001', 'username' => 'customer1', 'role' => 'customer']);
    }

    public function test_customer_sees_points_and_rewards(): void
    {
        $this->asCustomer1()->get('/customer/points-rewards')
            ->assertOk()
            ->assertSee('RM10 Voucher');
    }

    public function test_archived_rewards_are_hidden_from_customer(): void
    {
        \App\Models\Reward::where('rewardID', 'RWD001')->update(['status' => 'inactive']);
        $this->asCustomer1()->get('/customer/points-rewards')->assertDontSee('RM10 Voucher');
    }

    public function test_customer_updates_profile(): void
    {
        $this->asCustomer1()->put('/customer/profile', [
            'customerName' => 'Alice T', 'email' => 'alice.new@email.com',
            'phoneNumber' => '012-0000001', 'birthDate' => '1995-06-15',
        ])->assertSessionHas('success');

        $this->assertSame('alice.new@email.com', Customer::find('CUS001')->email);
    }

    public function test_profile_rejects_email_used_by_another_customer(): void
    {
        $this->asCustomer1()->put('/customer/profile', [
            'customerName' => 'Alice', 'email' => 'bob@email.com',
            'phoneNumber' => '012-3456789', 'birthDate' => '1995-06-15',
        ])->assertSessionHasErrors('email');
    }

    public function test_profile_requires_phone_and_birth_date(): void
    {
        $this->asCustomer1()->put('/customer/profile', [
            'customerName' => 'Alice', 'email' => 'alice@email.com', 'phoneNumber' => '', 'birthDate' => '',
        ])->assertSessionHasErrors(['phoneNumber', 'birthDate']);
    }

    public function test_referral_count_only_counts_people_who_used_my_code(): void
    {
        $this->post('/register', $this->validRegistration(['referral_code' => 'ALICE01']));

        $this->asCustomer1()->get('/customer-dashboard')->assertViewHas('referralCount', 1);
        $this->withSession(['user_id' => 'CUS004', 'username' => 'jeremytest', 'role' => 'customer'])
            ->get('/customer-dashboard')->assertViewHas('referralCount', 0);
    }
}
