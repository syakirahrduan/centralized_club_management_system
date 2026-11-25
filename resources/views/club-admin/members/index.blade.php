@extends('layouts.club-admin')

@section('title', 'Members')

@section('content')
<div class="mb-4">
    <div class="fw-bold text-uppercase mb-1" style="color: #0d9488; font-size: 0.78rem; letter-spacing: 1px;">
        {{ $club->name ?? 'CLUB MANAGEMENT' }}
    </div>
    <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2rem;">Members</h2>
    <p class="text-muted mt-1">View and manage approved members of your club.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 10px;">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($admin->status === 'pending')
    <div class="alert alert-warning" style="border-radius: 10px;">
        <i class="bi bi-clock-history me-1"></i> Your request to manage <strong>{{ $admin->requested_club_name }}</strong> is pending System Admin approval.
    </div>
@elseif($admin->status === 'rejected')
    <div class="alert alert-danger" style="border-radius: 10px;">
        <i class="bi bi-x-circle me-1"></i> Your Club Admin request was declined by the System Admin.
    </div>
@else
    <div class="card p-4">
        <!-- Search Row -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <form action="{{ route('club_admin.members.index') }}" method="GET" class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search members..." value="{{ $search }}">
                </div>
            </form>
            <span class="text-muted small fw-medium">
                {{ $approvedMembers->count() }} active members
            </span>
        </div>

        <!-- Active Members Table -->
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="text-muted small" style="border-bottom: 1px solid #f1f5f9;">
                    <tr>
                        <th class="fw-semibold pb-3">MEMBER</th>
                        <th class="fw-semibold pb-3">STUDENT ID</th>
                        <th class="fw-semibold pb-3">JOINED</th>
                        <th class="fw-semibold pb-3">STATUS</th>
                        <th class="fw-semibold pb-3 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedMembers as $member)
                        <tr style="border-bottom: 1px solid #f8fafc;">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center avatar-initials">
                                        {{ strtoupper(substr($member->name, 0, 1) . (str_contains($member->name, ' ') ? substr(strrchr($member->name, ' '), 1, 1) : substr($member->name, 1, 1))) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $member->name }}</div>
                                        <div class="text-muted small">{{ $member->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $member->student_id ?? 'N/A' }}</td>
                            <td class="text-muted">{{ \Carbon\Carbon::parse($member->updated_at)->format('M d, Y') }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #ecfdf5; color: #059669;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> Active
                                </span>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('club_admin.members.destroy', $member->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Are you sure you want to remove {{ $member->name }} from this club?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger text-decoration-none fw-medium p-1">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No active members found for this club.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection