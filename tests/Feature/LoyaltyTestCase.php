<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Base class for CLS feature tests: fresh in-memory database with the demo
 * seed data (admin, cashier1, customer1–3, RWD001–RWD004) for every test.
 */
abstract class LoyaltyTestCase extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function asAdmin(): static
    {
        return $this->withSession(['user_id' => 'ADM001', 'username' => 'admin', 'role' => 'admin']);
    }

    protected function asCashier(): static
    {
        return $this->withSession(['user_id' => 'CSH001', 'username' => 'cashier1', 'role' => 'cashier']);
    }

    protected function asCashierServing(string $customerID): static
    {
        return $this->withSession([
            'user_id' => 'CSH001', 'username' => 'cashier1', 'role' => 'cashier',
            'cashier_customer_id' => $customerID,
        ]);
    }

    protected function validRegistration(array $overrides = []): array
    {
        return array_merge([
            'customerName' => 'Jeremy Test',
            'username'     => 'jeremytest',
            'email'        => 'jeremy@test.com',
            'phoneNumber'  => '011-1112222',
            'birthDate'    => '2005-04-05',
            'password'     => 'pass1234',
        ], $overrides);
    }
}
