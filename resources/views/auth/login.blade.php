<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in - ClubHub</title>
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
        /* Left Branding Panel - Soft luminous top corner fading into rich IIUM emerald-teal */
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
            max-width: 460px;
            margin: auto;
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
            margin-bottom: 1.75rem;
        }

        /* Role Segmented Control */
        .role-switcher {
            background-color: #f1f5f9;
            padding: 4px;
            border-radius: 10px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            margin-bottom: 1.5rem;
        }
        .role-btn {
            border: none;
            background: transparent;
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .role-btn.active {
            background-color: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.45rem;
        }
        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            padding: 0.7rem 0.95rem;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
        }
        .btn-signin {
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
        .btn-signin:hover {
            background-color: #0f766e;
            color: #ffffff;
        }
        .create-link {
            color: #0d9488;
            font-weight: 600;
            text-decoration: none;
        }
        .create-link:hover {
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
            <a href="{{ url('/') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Back to home
            </a>
        </div>

        <div class="form-wrapper">
            <div class="welcome-tag">WELCOME BACK</div>
            <h2 class="form-title">Sign in to your account</h2>
            <p class="form-subtitle">Choose your role and enter your details.</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 small">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success py-2 small">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'member') }}">

                <label class="form-label">I am signing in as</label>
                <div class="role-switcher">
                    <button type="button" class="role-btn {{ old('role', 'member') === 'member' ? 'active' : '' }}" data-role="member" data-placeholder="e.g name@iium.edu.my">Member</button>
                    <button type="button" class="role-btn {{ old('role') === 'club_admin' ? 'active' : '' }}" data-role="club_admin" data-placeholder="e.g name@iium.edu.my">Club Admin</button>
                    <button type="button" class="role-btn {{ old('role') === 'system_admin' ? 'active' : '' }}" data-role="system_admin" data-placeholder="e.g admin@iium.edu.my">System Admin</button>
                </div>

                <div class="mb-3">
                    <label for="login_id" class="form-label">Email or User ID</label>
                    <input type="text" class="form-control" id="login_id" name="login_id" value="{{ old('login_id') }}" placeholder="e.g name@iium.edu.my" required>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label mb-1">Password</label>
                        <a href="#" class="small text-decoration-none" style="color: #0d9488; font-weight: 500;">Forgot password?</a>
                    </div>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn-signin">
                    Sign in <i class="bi bi-arrow-right ms-1"></i>
                </button>
            </form>

            <div class="text-center mt-4 small text-muted">
                New to ClubHub? <a href="{{ route('register') }}" class="create-link">Create account</a>
            </div>
        </div>
    </div>
</div>

<script>
    const roleButtons = document.querySelectorAll('.role-btn');
    const roleInput = document.getElementById('selectedRole');
    const loginInput = document.getElementById('login_id');

    roleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            roleButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            roleInput.value = btn.dataset.role;
            loginInput.placeholder = btn.dataset.placeholder;
        });
    });
</script>
</body>
</html>