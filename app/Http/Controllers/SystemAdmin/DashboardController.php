<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClubs = Club::count();
        $totalClubAdmins = User::where('role', 'club_admin')->count();
        $totalMembers = User::where('role', 'member')->count();
        $pendingApprovals = User::where('role', 'club_admin')
            ->where('status', 'pending')
            ->count();

        $pendingClubAdmins = User::where('role', 'club_admin')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentActivities = DB::table('club_user')
            ->join('users', 'club_user.user_id', '=', 'users.id')
            ->join('clubs', 'club_user.club_id', '=', 'clubs.id')
            ->select(
                'club_user.status',
                'club_user.updated_at',
                'users.name as member_name',
                'clubs.name as club_name'
            )
            ->orderBy('club_user.updated_at', 'desc')
            ->take(5)
            ->get();

        return view('system-admin.dashboard', compact(
            'totalClubs',
            'totalClubAdmins',
            'totalMembers',
            'pendingApprovals',
            'pendingClubAdmins',
            'recentActivities'
        ));
    }
}