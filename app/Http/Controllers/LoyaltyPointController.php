<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\PurchaseTransaction;
use App\Models\Redemption;
use App\Models\Reward;

class LoyaltyPointController extends Controller
{
    private function checkAccess()
    {
        if (session('role') !== 'cashier') {
            abort(403, 'Unauthorized access.');
        }
    }

    public function showCashierDashboard()
    {
        if (session('role') !== 'cashier') {
            return redirect('/my-login')->with('error', 'Please log in to access the dashboard.');
        }
        return view('cashier-dashboard');
    }

    public function managePoints()
    {
        $this->checkAccess();
        $rewards = Reward::where('status', 'active')->where('stock', '>', 0)->get();
        return view('cashier.manage-points', compact('rewards'));
    }

    public function addPoints(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'customerID'  => 'required|string|exists:Customer,customerID',
            'totalPrice'  => 'required|numeric|min:0.01',
        ]);

        $customer = Customer::where('customerID', $request->customerID)
            ->whereNull('archivedAt')
            ->first();

        if (!$customer) {
            return back()->with('error', 'Customer not found or inactive. Please check the Customer ID.');
        }

        $pointEarned = (int) floor($request->totalPrice);

        $last = PurchaseTransaction::orderBy('transactionID', 'desc')->first();
        $num = $last ? (int) substr($last->transactionID, 3) + 1 : 1;
        $transactionID = 'TXN' . str_pad($num, 4, '0', STR_PAD_LEFT);

        PurchaseTransaction::create([
            'transactionID'    => $transactionID,
            'customerID'       => $customer->customerID,
            'cashierID'        => session('user_id'),
            'transactionDate'  => now()->toDateString(),
            'totalPrice'       => $request->totalPrice,
            'pointEarned'      => $pointEarned,
        ]);

        $customer->increment('currentPoints', $pointEarned);

        return redirect('/cashier/manage-points')
            ->with('success', "$pointEarned points added to {$customer->customerName}. Transaction: $transactionID");
    }

    public function redeemPoints(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'customerID' => 'required|string|exists:Customer,customerID',
            'rewardID'   => 'required|string|exists:Reward,rewardID',
        ]);

        $customer = Customer::where('customerID', $request->customerID)
            ->whereNull('archivedAt')
            ->first();

        if (!$customer) {
            return back()->with('error', 'Customer not found or inactive. Please check the Customer ID.');
        }

        $reward = Reward::findOrFail($request->rewardID);

        if ($customer->currentPoints < $reward->pointRequired) {
            return back()->with('error', "Insufficient points. {$customer->customerName} has {$customer->currentPoints} points but needs {$reward->pointRequired}.");
        }

        if ($reward->stock < 1) {
            return back()->with('error', 'Reward is out of stock.');
        }

        $last = Redemption::orderBy('redemptionID', 'desc')->first();
        $num = $last ? (int) substr($last->redemptionID, 3) + 1 : 1;
        $redemptionID = 'RDM' . str_pad($num, 4, '0', STR_PAD_LEFT);

        Redemption::create([
            'redemptionID' => $redemptionID,
            'customerID'   => $customer->customerID,
            'rewardID'     => $reward->rewardID,
            'redeemedDate' => now()->toDateString(),
        ]);

        $customer->decrement('currentPoints', $reward->pointRequired);
        $reward->decrement('stock');

        return redirect('/cashier/manage-points')
            ->with('success', "{$customer->customerName} redeemed {$reward->rewardName}. Redemption: $redemptionID");
    }
}
