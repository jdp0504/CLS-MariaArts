<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class ProfileController extends Controller
{
    public function showProfile()
    {
        if (session('role') !== 'customer') {
            return redirect('/my-login')->with('error', 'Please log in to access your profile.');
        }

        $customer = Customer::where('customerID', session('user_id'))->firstOrFail();
        return view('customer.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        if (session('role') !== 'customer') {
            return redirect('/my-login')->with('error', 'Please log in to access your profile.');
        }

        $customer = Customer::where('customerID', session('user_id'))->firstOrFail();

        $request->validate([
            'customerName' => 'required|string|max:150',
            'email'        => 'required|email|max:100|unique:Customer,email,' . $customer->customerID . ',customerID',
            'phoneNumber'  => 'nullable|string|max:20',
            'birthDate'    => 'nullable|date',
        ]);

        $customer->update([
            'customerName' => $request->customerName,
            'email'        => $request->email,
            'phoneNumber'  => $request->phoneNumber,
            'birthDate'    => $request->birthDate,
        ]);

        return redirect('/customer/profile')
            ->with('success', 'Your profile has been updated successfully.');
    }
}
