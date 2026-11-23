@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <div class="fw-bold text-uppercase mb-1" style="color: #0d9488; font-size: 0.78rem; letter-spacing: 1px;">OVERVIEW</div>
    <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2rem;">System Admin Dashboard</h2>
    <p class="text-muted mt-1">Monitor clubs, club administrators, and member accounts across the platform.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- 4 Stat Cards Row -->
<div class="row g-4 mb-4">
    <!-- Total Clubs -->
    <div class="col-md-3">
        <div class="card p-4 h-100" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-semibold small">Total Clubs</span>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #ecfdf5; color: #059669;">
                    <i class="bi bi-house fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2.25rem;">{{ $totalClubs }}</h2>
        </div>
    </div>

    <!-- Club Admins -->
    <div class="col-md-3">
        <div class="card p-4 h-100" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-semibold small">Club Admins</span>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #f0fdfa; color: #0d9488;">
                    <i class="bi bi-person-badge fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2.25rem;">{{ $totalClubAdmins }}</h2>
        </div>
    </div>

    <!-- Members -->
    <div class="col-md-3">
        <div class="card p-4 h-100" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-semibold small">Members</span>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #eff6ff; color: #2563eb;">
                    <i class="bi bi-people fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2.25rem;">{{ $totalMembers }}</h2>
        </div>
    </div>

    <!-- Pending Approval -->
    <div class="col-md-3">
        <div class="card p-4 h-100" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted fw-semibold small">Pending Approval</span>
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: #fffbeb; color: #d97706;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2.25rem;">{{ $pendingApprovals }}</h2>
        </div>
    </div>
</div>

<!-- Bottom Section: Pending Registrations & Recent Activity -->
<div class="row g-4 align-items-start">
    <!-- Left Column: Registration Requests Requiring Review -->
    <div class="col-lg-8">
        <div class="card p-0 overflow-hidden" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <div class="p-4 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h5 class="fw-bold m-0" style="color: #0f172a;">Registration requests requiring review</h5>
                    <p class="text-muted small m-0 mt-1">Pending Club Admin applications awaiting approval.</p>
                </div>
                <a href="{{ route('system_admin.club_admins.index') }}" class="btn btn-sm btn-outline-secondary fw-semibold px-3 py-2" style="border-radius: 8px;">
                    View all
                </a>
            </div>

            <div class="table-responsive p-4">
                <table class="table align-middle mb-0">
                    <thead class="text-muted small" style="border-bottom: 1px solid #f1f5f9;">
                        <tr>
                            <th class="fw-semibold pb-3">CLUB ADMIN</th>
                            <th class="fw-semibold pb-3">STAFF ID</th>
                            <th class="fw-semibold pb-3">REQUESTED CLUB</th>
                            <th class="fw-semibold pb-3 text-end">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingClubAdmins as $admin)
                            <tr style="border-bottom: 1px solid #f8fafc;">
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; background-color: #e6f7f2; color: #0f766e; font-size: 0.85rem;">
                                            {{ strtoupper(substr($admin->name, 0, 1) . (str_contains($admin->name, ' ') ? substr(strrchr($admin->name, ' '), 1, 1) : substr($admin->name, 1, 1))) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $admin->name }}</div>
                                            <div class="text-muted small">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $admin->staff_id ?? 'N/A' }}</td>
                                <td class="fw-medium text-dark">{{ $admin->requested_club_name ?? 'N/A' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('system_admin.club_admins.approve', $admin->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success me-1" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('system_admin.club_admins.reject', $admin->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No pending Club Admin requests at the moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Activity -->
    <div class="col-lg-4">
        <div class="card p-4" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold m-0" style="color: #0f172a;">Recent activity</h5>
                    <p class="text-muted small m-0 mt-1">Latest updates</p>
                </div>
                <a href="{{ route('system_admin.memberships.index') }}" class="text-decoration-none fw-semibold small" style="color: #0d9488;">
                    View all
                </a>
            </div>

            @forelse($recentActivities as $activity)
                <div class="d-flex align-items-start {{ !$loop->last ? 'pb-3 mb-3 border-bottom' : '' }}" style="border-color: #f1f5f9 !important;">
                    @if($activity->status === 'approved')
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 34px; height: 34px; background-color: #ecfdf5; color: #059669;">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark small">Membership approved</div>
                            <div class="text-muted small">{{ $activity->member_name }} was approved for {{ $activity->club_name }}.</div>
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</div>
                        </div>
                    @elseif($activity->status === 'rejected')
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 34px; height: 34px; background-color: #fee2e2; color: #dc2626;">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark small">Membership rejected</div>
                            <div class="text-muted small">{{ $activity->member_name }}'s application to {{ $activity->club_name }} was rejected.</div>
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</div>
                        </div>
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" 
                             style="width: 34px; height: 34px; background-color: #fffbeb; color: #d97706;">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div>
                            <div class="fw-semibold text-dark small">New membership application</div>
                            <div class="text-muted small">{{ $activity->member_name }} applied to {{ $activity->club_name }}.</div>
                            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($activity->updated_at)->diffForHumans() }}</div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-4 text-muted small">No recent membership activity yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection