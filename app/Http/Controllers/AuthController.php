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

        // 1. Club Admin -> redirect to blank page (groupmate's part)
        if ($request->role === 'club_admin') {
            return redirect()->route('portal.placeholder');
        }

        // 2. Member authentication (supports Email or Matric Student ID)
        if ($request->role === 'member') {
            $member = User::where('role', 'member')
                ->where(function ($query) use ($request) {
                    $query->where('email', $request->login_id)
                          ->orWhere('student_id', $request->login_id);
                })
                ->first();

            if ($member && Hash::check($request->password, $member->password)) {
                Auth::login($member);
                $request->session()->regenerate();

                return redirect()->route('member.clubs.index');
            }

            return back()->withErrors([
                'login_id' => 'Invalid Member credentials.',
            ])->onlyInput('login_id', 'role');
        }

        // 3. System Admin authentication (supports Email or Staff ID)
        $user = User::where('role', 'system_admin')
            ->where(function ($query) use ($request) {
                $query->where('email', $request->login_id)
                      ->orWhere('staff_id', $request->login_id);
            })
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
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
        $request->validate([
            'role' => 'required|in:member,club_admin',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Handle Member Registration
        if ($request->role === 'member') {
            $request->validate([
                'student_id' => 'required|string|max:50|unique:users,student_id',
            ]);

            $member = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'member',
                'status' => 'active',
                'student_id' => $request->student_id,
            ]);

            Auth::login($member);
            $request->session()->regenerate();

            return redirect()->route('member.clubs.index')
                ->with('success', 'Welcome to ClubHub IIUM!');
        }

        // Handle Club Admin Registration
        $request->validate([
            'staff_id' => 'required|string|max:50',
            'requested_club_name' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
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