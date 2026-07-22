<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use App\Models\PurchaseTransaction;
use App\Models\Redemption;
use App\Models\Reward;
use App\Models\LoyaltyUser;

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

        $customer = null;
        if (session('cashier_customer_id')) {
            $customer = Customer::where('customerID', session('cashier_customer_id'))
                ->whereNull('archivedAt')
                ->first();
            if (!$customer) {
                session()->forget(['cashier_customer_id', 'cashier_step']);
            }
        }

        $step = session('cashier_step', null);
        $rewards = Reward::where('status', 'active')->where('stock', '>', 0)->get();

        return view('cashier.manage-points', compact('customer', 'step', 'rewards'));
    }

    public function searchCustomer(Request $request)
    {
        $this->checkAccess();

        $request->validate([
            'search' => 'required|string',
        ]);

        $search = $request->search;

        $customer = Customer::where('phoneNumber', $search)
            ->whereNull('archivedAt')
            ->first();

        if (!$customer) {
            return back()->with('error', 'Customer not found. Please check the phone number.');
        }

        session([
            'cashier_customer_id' => $customer->customerID,
            'cashier_step' => null,
        ]);

        return redirect('/cashier/manage-points');
    }

    public function setStep($step)
    {
        $this->checkAccess();

        if (!in_array($step, ['add', 'redeem', 'reset'])) {
            return redirect('/cashier/manage-points');
        }

        if (!session('cashier_customer_id')) {
            return redirect('/cashier/manage-points');
        }

        session(['cashier_step' => $step]);

        return redirect('/cashier/manage-points');
    }

    public function goBack()
    {
        $this->checkAccess();

        if (session('cashier_step')) {
            session()->forget('cashier_step');
        } else {
            session()->forget(['cashier_customer_id', 'cashier_step']);
        }

        return redirect('/cashier/manage-points');
    }

    public function addPoints(Request $request)
    {
        $this->checkAccess();

        if (!session('cashier_customer_id')) {
            return redirect('/cashier/manage-points');
        }

        $request->validate([
            'totalPrice' => 'required|numeric|min:0.01',
        ]);

        $customer = Customer::where('customerID', session('cashier_customer_id'))
            ->whereNull('archivedAt')
            ->first();

        if (!$customer) {
            session()->forget(['cashier_customer_id', 'cashier_step']);
            return redirect('/cashier/manage-points')->with('error', 'Customer not found or inactive.');
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

        session()->forget('cashier_step');

        return redirect('/cashier/manage-points')
            ->with('success', "$pointEarned points added to {$customer->customerName}. Transaction: $transactionID");
    }

    public function redeemPoints(Request $request)
    {
        $this->checkAccess();

        if (!session('cashier_customer_id')) {
            return redirect('/cashier/manage-points');
        }

        $request->validate([
            'rewardID' => 'required|string|exists:Reward,rewardID',
        ]);

        $customer = Customer::where('customerID', session('cashier_customer_id'))
            ->whereNull('archivedAt')
            ->first();

        if (!$customer) {
            session()->forget(['cashier_customer_id', 'cashier_step']);
            return redirect('/cashier/manage-points')->with('error', 'Customer not found or inactive.');
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

        session()->forget('cashier_step');

        return redirect('/cashier/manage-points')
            ->with('success', "{$customer->customerName} redeemed {$reward->rewardName}. Redemption: $redemptionID");
    }

    public function resetPassword(Request $request)
    {
        $this->checkAccess();

        if (!session('cashier_customer_id')) {
            return redirect('/cashier/manage-points');
        }

        $request->validate([
            'newPassword' => 'required|string|min:4',
        ]);

        $customer = Customer::where('customerID', session('cashier_customer_id'))
            ->whereNull('archivedAt')
            ->first();

        if (!$customer) {
            session()->forget(['cashier_customer_id', 'cashier_step']);
            return redirect('/cashier/manage-points')->with('error', 'Customer not found or inactive.');
        }

        $user = LoyaltyUser::where('userID', $customer->customerID)->first();

        if (!$user) {
            return back()->with('error', 'User account not found for this customer.');
        }

        $user->update([
            'password' => Hash::make($request->newPassword),
        ]);

        session()->forget('cashier_step');

        $cashierName = session('username');

        return redirect('/cashier/manage-points')
            ->with('success', "Password for {$customer->customerName} has been reset by {$cashierName}.");
    }
}
