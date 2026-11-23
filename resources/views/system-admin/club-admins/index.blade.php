@extends('layouts.admin')

@section('title', 'Club Admin Management')

@section('content')
<style>
    .tab-active {
        color: #10b981 !important;
        border-bottom: 3px solid #10b981 !important;
        background: transparent !important;
        font-weight: bold;
    }
    .modal-content {
        border-radius: 16px;
    }
    .form-select:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 0.25rem rgba(13, 148, 136, 0.25);
    }
</style>

<div class="mb-4">
    <h2 class="fw-bold m-0" style="color: #0f172a;">Club Admin Management</h2>
    <p class="text-muted mt-1">Review registrations, assign clubs and manage administrator access.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card p-0 overflow-hidden">
    <div class="bg-white border-bottom px-4 pt-3">
        <ul class="nav nav-tabs border-0" style="margin-bottom: -1px;">
            <!-- 1. All Tab -->
            <li class="nav-item me-3">
                <a class="nav-link border-0 pb-3 {{ $status === 'all' ? 'tab-active' : 'text-muted' }}" 
                   href="{{ route('system_admin.club_admins.index', ['status' => 'all']) }}">
                   All
                </a>
            </li>
            <!-- 2. Pending Tab (with bubble) -->
            <li class="nav-item me-3">
                <a class="nav-link border-0 pb-3 {{ $status === 'pending' ? 'tab-active' : 'text-muted' }}" 
                   href="{{ route('system_admin.club_admins.index', ['status' => 'pending']) }}">
                   Pending <span class="badge rounded-pill ms-1 {{ $status === 'pending' ? 'bg-success' : 'bg-secondary' }}">{{ $counts['pending'] }}</span>
                </a>
            </li>
            <!-- 3. Approved Tab -->
            <li class="nav-item me-3">
                <a class="nav-link border-0 pb-3 {{ $status === 'active' ? 'tab-active' : 'text-muted' }}" 
                   href="{{ route('system_admin.club_admins.index', ['status' => 'active']) }}">
                   Approved
                </a>
            </li>
            <!-- 4. Rejected Tab -->
            <li class="nav-item">
                <a class="nav-link border-0 pb-3 {{ $status === 'rejected' ? 'tab-active' : 'text-muted' }}" 
                   href="{{ route('system_admin.club_admins.index', ['status' => 'rejected']) }}">
                   Rejected
                </a>
            </li>
        </ul>
    </div>

    <div class="table-responsive p-4">
        <table class="table align-middle">
            <thead class="text-muted small" style="border-bottom: 2px solid #f1f5f9;">
                <tr>
                    <th class="fw-semibold pb-3">ADMINISTRATOR</th>
                    <th class="fw-semibold pb-3">STAFF ID</th>
                    <th class="fw-semibold pb-3">REQUESTED CLUB</th>
                    <th class="fw-semibold pb-3">STATUS</th>
                    <th class="fw-semibold pb-3">ASSIGNED CLUB</th>
                    <th class="fw-semibold pb-3 text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 text-success fw-bold rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width: 40px; height: 40px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($admin->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $admin->name }}</div>
                                    <div class="text-muted small">{{ $admin->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">{{ $admin->staff_id ?? 'N/A' }}</td>
                        <td class="text-muted">{{ $admin->requested_club_name ?? 'N/A' }}</td>
                        <td>
                            @if($admin->status === 'pending')
                                <span class="badge bg-warning bg-opacity-10 text-dark rounded-pill px-3 py-2"><i class="bi bi-circle-fill text-warning me-1" style="font-size: 8px;"></i> Pending</span>
                            @elseif($admin->status === 'active')
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Active</span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> Rejected</span>
                            @endif
                        </td>
                        <td class="text-muted">
                            {{ $admin->club ? $admin->club->name : 'Not assigned' }}
                        </td>
                        <td class="text-end">
                            @if($admin->status === 'pending')
                                <button type="button" class="btn btn-sm btn-outline-success me-1" data-bs-toggle="modal" data-bs-target="#assignModal{{ $admin->id }}" title="Approve & Assign">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                                
                                <form action="{{ route('system_admin.club_admins.reject', $admin->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Reject"><i class="bi bi-x-lg"></i></button>
                                </form>

                                <!-- Assign & Approve Modal -->
                                <div class="modal fade text-start" id="assignModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header border-bottom-0 pb-0 mt-2 mx-2">
                                                <div class="text-uppercase" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 1.5px; color: #10b981;">CLUBHUB</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body px-4 pt-1 pb-4">
                                                <h4 class="mb-4" style="color: #0f172a; font-weight: 500;">Approve & assign administrator</h4>
                                                
                                                <div class="d-flex align-items-center mb-4 p-3 rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                                                    <div class="bg-success bg-opacity-10 text-success fw-bold rounded-circle p-2 me-3 text-center" style="width: 40px; height: 40px; line-height: 24px;">
                                                        {{ strtoupper(substr($admin->name, 0, 2)) }}
                                                    </div>
                                                    <div class="lh-sm">
                                                        <div class="fw-bold text-dark mb-1">{{ $admin->name }}</div>
                                                        <div class="text-muted" style="font-size: 0.8rem;">
                                                            {{ $admin->staff_id ?? 'N/A' }} &middot; {{ $admin->email }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <form action="{{ route('system_admin.club_admins.approve', $admin->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Assign to club</label>
                                                        <select name="club_id" class="form-select py-2" required>
                                                            <option value="" disabled selected>Select a club...</option>
                                                            @foreach($clubs as $club)
                                                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="p-3 mb-4 rounded" style="background-color: #fffbeb; border: 1px solid #fef08a; color: #854d0e; font-size: 0.85rem;">
                                                        Approval grants dashboard access only for the selected club.
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2 mt-2">
                                                        <button type="button" class="btn btn-outline-secondary px-4 bg-white" style="border-color: #cbd5e1; border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn px-4 text-white" style="background-color: #0d9488; border-color: #0d9488; border-radius: 8px; font-weight: 500;">Approve & assign</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Edit & Delete Buttons for Active/Rejected -->
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $admin->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('system_admin.club_admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this admin account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>

                                <!-- Edit Modal -->
                                <div class="modal fade text-start" id="editModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header border-bottom-0 pb-0 mt-2 mx-2">
                                                <div class="text-uppercase" style="font-size: 0.65rem; font-weight: 700; letter-spacing: 1.5px; color: #10b981;">CLUBHUB</div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body px-4 pt-1 pb-4">
                                                <h4 class="mb-4" style="color: #0f172a; font-weight: 500;">Edit Club Administrator</h4>
                                                
                                                <form action="{{ route('system_admin.club_admins.update', $admin->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Status</label>
                                                        <select name="status" class="form-select py-2" required>
                                                            <option value="active" {{ $admin->status === 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="pending" {{ $admin->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="rejected" {{ $admin->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label fw-bold" style="font-size: 0.85rem;">Assigned Club</label>
                                                        <select name="club_id" class="form-select py-2">
                                                            <option value="">None (Unassigned)</option>
                                                            @foreach($clubs as $club)
                                                                <option value="{{ $club->id }}" {{ $admin->club_id == $club->id ? 'selected' : '' }}>{{ $club->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="d-flex justify-content-end gap-2 mt-2">
                                                        <button type="button" class="btn btn-outline-secondary px-4 bg-white" data-bs-dismiss="modal" style="border-color: #cbd5e1; border-radius: 8px;">Cancel</button>
                                                        <button type="submit" class="btn px-4 text-white" style="background-color: #10b981; border-color: #10b981; border-radius: 8px; font-weight: 500;">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No club admins found in this category.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection