@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="mb-4">
    <div class="fw-bold text-uppercase mb-1" style="color: #0d9488; font-size: 0.78rem; letter-spacing: 1px;">ACCOUNT</div>
    <h2 class="fw-bold m-0" style="color: #0f172a; font-size: 2rem;">Settings</h2>
    <p class="text-muted mt-1">Manage your profile, security and preferences.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4 align-items-start">
    <!-- Left Card: Profile Information -->
    <div class="col-lg-8">
        <div class="card p-0 overflow-hidden" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <form action="{{ route('system_admin.settings.profile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Top Profile Header -->
                <div class="p-4 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid #f1f5f9;">
                    <div class="d-flex align-items-center">
                        @if($user->avatar_path)
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="Avatar" class="rounded-circle me-3 object-fit-cover" style="width: 56px; height: 56px;">
                        @else
                            <div class="rounded-circle fw-bold me-3 d-flex align-items-center justify-content-center" 
                                 style="width: 56px; height: 56px; background-color: #d1fae5; color: #0f766e; font-size: 1.1rem;">
                                {{ strtoupper(substr($user->name, 0, 1) . (str_contains($user->name, ' ') ? substr(strrchr($user->name, ' '), 1, 1) : substr($user->name, 1, 1))) }}
                            </div>
                        @endif
                        <div>
                            <h5 class="fw-bold m-0" style="color: #0f172a;">{{ $user->name }}</h5>
                            <div class="text-muted small">
                                System Administrator &middot; {{ $user->staff_id ?? 'ADM-0001' }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <input type="file" name="avatar" id="avatarInput" class="d-none" accept="image/*" onchange="this.form.submit()">
                        <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold px-3 py-2" 
                                style="border-color: #cbd5e1; color: #0f172a; border-radius: 8px;"
                                onclick="document.getElementById('avatarInput').click()">
                            Update photo
                        </button>
                    </div>
                </div>

                <!-- Profile Form Inputs -->
                <div class="p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold small" style="color: #0f172a;">Full name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" style="border-radius: 8px; padding: 0.65rem 0.9rem;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="staff_id" class="form-label fw-semibold small" style="color: #0f172a;">Staff ID</label>
                            <input type="text" class="form-control" id="staff_id" name="staff_id" value="{{ old('staff_id', $user->staff_id ?? 'ADM-0001') }}" style="border-radius: 8px; padding: 0.65rem 0.9rem;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label fw-semibold small" style="color: #0f172a;">University email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" style="border-radius: 8px; padding: 0.65rem 0.9rem;" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn text-white fw-semibold px-4 py-2" style="background-color: #0d9488; border-radius: 8px;">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Card: Security -->
    <div class="col-lg-4">
        <div class="card p-4" style="border: 1px solid #e2e8f0; border-radius: 14px;">
            <h5 class="fw-bold mb-1" style="color: #0f172a;">Security</h5>
            <p class="text-muted small mb-4">Keep your account protected with a strong password.</p>

            <form action="{{ route('system_admin.settings.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label fw-semibold small" style="color: #0f172a;">Current password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="••••••••" style="border-radius: 8px; padding: 0.65rem 0.9rem;" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold small" style="color: #0f172a;">New password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="At least 8 characters" style="border-radius: 8px; padding: 0.65rem 0.9rem;" required>
                </div>

                <button type="submit" class="btn btn-outline-secondary w-100 fw-semibold py-2" style="border-color: #cbd5e1; color: #0f172a; border-radius: 8px;">
                    Change password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection