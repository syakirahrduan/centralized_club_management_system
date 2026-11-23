<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        
        $query = User::where('role', 'club_admin');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $admins = $query->latest()->get();
        
        $counts = [
            'pending' => User::where('role', 'club_admin')->where('status', 'pending')->count(),
            'active' => User::where('role', 'club_admin')->where('status', 'active')->count(),
            'rejected' => User::where('role', 'club_admin')->where('status', 'rejected')->count(),
        ];

        $clubs = Club::all();

        return view('system-admin.club-admins.index', compact('admins', 'status', 'counts', 'clubs'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id'
        ]);

        $admin = User::findOrFail($id);
        
        $admin->status = 'active'; 
        $admin->club_id = $request->club_id; 
        
        $admin->save();

        return redirect()->back()->with('success', 'Club Admin approved and assigned successfully.');
    }

    public function reject(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', 'Club Admin rejected.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'club_id' => 'nullable|exists:clubs,id',
            'status' => 'required|in:pending,active,rejected'
        ]);

        $admin = User::findOrFail($id);
        $admin->status = $request->status;
        $admin->club_id = $request->club_id;
        $admin->save();

        return redirect()->back()->with('success', 'Club Admin updated successfully.');
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);
        $admin->delete();

        return redirect()->back()->with('success', 'Club Admin deleted successfully.');
    }
}