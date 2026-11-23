@extends('layouts.admin')

@section('title', 'Club Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-1">
    <h2 class="fw-bold m-0" style="color: #0f172a;">Club Management</h2>
    <button type="button" class="btn text-white px-4" style="background-color: #10b981;" data-bs-toggle="modal" data-bs-target="#createClubModal">
        + Add club
    </button>
</div>
<p class="text-muted mb-4">Create, update and oversee every university club.</p>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card p-4">
    <!-- Search and Filter Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Search records...">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select text-muted">
                <option selected>All categories</option>
                <option value="technology">Technology</option>
                <option value="community">Community</option>
                <option value="academic">Academic</option>
            </select>
        </div>
    </div>

    <!-- Clubs Table -->
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="text-muted small" style="border-bottom: 2px solid #f1f5f9;">
                <tr>
                    <th class="fw-semibold pb-3">CLUB</th>
                    <th class="fw-semibold pb-3">CATEGORY</th>
                    <th class="fw-semibold pb-3">MEMBERS</th>
                    <th class="fw-semibold pb-3">ASSIGNED ADMIN</th>
                    <th class="fw-semibold pb-3">STATUS</th>
                    <th class="fw-semibold pb-3 text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clubs as $club)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 text-success fw-bold rounded d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($club->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $club->name }}</div>
                                    <div class="text-muted small">{{ $club->email ?? 'No email set' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $club->category }}</td>
                        <td class="text-muted">0</td>
                        <td>
                            @if($club->admin)
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $club->admin->name }}</div>
                                <div class="text-muted small">{{ $club->admin->email }}</div>
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                        <td>
                            @if($club->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Active</span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editClubModal{{ $club->id }}" title="Edit Club">
                                <i class="bi bi-pencil"></i>
                            </button>
                            
                            <form action="{{ route('system_admin.clubs.destroy', $club->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this club?')" title="Delete Club">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                            <!-- Edit Club Modal -->
                            <div class="modal fade text-start" id="editClubModal{{ $club->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                                        <div class="modal-header border-bottom-0 pb-0 mt-2 mx-2">
                                            <div class="text-uppercase" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 1.5px; color: #10b981;">CLUBHUB</div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-5 pt-2 pb-4">
                                            <h4 class="mb-4" style="color: #0f172a; font-weight: 500;">Edit club</h4>
                                            
                                            <form action="{{ route('system_admin.clubs.update', $club->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Club name</label>
                                                    <input type="text" name="name" class="form-control py-2" value="{{ $club->name }}" required>
                                                </div>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Category</label>
                                                        <select name="category" class="form-select py-2" required>
                                                            <option value="Technology" {{ $club->category == 'Technology' ? 'selected' : '' }}>Technology</option>
                                                            <option value="Community" {{ $club->category == 'Community' ? 'selected' : '' }}>Community</option>
                                                            <option value="Academic" {{ $club->category == 'Academic' ? 'selected' : '' }}>Academic</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Club email</label>
                                                        <input type="email" name="email" class="form-control py-2" value="{{ $club->email }}" placeholder="Optional (to be filled by club admin)">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Status</label>
                                                    <select name="status" class="form-select py-2" required>
                                                        <option value="active" {{ $club->status == 'active' ? 'selected' : '' }}>Active</option>
                                                        <option value="inactive" {{ $club->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Description</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ $club->description }}</textarea>
                                                </div>

                                                <!-- Read-Only Assigned Admin Field -->
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold" style="font-size: 0.85rem;">Assigned admin</label>
                                                    <input type="text" class="form-control bg-light text-muted py-2" 
                                                           value="{{ $club->admin ? $club->admin->name : 'Not assigned' }}" 
                                                           readonly>
                                                    <div class="form-text mt-1 text-muted" style="font-size: 0.75rem;">
                                                        <i class="bi bi-info-circle me-1"></i> To reassign this club, manage the user in the Club Admin Management tab.
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end gap-3 mt-2">
                                                    <button type="button" class="btn btn-outline-secondary px-4 bg-white" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                                                    <button type="submit" class="btn px-4 text-white" style="background-color: #10b981; border-radius: 8px; font-weight: 500;">Save club</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No clubs found. Click "+ Add club" to create one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Club Modal -->
<div class="modal fade text-start" id="createClubModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-bottom-0 pb-0 mt-2 mx-2">
                <div class="text-uppercase" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 1.5px; color: #10b981;">CLUBHUB</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pt-2 pb-4">
                <h4 class="mb-4" style="color: #0f172a; font-weight: 500;">Create a new club</h4>
                
                <form action="{{ route('system_admin.clubs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="active">
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Club name</label>
                        <input type="text" name="name" class="form-control py-2" required>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Category</label>
                            <select name="category" class="form-select py-2" required>
                                <option value="Technology">Technology</option>
                                <option value="Community">Community</option>
                                <option value="Academic">Academic</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="font-size: 0.85rem;">Club email</label>
                            <input type="email" name="email" class="form-control py-2" placeholder="Optional (to be filled by club admin)">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-2">
                        <button type="button" class="btn btn-outline-secondary px-4 bg-white" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" class="btn px-4 text-white" style="background-color: #10b981; border-radius: 8px; font-weight: 500;">Save club</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection