<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Referral;

/** UCD200 – Register Loyalty Account */
class RegistrationTest extends LoyaltyTestCase
{
    public function test_customer_registers_with_zero_points_and_referral_code(): void
    {
        $this->post('/register', $this->validRegistration())
            ->assertRedirect('/registration-success');

        $customer = Customer::where('email', 'jeremy@test.com')->first();
        $this->assertNotNull($customer);
        $this->assertSame('CUS004', $customer->customerID);
        $this->assertSame(0, (int) $customer->currentPoints);
        $this->assertSame('active', $customer->status);
        $this->assertNotEmpty($customer->referralCode);
        $this->assertDatabaseHas('User', ['userID' => 'CUS004', 'username' => 'jeremytest', 'role' => 'customer']);
    }

    public function test_phone_number_is_required(): void
    {
        $this->post('/register', $this->validRegistration(['phoneNumber' => '']))
            ->assertSessionHasErrors(['phoneNumber' => 'Incomplete or Invalid Information']);
        $this->assertDatabaseMissing('Customer', ['email' => 'jeremy@test.com']);
    }

    public function test_birth_date_is_required(): void
    {
        $this->post('/register', $this->validRegistration(['birthDate' => '']))
            ->assertSessionHasErrors(['birthDate' => 'Incomplete or Invalid Information']);
    }

    public function test_email_without_at_sign_is_rejected(): void
    {
        $this->post('/register', $this->validRegistration(['email' => 'jeremytest.com']))
            ->assertSessionHasErrors(['email' => "Please include an '@' in the email address"]);
    }

    public function test_duplicate_phone_number_is_rejected(): void
    {
        $this->post('/register', $this->validRegistration(['phoneNumber' => '012-3456789']))
            ->assertSessionHasErrors(['phoneNumber' => 'Customer already registered']);
    }

    public function test_duplicate_email_is_rejected(): void
    {
        $this->post('/register', $this->validRegistration(['email' => 'alice@email.com']))
            ->assertSessionHasErrors(['email' => 'Customer already registered']);
    }

    public function test_referral_gives_both_customers_five_points_and_records_both(): void
    {
        $this->post('/register', $this->validRegistration(['referral_code' => 'ALICE01']))
            ->assertRedirect('/registration-success');

        $this->assertSame(155, (int) Customer::find('CUS001')->currentPoints); // 150 + 5
        $this->assertSame(5, (int) Customer::find('CUS004')->currentPoints);

        $this->assertDatabaseHas('Referral', ['customerID' => 'CUS001', 'referralType' => 'referrer', 'pointGranted' => 5]);
        $this->assertDatabaseHas('Referral', ['customerID' => 'CUS004', 'referralType' => 'referred', 'pointGranted' => 5]);
        $this->assertSame(2, Referral::count());
    }

    public function test_invalid_referral_code_is_rejected(): void
    {
        $this->post('/register', $this->validRegistration(['referral_code' => 'NOPE99']))
            ->assertSessionHasErrors(['referral_code' => 'Invalid referral code']);
    }
}
