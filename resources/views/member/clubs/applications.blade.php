@extends('member.layout')

@section('title', 'My Applications')

@section('content')
<div class="mb-4">
    <div class="page-kicker">Application Tracking</div>
    <h2 class="fw-bold mb-1" style="color: #0f172a;">My Applications</h2>
    <p class="text-muted mb-0">Track every application and see its latest status.</p>
</div>

<div class="card">
    <div class="card-body p-4">
        <!-- Search & Status Filter -->
        <form action="{{ route('member.clubs.applications') }}" method="GET" class="row g-3 mb-4">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search records..." value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ ($statusFilter ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ ($statusFilter ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            @if(($search ?? '') || ($statusFilter ?? ''))
                <div class="col-md-2">
                    <a href="{{ route('member.clubs.applications') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            @endif
        </form>

        <!-- Applications Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr class="text-muted small text-uppercase">
                        <th class="py-3">Club</th>
                        <th class="py-3">Application Date</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $club)
                        @php
                            $initials = collect(explode(' ', $club->name))
                                ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                ->take(2)
                                ->implode('');
                        @endphp
                        <tr>
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center fw-bold small"
                                         style="width: 38px; height: 38px; background-color: #e6f4f1; color: #0d9488;">
                                        {{ $initials }}
                                    </div>
                                    <span class="fw-semibold">{{ $club->name }}</span>
                                </div>
                            </td>
                            <td class="text-muted">
                                {{ $club->pivot->created_at ? $club->pivot->created_at->format('M d, Y') : '-' }}
                            </td>
                            <td>
                                @if($club->pivot->status === 'pending')
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #fef3c7; color: #b45309;">
                                        &bull; Pending
                                    </span>
                                @elseif($club->pivot->status === 'approved')
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #dcfce7; color: #15803d;">
                                        &bull; Approved
                                    </span>
                                @elseif($club->pivot->status === 'rejected')
                                    <span class="badge rounded-pill px-3 py-2" style="background-color: #fee2e2; color: #b91c1c;">
                                        &bull; Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-2">
                                    <a href="{{ route('member.clubs.show', $club) }}" class="btn btn-sm btn-outline-secondary" title="View Club">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($club->pivot->status === 'pending')
                                        <form action="{{ route('member.clubs.cancel', $club) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Cancel your application to {{ $club->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                No applications found. <a href="{{ route('member.clubs.index') }}" style="color: #0d9488;">Discover Clubs</a> to apply!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection