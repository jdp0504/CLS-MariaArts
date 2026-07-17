<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Reward;
use App\Models\Redemption;

class MembershipStatusController extends Controller
{
    public function showAdminDashboard()
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access the dashboard.');
        }

        $totalMembers = Customer::where('status', 'active')->count();
        $activeRewards = Reward::where('status', 'active')->count();
        $claimsThisMonth = Redemption::whereMonth('redeemedDate', now()->month)
                                      ->whereYear('redeemedDate', now()->year)
                                      ->count();

        return view('admin-dashboard', compact('totalMembers', 'activeRewards', 'claimsThisMonth'));
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
                $q->where('customerID', 'LIKE', "%$search%")
                  ->orWhere('customerName', 'LIKE', "%$search%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $customers = $query->orderBy('customerName')->get();

        $totalActive = Customer::where('status', 'active')->count();
        $totalInactive = Customer::where('status', 'inactive')->count();

        return view('admin.manage-membership', compact('customers', 'search', 'status', 'totalActive', 'totalInactive'));
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
