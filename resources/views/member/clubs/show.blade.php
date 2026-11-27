@extends('member.layout')

@section('title', $club->name . ' - ClubHub IIUM')

@section('content')
<div class="mb-3">
    <a href="{{ route('member.clubs.index') }}" class="text-decoration-none fw-semibold" style="color: #185B4B;">
        <i class="bi bi-arrow-left me-1"></i>Back to Discover Clubs
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
            <div>
                <span class="badge badge-gold mb-2">{{ $club->category ?? 'General' }}</span>
                <h2 class="fw-bold mb-1" style="color: #111827;">{{ $club->name }}</h2>
                @if($club->email)
                    <p class="text-muted small mb-0">
                        <i class="bi bi-envelope me-1"></i>{{ $club->email }}
                    </p>
                @endif
            </div>

            <div>
                @php
                    $status = $membership ? $membership->pivot->status : null;
                @endphp

                @if($status === 'approved')
                    <button class="btn btn-success" disabled>
                        <i class="bi bi-check-circle me-1"></i>Approved Member
                    </button>
                @elseif($status === 'pending')
                    <button class="btn btn-warning text-dark" disabled>
                        <i class="bi bi-hourglass-split me-1"></i>Application Pending
                    </button>
                @elseif($status === 'rejected')
                    <button class="btn btn-danger" disabled>
                        <i class="bi bi-x-circle me-1"></i>Application Rejected
                    </button>
                @else
                    <form action="{{ route('member.clubs.apply', $club) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-iium btn-lg">
                            <i class="bi bi-plus-circle me-1"></i>Apply to Join
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <hr>

        <div class="row mt-4">
            <div class="col-md-8">
                <h5 class="fw-bold mb-3">About This Club</h5>
                <p class="text-secondary" style="white-space: pre-line;">{{ $club->description ?? 'No description provided yet.' }}</p>
            </div>
            <div class="col-md-4">
                <div class="card bg-light border-0">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Club Information</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <strong>Status:</strong>
                                <span class="text-capitalize">{{ $club->status ?? 'Active' }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Approved Members:</strong>
                                {{ $memberCount }}
                            </li>
                            @if($club->admin)
                                <li class="mb-0">
                                    <strong>Club Admin:</strong> {{ $club->admin->name }}
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection