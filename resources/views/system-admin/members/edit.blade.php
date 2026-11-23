@extends('layouts.admin')

@section('title', 'Edit Member')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold m-0" style="color: #0f172a;">Edit Member: {{ $user->name }}</h2>
    <a href="{{ route('system_admin.members.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back to Members
    </a>
</div>

<div class="card p-4" style="max-width: 700px;">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('system_admin.members.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="col-md-6">
                <label for="student_id" class="form-label fw-semibold">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id', $user->student_id) }}" required>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">University Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-md-6">
                <label for="status" class="form-label fw-semibold">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('system_admin.members.index') }}" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn text-white px-4" style="background-color: #10b981;">Save Changes</button>
        </div>
    </form>
</div>
@endsection