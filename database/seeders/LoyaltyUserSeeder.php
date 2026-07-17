<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoyaltyUserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now()->toDateString();

        // ─── User table ───
        $users = [
            ['userID' => 'ADM001', 'username' => 'admin',     'role' => 'admin',    'createdDate' => $now, 'password' => Hash::make('admin123')],
            ['userID' => 'CSH001', 'username' => 'cashier1',  'role' => 'cashier',  'createdDate' => $now, 'password' => Hash::make('cashier123')],
            ['userID' => 'CSH002', 'username' => 'cashier2',  'role' => 'cashier',  'createdDate' => $now, 'password' => Hash::make('cashier123')],
            ['userID' => 'CUS001', 'username' => 'customer1', 'role' => 'customer', 'createdDate' => $now, 'password' => Hash::make('pass123')],
            ['userID' => 'CUS002', 'username' => 'customer2', 'role' => 'customer', 'createdDate' => $now, 'password' => Hash::make('pass123')],
            ['userID' => 'CUS003', 'username' => 'customer3', 'role' => 'customer', 'createdDate' => $now, 'password' => Hash::make('pass123')],
        ];
        DB::table('User')->insert($users);

        // ─── Admin table ───
        DB::table('Admin')->insert([
            ['adminID' => 'ADM001', 'adminName' => 'System Administrator'],
        ]);

        // ─── Cashier table ───
        DB::table('Cashier')->insert([
            ['cashierID' => 'CSH001', 'cashierName' => 'Sarah Lee'],
            ['cashierID' => 'CSH002', 'cashierName' => 'James Wong'],
        ]);

        // ─── Customer table ───
        DB::table('Customer')->insert([
            [
                'customerID'    => 'CUS001',
                'customerName'  => 'Alice Tan',
                'birthDate'     => '1995-06-15',
                'phoneNumber'   => '012-3456789',
                'referralCode'  => 'ALICE01',
                'currentPoints' => 150,
                'email'         => 'alice@email.com',
                'status'        => 'active',
                'archivedAt'    => null,
            ],
            [
                'customerID'    => 'CUS002',
                'customerName'  => 'Bob Lim',
                'birthDate'     => '1990-11-02',
                'phoneNumber'   => '012-9876543',
                'referralCode'  => 'BOB002',
                'currentPoints' => 320,
                'email'         => 'bob@email.com',
                'status'        => 'active',
                'archivedAt'    => null,
            ],
            [
                'customerID'    => 'CUS003',
                'customerName'  => 'Chong Wei',
                'birthDate'     => '1988-03-20',
                'phoneNumber'   => '013-5551234',
                'referralCode'  => 'CHONG03',
                'currentPoints' => 0,
                'email'         => 'chong@email.com',
                'status'        => 'inactive',
                'archivedAt'    => $now,
            ],
        ]);

        // ─── Reward table ───
        DB::table('Reward')->insert([
            ['rewardID' => 'RWD001', 'rewardName' => 'RM10 Voucher',  'pointRequired' => 100, 'stock' => 50, 'status' => 'active'],
            ['rewardID' => 'RWD002', 'rewardName' => 'RM25 Voucher',  'pointRequired' => 250, 'stock' => 30, 'status' => 'active'],
            ['rewardID' => 'RWD003', 'rewardName' => 'Tote Bag',      'pointRequired' => 500, 'stock' => 20, 'status' => 'active'],
            ['rewardID' => 'RWD004', 'rewardName' => 'Umbrella',      'pointRequired' => 200, 'stock' => 15, 'status' => 'active'],
        ]);
    }
}
