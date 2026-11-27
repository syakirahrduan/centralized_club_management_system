@extends('member.layout')

@section('title', 'Discover Clubs - ClubHub IIUM')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
    <div>
        <h2 class="fw-bold mb-1" style="color: var(--iium-turquoise);">Discover IIUM Clubs</h2>
        <p class="text-muted mb-0">Explore student societies, sports clubs, and special interest groups across campus.</p>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('member.clubs.index') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search by club name or keyword..." value="{{ $search ?? '' }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-iium">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Club Cards Grid -->
@if($clubs->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="bi bi-search fs-1 text-muted mb-3 d-block"></i>
            <h5>No clubs found</h5>
            <p class="text-muted">Try adjusting your search keywords or clearing the category filter.</p>
            <a href="{{ route('member.clubs.index') }}" class="btn btn-outline-secondary btn-sm">Reset Filters</a>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($clubs as $club)
            @php
                $membershipStatus = $userClubStatuses[$club->id] ?? null;
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 club-card-hover">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-light text-dark border">{{ $club->category ?? 'General' }}</span>
                            @if($membershipStatus === 'approved')
                                <span class="badge bg-success">Joined</span>
                            @elseif($membershipStatus === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($membershipStatus === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </div>

                        <h5 class="card-title fw-bold mt-1">{{ $club->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ \Illuminate\Support\Str::limit($club->description ?? 'No description available.', 110) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-3">
                            <span class="small text-muted">
                                <i class="bi bi-people me-1"></i>{{ $club->approved_members_count ?? 0 }} Members
                            </span>
                            <a href="{{ route('member.clubs.show', $club) }}" class="btn btn-sm btn-iium">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $clubs->withQueryString()->links() }}
    </div>
@endif
@endsection