<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClubHub - @yield('title', 'Club Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #0f172a;
        }

        /* Sidebar Matching IIUM Emerald-Teal & Logo Spotlight */
        .sidebar {
            height: 100vh;
            background:
                radial-gradient(circle at 22% 7%, rgba(88, 149, 128, 0.96) 0%, rgba(98, 156, 138, 0.72) 15%, rgba(15, 76, 69, 0) 36%),
                linear-gradient(175deg, #13544d 0%, #0b3833 45%, #06211e 100%);
            padding-top: 22px;
            color: #ffffff;
            position: fixed;
            width: 265px;
            box-shadow: 4px 0 20px rgba(6, 33, 30, 0.08);
            z-index: 1030;
        }

        .sidebar-eyebrow {
            color: #dbb03c;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            padding: 0 22px;
            margin-bottom: 10px;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 11px 20px;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.93rem;
            margin: 3px 12px;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .sidebar a i {
            color: #94a3b8;
            font-size: 1.05rem;
            transition: color 0.2s ease;
        }

        .sidebar a:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .sidebar a:hover i {
            color: #34d399;
        }

        .sidebar a.active {
            color: #ffffff;
            background-color: rgba(13, 148, 136, 0.28);
            border-left: 3px solid #dbb03c;
            font-weight: 600;
        }

        .sidebar a.active i {
            color: #dbb03c;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        .admin-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            background-color: rgba(219, 176, 60, 0.18);
            border: 1.5px solid #dbb03c;
            color: #dbb03c;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .btn-logout {
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #e2e8f0;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 0.86rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.18);
            border-color: rgba(239, 68, 68, 0.45);
            color: #ffffff;
        }

        /* Main Content Area & Unified Teal Accents */
        .main-content {
            margin-left: 265px;
            padding: 32px;
            min-height: 100vh;
        }

        .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
        }

        .btn-primary, .btn-teal {
            background-color: #0d9488;
            border-color: #0d9488;
            color: #ffffff;
            border-radius: 9px;
            font-weight: 600;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-teal:hover {
            background-color: #0f766e;
            border-color: #0f766e;
            color: #ffffff;
        }

        .avatar-initials {
            width: 40px;
            height: 40px;
            background-color: #e6f7f2;
            color: #0f766e;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .avatar-initials-lg {
            width: 48px;
            height: 48px;
            background-color: #e6f7f2;
            color: #0f766e;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .action-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .action-icon-btn:hover {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column">
    <div class="px-4 mb-3">
        <img src="{{ asset('images/clubhublogo.png') }}" alt="ClubHub Logo" style="height: 60px; width: auto; max-width: 200px; object-fit: contain;">
    </div>

    <div class="sidebar-eyebrow">Club Administrator</div>

    <a href="{{ route('club_admin.applications.index') }}" class="justify-content-between {{ request()->routeIs('club_admin.applications.*') ? 'active' : '' }}">
        <span class="d-flex align-items-center"><i class="bi bi-file-earmark-text me-2"></i> Membership Applications</span>
        @if(isset($pendingCount) && $pendingCount > 0)
            <span class="badge rounded-pill ms-2" style="background-color: #dbb03c; color: #06211e; font-weight: 700;">{{ $pendingCount }}</span>
        @endif
    </a>

    <a href="{{ route('club_admin.members.index') }}" class="{{ request()->routeIs('club_admin.members.*') ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i> Members
    </a>

    <!-- User Profile & Logout at bottom -->
    <div class="mt-auto p-4 sidebar-footer">
        <div class="d-flex align-items-center mb-3">
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="admin-avatar me-2">
            @else
                <div class="admin-avatar me-2">
                    {{ strtoupper(substr(auth()->user()->name ?? 'CA', 0, 2)) }}
                </div>
            @endif
            <div class="overflow-hidden">
                <div class="fw-bold fs-6 text-truncate">{{ auth()->user()->name }}</div>
                <div style="font-size: 0.72rem; color: #dbb03c;">{{ auth()->user()->staff_id ?? 'Club Admin' }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Log out
            </button>
        </form>
    </div>
</div>

<!-- Main Content Area -->
<div class="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>