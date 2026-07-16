<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Reward;
use App\Models\Redemption;
use App\Models\Referral;

class ViewPointController extends Controller
{
    public function showCustomerDashboard()
    {
        if (session('role') !== 'customer') {
            return redirect('/my-login')->with('error', 'Please log in to access the dashboard.');
        }

        $customer = Customer::where('customerID', session('user_id'))->firstOrFail();
        $rewards = Reward::where('status', 'active')->where('stock', '>', 0)->get();
        $redeemedCount = Redemption::where('customerID', $customer->customerID)->count();
        $referralCount = Referral::where('customerID', $customer->customerID)->count();

        return view('customer-dashboard', compact('customer', 'rewards', 'redeemedCount', 'referralCount'));
    }

    public function viewPointsRewards()
    {
        if (session('role') !== 'customer') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $customer = Customer::where('customerID', session('user_id'))->firstOrFail();
        $rewards = Reward::where('status', 'active')->where('stock', '>', 0)->get();
        $redeemedCount = Redemption::where('customerID', $customer->customerID)->count();
        $referralCount = Referral::where('customerID', $customer->customerID)->count();
        $redemptions = Redemption::where('customerID', $customer->customerID)
            ->orderBy('redeemedDate', 'desc')
            ->get();

        return view('customer.points-rewards', compact('customer', 'rewards', 'redeemedCount', 'referralCount', 'redemptions'));
    }
}
