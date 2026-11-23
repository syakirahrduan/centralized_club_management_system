<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MembershipOverviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');

        $query = DB::table('club_user')
            ->join('users', 'club_user.user_id', '=', 'users.id')
            ->join('clubs', 'club_user.club_id', '=', 'clubs.id')
            ->select(
                'club_user.id',
                'club_user.status',
                'club_user.created_at',
                'users.name as member_name',
                'users.email as member_email',
                'users.student_id',
                'clubs.name as club_name'
            );

        if ($status !== 'all') {
            $query->where('club_user.status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('users.student_id', 'like', "%{$search}%")
                  ->orWhere('clubs.name', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('club_user.created_at', 'desc')->get();

        return view('system-admin.memberships.index', compact('applications', 'status', 'search'));
    }
}