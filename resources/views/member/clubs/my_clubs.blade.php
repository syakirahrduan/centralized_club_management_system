@extends('member.layout')

@section('title', 'My Clubs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="page-kicker">Your Communities</div>
        <h2 class="fw-bold mb-1" style="color: #0f172a;">My Clubs</h2>
        <p class="text-muted mb-0">The communities you're officially part of.</p>
    </div>
    <a href="{{ route('member.clubs.index') }}" class="btn btn-iium">
        <i class="bi bi-search me-1"></i> Discover Clubs
    </a>
</div>

@if($joinedClubs->isEmpty())
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="bi bi-house fs-1 text-muted mb-3 d-block"></i>
            <h5>You haven't joined any clubs yet</h5>
            <p class="text-muted">Explore active IIUM student clubs and apply to become a member.</p>
            <a href="{{ route('member.clubs.index') }}" class="btn btn-iium">Discover Clubs</a>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($joinedClubs as $club)
            @php
                $initials = collect(explode(' ', $club->name))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->take(2)
                    ->implode('');
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 club-card-hover">
                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center fw-bold"
                                 style="width: 48px; height: 48px; background-color: #e6f4f1; color: #0d9488;">
                                {{ $initials }}
                            </div>
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #dcfce7; color: #15803d;">
                                &bull; Active
                            </span>
                        </div>

                        <h5 class="fw-bold mb-2">{{ $club->name }}</h5>
                        <p class="text-muted small flex-grow-1 mb-4">
                            {{ \Illuminate\Support\Str::limit($club->description ?? 'No description available.', 110) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top small mb-3">
                            <span class="text-muted">Joined</span>
                            <span class="fw-semibold">
                                {{ $club->pivot->updated_at ? $club->pivot->updated_at->format('M d, Y') : ($club->pivot->created_at ? $club->pivot->created_at->format('M d, Y') : '-') }}
                            </span>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('member.clubs.show', $club) }}" class="btn btn-iium btn-sm">
                                View Club
                            </a>
                            <form action="{{ route('member.clubs.leave', $club) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to leave {{ $club->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                    Leave club
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection