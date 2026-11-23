@extends('layouts.admin')

@section('title', 'Member Profile')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold m-0" style="color: #0f172a;">Member Profile</h2>
    <a href="{{ route('system_admin.members.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Members
    </a>
</div>

<div class="card p-4" style="max-width: 700px;">
    <div class="d-flex align-items-center mb-4">
        <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center" 
             style="width: 56px; height: 56px; background-color: #e6f7f2; color: #0f766e; font-size: 1.2rem;">
            {{ strtoupper(substr($user->name, 0, 2)) }}
        </div>
        <div>
            <h4 class="fw-bold m-0">{{ $user->name }}</h4>
            <div class="text-muted">{{ $user->email }}</div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="text-muted small">Student ID</div>
            <div class="fw-semibold">{{ $user->student_id ?? 'N/A' }}</div>
        </div>
        <div class="col-md-6">
            <div class="text-muted small">Account Status</div>
            <div class="fw-semibold text-capitalize">{{ $user->status }}</div>
        </div>
    </div>

    <h6 class="fw-bold mb-3">Club Memberships</h6>
    @if($user->clubs->isEmpty())
        <p class="text-muted small">This member has not joined any clubs yet.</p>
    @else
        <ul class="list-group list-group-flush">
            @foreach($user->clubs as $club)
                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                    <span>{{ $club->name }}</span>
                    <span class="badge bg-light text-dark text-capitalize">{{ $club->pivot->status }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection