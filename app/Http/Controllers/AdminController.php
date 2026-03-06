<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $adminUsername = 'mit';
        $adminPassword = 'meet@3895';

        if ($credentials['username'] === $adminUsername && $credentials['password'] === $adminPassword) {
            $request->session()->put('admin_logged_in', true);
            $request->session()->put('admin_username', $adminUsername);
            
            return redirect()->route('admin.dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'username' => 'Invalid credentials.',
        ])->withInput();
    }

    public function dashboard(Request $request)
    {
        if (!$request->session()->get('admin_logged_in')) {
            return redirect()->route('admin.login');
        }

        $username = $request->session()->get('admin_username');
        return view('admin.dashboard', compact('username'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin_logged_in');
        $request->session()->forget('admin_username');
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully!');
    }
}
