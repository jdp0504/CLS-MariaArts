<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyUser;
use App\Models\Customer;
use App\Models\Referral;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register-loyalty');
    }

    public function registerCustomer(Request $request)
    {
        $request->validate([
            'customerName'  => 'required|string|max:150',
            'username'      => 'required|string|max:50|unique:User,username',
            'email'         => 'required|email|max:100|unique:Customer,email',
            'phoneNumber'   => 'nullable|string|max:20|unique:Customer,phoneNumber',
            'birthDate'     => 'nullable|date',
            'password'      => 'required|min:4',
            'referral_code' => 'nullable|string|max:50|exists:Customer,referralCode',
        ], [
            'customerName.required' => 'Incomplete or Invalid Information',
            'username.required'     => 'Incomplete or Invalid Information',
            'username.unique'       => 'Username already taken',
            'email.required'        => 'Incomplete or Invalid Information',
            'email.email'           => 'Please include an \'@\' in the email address',
            'email.unique'          => 'Customer already registered',
            'phoneNumber.unique'    => 'Customer already registered',
            'password.required'     => 'Incomplete or Invalid Information',
            'password.min'          => 'Incomplete or Invalid Information',
            'referral_code.exists'  => 'Invalid referral code',
        ]);

        $last = Customer::orderBy('customerID', 'desc')->first();
        $num = $last ? (int) substr($last->customerID, 3) + 1 : 1;
        $customerID = 'CUS' . str_pad($num, 3, '0', STR_PAD_LEFT);

        $username = $request->username;

        LoyaltyUser::create([
            'userID'      => $customerID,
            'username'    => $username,
            'role'        => 'customer',
            'createdDate' => now()->toDateString(),
            'password'    => Hash::make($request->password),
        ]);

        $referralCode = strtoupper(substr($request->customerName, 0, 3)) . $num;

        Customer::create([
            'customerID'    => $customerID,
            'customerName'  => $request->customerName,
            'birthDate'     => $request->birthDate,
            'phoneNumber'   => $request->phoneNumber,
            'referralCode'  => $referralCode,
            'currentPoints' => 0,
            'email'         => $request->email,
            'status'        => 'active',
            'archivedAt'    => null,
        ]);

        if ($request->filled('referral_code')) {
            $referrer = Customer::where('referralCode', $request->referral_code)->first();

            $lastRef = Referral::orderBy('referralID', 'desc')->first();
            $refNum = $lastRef ? (int) substr($lastRef->referralID, 3) + 1 : 1;
            $referralID = 'REF' . str_pad($refNum, 3, '0', STR_PAD_LEFT);

            Referral::create([
                'referralID'   => $referralID,
                'customerID'   => $referrer->customerID,
                'pointGranted' => 50,
                'dateRef'      => now()->toDateString(),
            ]);

            $referrer->increment('currentPoints', 50);
        }

        return redirect('/registration-success')->with([
            'customerID'   => $customerID,
            'customerName' => $request->customerName,
            'referralCode' => $referralCode,
        ]);
    }

    public function showRegistrationSuccess()
    {
        if (!session('customerID')) {
            return redirect('/register');
        }
        return view('registration-success');
    }
}
