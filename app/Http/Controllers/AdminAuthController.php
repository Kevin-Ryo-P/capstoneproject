<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    // Display the admin login form
    public function showLoginForm()
    {
        return view('admin.admin');
    }

    // Handle the admin login request
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->withErrors(['email' => 'You do not have access to this area.']);
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Display the admin dashboard
    public function dashboard()
    {
        return view('dashboardhome');
    }
}

