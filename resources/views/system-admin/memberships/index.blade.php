@extends('layouts.admin')

@section('title', 'Membership Overview')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold m-0" style="color: #0f172a;">Membership Overview</h2>
    <p class="text-muted mt-1">Monitor application activity across clubs. Approval remains with Club Admins.</p>
</div>

<div class="card p-4">
    <!-- Search and Filter Row -->
    <form action="{{ route('system_admin.memberships.index') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search records..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select text-muted" onchange="this.form.submit()">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All statuses</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
    </form>

    <!-- Applications Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="text-muted small" style="border-bottom: 1px solid #f1f5f9;">
                <tr>
                    <th class="fw-semibold pb-3">MEMBER</th>
                    <th class="fw-semibold pb-3">STUDENT ID</th>
                    <th class="fw-semibold pb-3">EMAIL</th>
                    <th class="fw-semibold pb-3">CLUB</th>
                    <th class="fw-semibold pb-3">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background-color: #e6f7f2; color: #0f766e; font-size: 0.85rem;">
                                    {{ strtoupper(substr($app->member_name, 0, 1) . (str_contains($app->member_name, ' ') ? substr(strrchr($app->member_name, ' '), 1, 1) : substr($app->member_name, 1, 1))) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $app->member_name }}</div>
                                    <div class="text-muted small">{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $app->student_id ?? 'N/A' }}</td>
                        <td class="text-muted">{{ $app->member_email }}</td>
                        <td class="fw-medium text-dark">{{ $app->club_name }}</td>
                        <td>
                            @if($app->status === 'pending')
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #fef3c7; color: #d97706;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> Pending
                                </span>
                            @elseif($app->status === 'approved')
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #ecfdf5; color: #059669;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> Approved
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #fee2e2; color: #dc2626;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> Rejected
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No membership applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection