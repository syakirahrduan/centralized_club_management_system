<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'role' => 'required|in:member,club_admin,system_admin',
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // If Member or Club Admin is selected, redirect to blank page (groupmates' part)
        if (in_array($request->role, ['member', 'club_admin'])) {
            return redirect()->route('portal.placeholder');
        }

        // System Admin authentication (supports Email or Staff ID)
        $user = \App\Models\User::where('role', 'system_admin')
            ->where(function ($query) use ($request) {
                $query->where('email', $request->login_id)
                      ->orWhere('staff_id', $request->login_id);
            })
            ->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            \Illuminate\Support\Facades\Auth::login($user);
            $request->session()->regenerate();

            return redirect()->route('system_admin.dashboard');
        }

        return back()->withErrors([
            'login_id' => 'Invalid System Admin credentials.',
        ])->onlyInput('login_id', 'role');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Ignore Member registration flow (groupmate's part) -> redirect to blank page
        if ($request->role === 'member') {
            return redirect()->route('portal.placeholder');
        }

        // Only process Club Admin registration
        $request->validate([
            'role' => 'required|in:club_admin',
            'name' => 'required|string|max:255',
            'staff_id' => 'required|string|max:50',
            'requested_club_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'club_admin',
            'status' => 'pending',
            'staff_id' => $request->staff_id,
            'requested_club_name' => $request->requested_club_name,
        ]);

        return redirect()->route('login')->with(
            'success',
            'Club Admin registration submitted! Your account is pending System Admin approval.'
        );
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}