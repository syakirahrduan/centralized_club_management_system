@extends('layouts.club-admin')

@section('title', 'Membership Applications')

@section('content')
<div class="mb-4">
    <div class="fw-bold text-uppercase mb-1" style="color: #0d9488; font-size: 0.78rem; letter-spacing: 1px;">
        {{ $club->name ?? 'CLUB MANAGEMENT' }}
    </div>
    <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2rem;">Membership Applications</h2>
    <p class="text-muted mt-1">Review and respond to students who want to join your club.</p>
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
        <!-- Search and Filter -->
        <form action="{{ route('club_admin.applications.index') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search records..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select text-muted" onchange="this.form.submit()">
                    <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All statuses</option>
                    <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                        <th class="fw-semibold pb-3">STATUS</th>
                        <th class="fw-semibold pb-3 text-end">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $app)
                        @php
                            $initials = strtoupper(substr($app->name, 0, 1) . (str_contains($app->name, ' ') ? substr(strrchr($app->name, ' '), 1, 1) : substr($app->name, 1, 1)));
                        @endphp
                        <tr style="border-bottom: 1px solid #f8fafc;">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center avatar-initials">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $app->name }}</div>
                                        <div class="text-muted small">{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $app->student_id ?? 'N/A' }}</td>
                            <td class="text-muted">{{ $app->email }}</td>
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
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <button type="button" class="action-icon-btn text-secondary" data-bs-toggle="modal" data-bs-target="#applicationModal{{ $app->id }}" title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($app->status === 'pending')
                                        <form action="{{ route('club_admin.members.approve', $app->id) }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <button type="submit" class="action-icon-btn text-success" title="Approve Application">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('club_admin.members.reject', $app->id) }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            <button type="submit" class="action-icon-btn text-danger" title="Reject Application">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
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

    <!-- Application Details Modals -->
    @foreach($applications as $app)
        @php
            $initials = strtoupper(substr($app->name, 0, 1) . (str_contains($app->name, ' ') ? substr(strrchr($app->name, ' '), 1, 1) : substr($app->name, 1, 1)));
        @endphp
        <div class="modal fade" id="applicationModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow" style="border-radius: 18px; padding: 12px;">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <div class="fw-bold text-uppercase" style="color: #0d9488; font-size: 0.72rem; letter-spacing: 1px;">CLUBHUB</div>
                            <h4 class="modal-title fw-bold mt-1" style="color: #0f172a;">Application details</h4>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center avatar-initials-lg">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="fw-bold fs-5 text-dark">{{ $app->name }}</div>
                                    <div class="text-muted small">{{ $app->student_id ?? 'N/A' }} &bull; {{ $app->email }}</div>
                                </div>
                            </div>
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
                        </div>

                        <div class="row border-top border-bottom py-3 mb-4 mx-0">
                            <div class="col-6 ps-0">
                                <div class="text-uppercase text-muted fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">CLUB</div>
                                <div class="fw-medium text-dark mt-1">{{ $club->name ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-uppercase text-muted fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">SUBMITTED</div>
                                <div class="fw-medium text-dark mt-1">{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y') }}</div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3" style="background-color: #f8fafc;">
                            <div class="text-uppercase text-muted fw-semibold mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">REASON FOR JOINING</div>
                            <p class="mb-0 text-dark" style="font-size: 0.95rem;">
                                {{ $app->application_message ?? 'No reason provided by the applicant.' }}
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-2">
                        @if($app->status === 'pending')
                            <form action="{{ route('club_admin.members.reject', $app->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold" style="border-radius: 10px;">Reject</button>
                            </form>
                            <form action="{{ route('club_admin.members.approve', $app->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-teal px-4 py-2 fw-semibold">Approve application</button>
                            </form>
                        @else
                            <form action="{{ route('club_admin.members.updateStatus', $app->id) }}" method="POST" class="d-flex align-items-center gap-2 w-100 justify-content-end m-0">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select text-muted" style="width: 150px; border-radius: 10px;">
                                    <option value="pending" {{ $app->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $app->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $app->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <button type="submit" class="btn btn-teal px-4 py-2 fw-semibold">Update status</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
@endsection