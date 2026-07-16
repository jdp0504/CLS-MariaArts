<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoyaltyUser;
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

    public function logout()
    {
        session()->forget(['user_id', 'username', 'role', 'failed_attempts', 'lockout_until']);
        return redirect('/my-login');
    }
}
