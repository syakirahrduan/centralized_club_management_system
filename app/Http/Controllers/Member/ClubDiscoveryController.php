<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubDiscoveryController extends Controller
{
    // 1. Display all clubs & 2. Search/Filter
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $clubs = Club::query()
            ->withCount(['members as approved_members_count' => function ($q) {
                $q->where('club_user.status', 'approved');
            }])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category, function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(9);

        $categories = Club::select('category')
            ->distinct()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->pluck('category');

        $userClubStatuses = Auth::user()->clubs()
            ->pluck('club_user.status', 'clubs.id')
            ->toArray();

        return view('member.clubs.index', compact('clubs', 'categories', 'search', 'category', 'userClubStatuses'));
    }

    // 3. View club details
    public function show(Club $club)
    {
        $club->load('admin');
        $membership = Auth::user()->clubs()->where('club_id', $club->id)->first();
        $memberCount = $club->members()->where('club_user.status', 'approved')->count();

        return view('member.clubs.show', compact('club', 'membership', 'memberCount'));
    }

    // 4. Submit membership application
    public function apply(Club $club)
    {
        $user = Auth::user();

        if ($user->clubs()->where('club_id', $club->id)->exists()) {
            return redirect()->back()->with('error', 'You have already applied to or joined this club.');
        }

        $user->clubs()->attach($club->id, [
            'status' => 'pending',
        ]);

        return redirect()->route('member.clubs.applications')
            ->with('success', "Application submitted to {$club->name}! Status is currently Pending.");
    }

    // 5. View joined clubs ONLY (Approved memberships)
    public function myClubs()
    {
        $joinedClubs = Auth::user()->clubs()
            ->where('club_user.status', 'approved')
            ->with('admin')
            ->withPivot('created_at', 'updated_at')
            ->get();

        return view('member.clubs.my_clubs', compact('joinedClubs'));
    }

    // 6. View all applications (Separate page with search & status filter)
    public function applications(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $applications = Auth::user()->clubs()
            ->when($search, function ($query, $search) {
                $query->where('clubs.name', 'like', "%{$search}%");
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                $query->where('club_user.status', $statusFilter);
            })
            ->orderByPivot('created_at', 'desc')
            ->get();

        return view('member.clubs.applications', compact('applications', 'search', 'statusFilter'));
    }

    // 7. Cancel pending application
    public function cancelApplication(Club $club)
    {
        $user = Auth::user();
        $membership = $user->clubs()->where('club_id', $club->id)->first();

        if ($membership && $membership->pivot->status === 'pending') {
            $user->clubs()->detach($club->id);
            return redirect()->back()->with('success', "Application to {$club->name} has been cancelled.");
        }

        return redirect()->back()->with('error', 'Only pending applications can be cancelled.');
    }

    // 8. Leave an approved club
    public function leaveClub(Club $club)
    {
        $user = Auth::user();
        $membership = $user->clubs()->where('club_id', $club->id)->first();

        if ($membership && $membership->pivot->status === 'approved') {
            $user->clubs()->detach($club->id);
            return redirect()->back()->with('success', "You have left {$club->name}.");
        }

        return redirect()->back()->with('error', 'Unable to leave club.');
    }
}