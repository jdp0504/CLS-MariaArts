<?php

namespace Tests\Feature;

/** UCD100 – Login */
class LoginTest extends LoyaltyTestCase
{
    public function test_login_page_loads(): void
    {
        $this->get('/my-login')->assertOk();
    }

    public function test_admin_logs_in_as_staff_and_reaches_admin_dashboard(): void
    {
        $this->post('/my-login', ['username' => 'admin', 'password' => 'admin123', 'role' => 'staff'])
            ->assertRedirect('/admin-dashboard');
        $this->assertSame('admin', session('role'));
    }

    public function test_cashier_logs_in_as_staff_and_reaches_cashier_dashboard(): void
    {
        $this->post('/my-login', ['username' => 'cashier1', 'password' => 'cashier123', 'role' => 'staff'])
            ->assertRedirect('/cashier-dashboard');
    }

    public function test_active_customer_logs_in_and_reaches_customer_dashboard(): void
    {
        $this->post('/my-login', ['username' => 'customer1', 'password' => 'pass123', 'role' => 'customer'])
            ->assertRedirect('/customer-dashboard');
    }

    public function test_staff_account_cannot_log_in_through_customer_tab(): void
    {
        $this->post('/my-login', ['username' => 'admin', 'password' => 'admin123', 'role' => 'customer'])
            ->assertSessionHas('error', 'Invalid username or password. Attempts left: 4');
        $this->assertNull(session('role'));
    }

    public function test_wrong_password_shows_attempts_left(): void
    {
        $this->post('/my-login', ['username' => 'customer1', 'password' => 'wrong', 'role' => 'customer'])
            ->assertSessionHas('error', 'Invalid username or password. Attempts left: 4');
    }

    public function test_fifth_failed_attempt_locks_login(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/my-login', ['username' => 'customer1', 'password' => 'wrong', 'role' => 'customer']);
        }
        $response->assertSessionHas('error', 'LOCKED');

        // Even the correct password is refused during the lockout
        $this->post('/my-login', ['username' => 'customer1', 'password' => 'pass123', 'role' => 'customer'])
            ->assertSessionHas('error', 'LOCKED');
        $this->assertNull(session('role'));
    }

    public function test_archived_customer_cannot_log_in(): void
    {
        // customer3 (CUS003) is seeded as inactive
        $this->post('/my-login', ['username' => 'customer3', 'password' => 'pass123', 'role' => 'customer'])
            ->assertSessionHas('error');
        $this->assertNull(session('role'));
    }

    public function test_dashboards_redirect_when_not_logged_in(): void
    {
        $this->get('/admin-dashboard')->assertRedirect('/my-login');
        $this->get('/cashier-dashboard')->assertRedirect('/my-login');
        $this->get('/customer-dashboard')->assertRedirect('/my-login');
    }
}
