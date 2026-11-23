<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - ClubHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #ffffff;
        }
        .split-container {
            min-height: 100vh;
            display: flex;
        }

        /* Left Branding Panel - Matching Login Page IIUM Emerald-Teal & Logo Spotlight */
        .left-panel {
            width: 45%;
            background: 
                radial-gradient(circle at 16% 12%, rgba(240, 249, 246, 0.96) 0%, rgba(212, 236, 229, 0.75) 14%, rgba(15, 76, 69, 0) 34%),
                linear-gradient(160deg, #13544d 0%, #1f5f58 45%, #11443e 100%);
            color: #ffffff;
            padding: 3rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 85% 85%, rgba(219, 176, 60, 0.08), transparent 45%);
            pointer-events: none;
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1;
        }
        .hero-copy {
            margin-top: auto;
            margin-bottom: auto;
            max-width: 440px;
            z-index: 1;
        }
        .hero-eyebrow {
            color: #dbb03c;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .hero-title {
            color: #ffffff;
            font-size: 3.25rem;
            font-weight: 700;
            line-height: 1.12;
            margin-bottom: 1.25rem;
        }
        .hero-title span {
            color: #34d399;
        }
        .hero-subtitle {
            color: #cbd5e1;
            font-size: 1.05rem;
            line-height: 1.6;
        }

        /* Right Form Panel */
        .right-panel {
            width: 55%;
            padding: 2.5rem 4rem;
            display: flex;
            flex-direction: column;
        }
        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .back-link:hover {
            color: #0f172a;
        }
        .form-wrapper {
            width: 100%;
            max-width: 480px;
            margin: auto;
            padding: 1.5rem 0;
        }
        .welcome-tag {
            color: #0d9488;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }
        .form-title {
            color: #0f172a;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.35rem;
        }
        .form-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        /* Role Selection Cards */
        .role-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 1.5rem;
        }
        .role-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.9rem 1rem;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            background-color: #ffffff;
            transition: all 0.2s ease;
            text-align: left;
        }
        .role-card:hover {
            border-color: #99f6e4;
        }
        .role-card.active {
            border-color: #0d9488;
            background-color: #f0fdfa;
        }
        .role-card-icon {
            font-size: 1.25rem;
            color: #64748b;
            flex-shrink: 0;
        }
        .role-card.active .role-card-icon {
            color: #0d9488;
        }
        .role-card-title {
            font-weight: 700;
            font-size: 0.92rem;
            color: #0f172a;
            line-height: 1.2;
        }
        .role-card-desc {
            font-size: 0.78rem;
            color: #64748b;
            margin-top: 2px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }
        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            padding: 0.68rem 0.9rem;
            font-size: 0.93rem;
        }
        .form-control:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
        }
        .btn-create {
            background-color: #0d9488;
            color: #ffffff;
            border: none;
            border-radius: 9px;
            padding: 0.78rem;
            font-weight: 600;
            font-size: 0.98rem;
            width: 100%;
            margin-top: 0.5rem;
            transition: background-color 0.2s ease;
        }
        .btn-create:hover {
            background-color: #0f766e;
            color: #ffffff;
        }
        .signin-link {
            color: #0d9488;
            font-weight: 600;
            text-decoration: none;
        }
        .signin-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 991px) {
            .left-panel { display: none; }
            .right-panel { width: 100%; padding: 2rem; }
        }
    </style>
</head>
<body>
<div class="split-container">
    <!-- Left Panel -->
    <div class="left-panel">
        <div class="brand-logo">
            <img src="{{ asset('images/clubhublogo.png') }}" alt="ClubHub Logo" style="height: 100px; width: auto; max-width: 260px; object-fit: contain;">
        </div>

        <div class="hero-copy">
            <div class="hero-eyebrow">INTERNATIONAL ISLAMIC UNIVERSITY MALAYSIA</div>
            <h1 class="hero-title">Find where<br>you <span>belong.</span></h1>
            <p class="hero-subtitle">One account connects you to every opportunity, community and experience on campus.</p>
        </div>

        <div></div>
    </div>

    <!-- Right Panel -->
    <div class="right-panel">
        <div>
            <a href="{{ route('login') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to home
            </a>
        </div>

        <div class="form-wrapper">
            <div class="welcome-tag">JOIN CLUBHUB</div>
            <h2 class="form-title">Create your account</h2>
            <p class="form-subtitle">Tell us how you'll be using ClubHub.</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'member') }}">

                <!-- Role Selection Cards -->
                <div class="role-cards">
                    <button type="button" class="role-card {{ old('role', 'member') === 'member' ? 'active' : '' }}" data-role="member">
                        <i class="bi bi-people role-card-icon"></i>
                        <div>
                            <div class="role-card-title">Member</div>
                            <div class="role-card-desc">Join and explore clubs</div>
                        </div>
                    </button>

                    <button type="button" class="role-card {{ old('role') === 'club_admin' ? 'active' : '' }}" data-role="club_admin">
                        <i class="bi bi-person-badge role-card-icon"></i>
                        <div>
                            <div class="role-card-title">Club Admin</div>
                            <div class="role-card-desc">Manage a club</div>
                        </div>
                    </button>
                </div>

                <!-- Row 1: Full Name & Student/Staff ID -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
                    </div>

                    <!-- Member Student ID Field -->
                    <div class="col-md-6 {{ old('role') === 'club_admin' ? 'd-none' : '' }}" id="studentIdWrapper">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" value="{{ old('student_id') }}" placeholder="e.g. 2519000">
                    </div>

                    <!-- Club Admin Staff ID Field -->
                    <div class="col-md-6 {{ old('role') === 'club_admin' ? '' : 'd-none' }}" id="staffIdWrapper">
                        <label for="staff_id" class="form-label">Staff ID</label>
                        <input type="text" class="form-control" id="staff_id" name="staff_id" value="{{ old('staff_id') }}" placeholder="e.g. 1001">
                    </div>
                </div>

                <!-- Requested Club Name (Only shown for Club Admin) -->
                <div class="mb-3 {{ old('role') === 'club_admin' ? '' : 'd-none' }}" id="requestedClubWrapper">
                    <label for="requested_club_name" class="form-label">Club / Society Name</label>
                    <input type="text" class="form-control" id="requested_club_name" name="requested_club_name" value="{{ old('requested_club_name') }}" placeholder="e.g. IIUM Roboteam">
                </div>

                <!-- Row 2: University Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">University Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="e.g. name@iium.edu.my" required>
                </div>

                <!-- Row 3: Password & Confirm Password -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Minimum 6 characters" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Repeat password" required>
                    </div>
                </div>

                <button type="submit" class="btn-create">
                    Create account <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>

            <div class="text-center mt-4 small text-muted">
                Already have an account? <a href="{{ route('login') }}" class="signin-link">Sign in</a>
            </div>
        </div>
    </div>
</div>

<script>
    const roleCards = document.querySelectorAll('.role-card');
    const roleInput = document.getElementById('selectedRole');
    const studentIdWrapper = document.getElementById('studentIdWrapper');
    const staffIdWrapper = document.getElementById('staffIdWrapper');
    const requestedClubWrapper = document.getElementById('requestedClubWrapper');

    // Inputs to toggle required validation
    const nameInput = document.getElementById('name');
    const staffIdInput = document.getElementById('staff_id');
    const clubNameInput = document.getElementById('requested_club_name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');

    function updateFormForRole(role) {
        roleInput.value = role;

        if (role === 'club_admin') {
            studentIdWrapper.classList.add('d-none');
            staffIdWrapper.classList.remove('d-none');
            requestedClubWrapper.classList.remove('d-none');

            // Enforce required validation for Club Admin
            nameInput.required = true;
            staffIdInput.required = true;
            clubNameInput.required = true;
            emailInput.required = true;
            passwordInput.required = true;
            passwordConfirmInput.required = true;
        } else {
            studentIdWrapper.classList.remove('d-none');
            staffIdWrapper.classList.add('d-none');
            requestedClubWrapper.classList.add('d-none');

            // Allow dummy data without validation blocks for Member
            nameInput.required = false;
            staffIdInput.required = false;
            clubNameInput.required = false;
            emailInput.required = false;
            passwordInput.required = false;
            passwordConfirmInput.required = false;
        }
    }

    roleCards.forEach(card => {
        card.addEventListener('click', () => {
            roleCards.forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            updateFormForRole(card.dataset.role);
        });
    });

    // Initialize state on page load
    updateFormForRole(roleInput.value);
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>