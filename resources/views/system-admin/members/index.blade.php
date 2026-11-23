@extends('layouts.admin')

@section('title', 'Member Management')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold m-0" style="color: #0f172a;">Member Management</h2>
    <p class="text-muted mt-1">Search and manage all member accounts and profiles.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card p-4">
    <!-- Search and Filter Row -->
    <form action="{{ route('system_admin.members.index') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search records..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select" style="border-color: #10b981;" onchange="this.form.submit()">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All members</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </form>

    <!-- Members Table -->
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="text-muted small" style="border-bottom: 1px solid #f1f5f9;">
                <tr>
                    <th class="fw-semibold pb-3">MEMBER</th>
                    <th class="fw-semibold pb-3">STUDENT ID</th>
                    <th class="fw-semibold pb-3">MEMBERSHIPS</th>
                    <th class="fw-semibold pb-3">STATUS</th>
                    <th class="fw-semibold pb-3 text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px; background-color: #e6f7f2; color: #0f766e; font-size: 0.85rem;">
                                    {{ strtoupper(substr($member->name, 0, 1) . (str_contains($member->name, ' ') ? substr(strrchr($member->name, ' '), 1, 1) : substr($member->name, 1, 1))) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $member->name }}</div>
                                    <div class="text-muted small">{{ $member->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $member->student_id ?? 'N/A' }}</td>
                        <td class="text-muted">{{ $member->clubs_count }} {{ Str::plural('club', $member->clubs_count) }}</td>
                        <td>
                            @if($member->status === 'active')
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #ecfdf5; color: #059669;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> Active
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 fw-medium" style="background-color: #f1f5f9; color: #64748b;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 7px;"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('system_admin.members.show', $member->id) }}" class="btn btn-sm btn-outline-secondary border-light-subtle me-1" title="View Profile">
                                <i class="bi bi-eye text-dark"></i>
                            </a>
                            <a href="{{ route('system_admin.members.edit', $member->id) }}" class="btn btn-sm btn-outline-secondary border-light-subtle" title="Edit Profile">
                                <i class="bi bi-pencil text-dark"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No member accounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection