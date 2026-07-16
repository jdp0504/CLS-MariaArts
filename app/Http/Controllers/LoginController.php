<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyUser;
use App\Models\Customer;
use App\Models\Referral;
use App\Models\Reward;
use App\Models\Redemption;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('custom-login');
    }

    public function processLogin(Request $request)
    {
        if (!session()->has('failed_attempts')) {
            session(['failed_attempts' => 0]);
        }
        if (!session()->has('lockout_until')) {
            session(['lockout_until' => null]);
        }

        $now = time();

        if (session('lockout_until') && $now < session('lockout_until')) {
            $lockout_seconds = session('lockout_until') - $now;
            return back()->with('error', 'LOCKED')->with('lockout_seconds', $lockout_seconds);
        }

        if (session('lockout_until') && $now >= session('lockout_until')) {
            session(['failed_attempts' => 0, 'lockout_until' => null]);
        }

        $username = $request->input('username') ?? '';
        $password = $request->input('password') ?? '';
        $role = $request->input('role') ?? 'customer';

        if ($role === 'staff') {
            $user = LoyaltyUser::where('username', $username)
                ->whereIn('role', ['admin', 'cashier'])
                ->first();
        } else {
            $user = LoyaltyUser::where('username', $username)
                ->where('role', 'customer')
                ->first();
        }

        if ($user && Hash::check($password, $user->password)) {
            session([
                'user_id' => $user->userID,
                'username' => $user->username,
                'role' => $user->role,
                'failed_attempts' => 0,
                'lockout_until' => null,
            ]);

            switch ($user->role) {
                case 'admin':
                    return redirect('/admin-dashboard');
                case 'cashier':
                    return redirect('/cashier-dashboard');
                case 'customer':
                    return redirect('/customer-dashboard');
                default:
                    return redirect('/my-login')->with('error', 'Unknown role.');
            }
        }

        $attempts = session('failed_attempts') + 1;
        session(['failed_attempts' => $attempts]);

        if ($attempts >= 5) {
            session(['lockout_until' => time() + 10]);
            return back()->with('error', 'LOCKED')->with('lockout_seconds', 10);
        }

        $remaining = 5 - $attempts;
        return back()->with('error', "Invalid username or password. Attempts left: $remaining");
    }

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

    public function showAdminDashboard()
    {
        if (session('role') !== 'admin') {
            return redirect('/my-login')->with('error', 'Please log in to access the dashboard.');
        }
        return view('admin-dashboard');
    }

    public function showCashierDashboard()
    {
        if (session('role') !== 'cashier') {
            return redirect('/my-login')->with('error', 'Please log in to access the dashboard.');
        }
        return view('cashier-dashboard');
    }

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

    public function logout()
    {
        session()->forget(['user_id', 'username', 'role', 'failed_attempts', 'lockout_until']);
        return redirect('/my-login');
    }
}
