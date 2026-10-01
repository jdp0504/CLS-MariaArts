<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Reward;
use App\Models\Redemption;
use App\Models\PurchaseTransaction;

class MembershipStatusController extends Controller
{
    public function showAdminDashboard()
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access the dashboard.');
        }

        $totalMembers = Customer::where('status', 'active')->count();
        $inactiveMembers = Customer::where('status', 'inactive')->count();
        $activeRewards = Reward::where('status', 'active')->count();
        $claimsThisMonth = Redemption::whereMonth('redeemedDate', now()->month)
                                      ->whereYear('redeemedDate', now()->year)
                                      ->count();

        return view('admin-dashboard', compact('totalMembers', 'inactiveMembers', 'activeRewards', 'claimsThisMonth'));
    }

    public function manageMembership(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $search = $request->input('search');
        $status = $request->input('status', 'all');

        $query = Customer::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Customer.customerID', 'LIKE', "%$search%")
                  ->orWhere('Customer.customerName', 'LIKE', "%$search%");
            });
        }

        if ($status !== 'all') {
            $query->where('Customer.status', $status);
        }

        $customers = $query
            ->leftJoin('User', 'User.userID', '=', 'Customer.customerID')
            ->select('Customer.*', 'User.createdDate as registeredDate')
            ->selectSub(
                PurchaseTransaction::selectRaw('MAX(transactionDate)')
                    ->whereColumn('PurchaseTransaction.customerID', 'Customer.customerID'),
                'lastPurchaseDate'
            )
            ->selectSub(
                Redemption::selectRaw('MAX(redeemedDate)')
                    ->whereColumn('Redemption.customerID', 'Customer.customerID'),
                'lastRedemptionDate'
            )
            ->orderBy('Customer.customerName')
            ->get();

        foreach ($customers as $customer) {
            $dates = array_filter([$customer->lastPurchaseDate, $customer->lastRedemptionDate]);
            $last  = $dates ? max($dates) : null;

            $customer->lastActivityDate  = $last;
            $customer->hasActivity       = (bool) $last;
            $customer->lastActivityLabel = $last
                ? $this->relativeDate($last)
                : $this->relativeDate($customer->registeredDate);
        }

        $totalActive = Customer::where('status', 'active')->count();
        $totalInactive = Customer::where('status', 'inactive')->count();

        return view('admin.manage-membership', compact('customers', 'search', 'status', 'totalActive', 'totalInactive'));
    }

    private function relativeDate($date)
    {
        if (!$date) {
            return 'Unknown';
        }

        $days = \Illuminate\Support\Carbon::parse($date)->diffInDays(now());

        if ($days === 0)  return 'Today';
        if ($days === 1)  return 'Yesterday';
        if ($days < 30)   return $days . ' days ago';

        if ($days < 365) {
            $m = intdiv($days, 30);
            return $m . ' month' . ($m > 1 ? 's' : '') . ' ago';
        }

        $y = intdiv($days, 365);
        return $y . ' year' . ($y > 1 ? 's' : '') . ' ago';
    }

    public function changeStatus(Request $request)
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access this page.');
        }

        $request->validate([
            'customerID' => 'required|string|exists:Customer,customerID',
        ]);

        $customer = Customer::findOrFail($request->customerID);

        if ($customer->status === 'active') {
            $customer->update([
                'status'     => 'inactive',
                'archivedAt' => now(),
            ]);

            $action = 'archived';
        } else {
            $customer->update([
                'status'     => 'active',
                'archivedAt' => null,
            ]);

            $action = 'reactivated';
        }

        $adminName = session('username');

        return redirect('/admin/manage-membership')
            ->with('success', "{$customer->customerName} has been {$action} by {$adminName}.");
    }
}
