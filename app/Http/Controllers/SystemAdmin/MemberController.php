<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = User::where('role', 'member')
            ->withCount(['clubs' => function ($q) {
                $q->where('club_user.status', 'approved');
            }]);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->get();

        return view('system-admin.members.index', compact('members', 'status', 'search'));
    }

    public function show(User $user)
    {
        $user->load('clubs');
        return view('system-admin.members.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('system-admin.members.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'student_id' => 'required|string|max:50',
            'status' => 'required|in:active,inactive',
        ]);

        $user->update($validated);

        return redirect()->route('system_admin.members.index')
            ->with('success', 'Member profile updated successfully.');
    }
}