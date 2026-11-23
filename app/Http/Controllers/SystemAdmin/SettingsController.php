<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('system-admin.settings.index', [
            'user' => $request->user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'staff_id' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $validated['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        unset($validated['avatar']);
        $user->update($validated);

        return back()->with('success', 'Profile changes saved successfully.');
    }

    public function updatePassword(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}