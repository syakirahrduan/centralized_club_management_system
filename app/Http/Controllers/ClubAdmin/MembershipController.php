<?php

namespace App\Http\Controllers\ClubAdmin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MembershipController extends Controller
{
    public function applications(Request $request)
    {
        $admin = Auth::user();

        if ($admin->role !== 'club_admin') {
            abort(403, 'Unauthorized access.');
        }

        $club = $admin->club_id ? Club::find($admin->club_id) : null;
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search');

        $pendingCount = 0;
        $applications = collect();

        if ($admin->status === 'active' && $admin->club_id) {
            $pendingCount = DB::table('club_user')
                ->where('club_id', $admin->club_id)
                ->where('status', 'pending')
                ->count();

            // Fetch all membership applications for the admin's club
            $query = DB::table('club_user')
                ->join('users', 'club_user.user_id', '=', 'users.id')
                ->where('club_user.club_id', $admin->club_id)
                ->select(
                    'club_user.id',
                    'club_user.status',
                    'club_user.application_message',
                    'club_user.created_at',
                    'users.name',
                    'users.email',
                    'users.student_id'
                );

            if ($statusFilter !== 'all') {
                $query->where('club_user.status', $statusFilter);
            }

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.student_id', 'like', "%{$search}%")
                      ->orWhere('users.email', 'like', "%{$search}%");
                });
            }

            $applications = $query->orderByRaw("FIELD(club_user.status, 'pending', 'approved', 'rejected')")
                ->orderBy('club_user.created_at', 'desc')
                ->get();
        }

        return view('club-admin.members.applications', compact(
            'admin',
            'club',
            'applications',
            'pendingCount',
            'statusFilter',
            'search'
        ));
    }

    public function index(Request $request)
    {
        $admin = Auth::user();

        if ($admin->role !== 'club_admin') {
            abort(403, 'Unauthorized access.');
        }

        $club = $admin->club_id ? Club::find($admin->club_id) : null;
        $search = $request->query('search');

        $pendingCount = 0;
        $approvedMembers = collect();

        if ($admin->status === 'active' && $admin->club_id) {
            $pendingCount = DB::table('club_user')
                ->where('club_id', $admin->club_id)
                ->where('status', 'pending')
                ->count();

            // Fetch only approved members for the admin's club
            $query = DB::table('club_user')
                ->join('users', 'club_user.user_id', '=', 'users.id')
                ->where('club_user.club_id', $admin->club_id)
                ->where('club_user.status', 'approved')
                ->select(
                    'club_user.id',
                    'club_user.status',
                    'club_user.updated_at',
                    'users.name',
                    'users.email',
                    'users.student_id'
                );

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                      ->orWhere('users.student_id', 'like', "%{$search}%")
                      ->orWhere('users.email', 'like', "%{$search}%");
                });
            }

            $approvedMembers = $query->orderBy('users.name', 'asc')->get();
        }

        return view('club-admin.members.index', compact(
            'admin',
            'club',
            'approvedMembers',
            'pendingCount',
            'search'
        ));
    }

    public function approve($id)
    {
        $admin = Auth::user();

        DB::table('club_user')
            ->where('id', $id)
            ->where('club_id', $admin->club_id)
            ->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Membership application approved successfully.');
    }

    public function reject($id)
    {
        $admin = Auth::user();

        DB::table('club_user')
            ->where('id', $id)
            ->where('club_id', $admin->club_id)
            ->update([
                'status' => 'rejected',
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Membership application rejected successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $admin = Auth::user();

        DB::table('club_user')
            ->where('id', $id)
            ->where('club_id', $admin->club_id)
            ->update([
                'status' => $validated['status'],
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Membership status updated successfully.');
    }

    public function destroy($id)
    {
        $admin = Auth::user();

        DB::table('club_user')
            ->where('id', $id)
            ->where('club_id', $admin->club_id)
            ->delete();

        return back()->with('success', 'Member removed from club successfully.');
    }
}