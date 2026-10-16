<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hospital HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; min-height: 100vh; }
        .login-wrapper { min-height: 100vh; display: flex; }
        .login-left {
            flex: 1; background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 40px; color: #fff;
        }
        .login-right {
            width: 460px; background: #fff;
            display: flex; flex-direction: column; justify-content: center;
            padding: 50px 40px;
        }
        .login-logo {
            width: 80px; height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px; display: flex; align-items: center; justify-content: center;
            font-size: 36px; margin-bottom: 20px;
        }
        .feature-item { display: flex; align-items: center; gap: 12px; margin: 10px 0; font-size: 14px; opacity: 0.9; }
        .feature-icon { width: 36px; height: 36px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .form-control { border-radius: 8px; padding: 10px 14px; border-color: #dee2e6; font-size: 14px; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,0.1); }
        .btn-login { padding: 11px; border-radius: 8px; font-weight: 600; font-size: 15px; }
        .input-group-text { background: #f8f9fa; border-color: #dee2e6; color: #6c757d; border-radius: 0 8px 8px 0; }
        .input-group .form-control { border-right: none; border-radius: 8px 0 0 8px; }
        .demo-credentials { background: #f8f9fa; border-radius: 10px; padding: 14px; font-size: 12.5px; }
        .demo-row { display: flex; justify-content: space-between; padding: 3px 0; }
        @media(max-width:768px) { .login-left { display: none; } .login-right { width: 100%; padding: 30px 24px; } }
    </style>
</head>
<body>
<div class="login-wrapper">
    {{-- Left Panel --}}
    <div class="login-left">
        <div class="login-logo"><i class="fas fa-hospital-alt"></i></div>
        <h2 class="fw-bold mb-2 text-center">Hospital Management System</h2>
        <p class="text-center mb-4" style="opacity:.8;font-size:14px">Streamline your hospital operations with our comprehensive management platform</p>

        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-users-cog"></i></div>
            <span>Multi-role access: Admin, Doctor, Nurse, Receptionist & more</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
            <span>Appointment scheduling & patient management</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-flask"></i></div>
            <span>Laboratory & prescription management</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-file-invoice-dollar"></i></div>
            <span>Billing, invoicing & financial reports</span>
        </div>
        <div class="feature-item">
            <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
            <span>Real-time dashboards & analytics</span>
        </div>
    </div>

    {{-- Right Panel --}}
    <div class="login-right">
        <h3 class="fw-bold mb-1">Welcome Back</h3>
        <p class="text-muted mb-4" style="font-size:14px">Sign in to access your dashboard</p>

        @if(session('success'))
        <div class="alert alert-success alert-sm mb-3" style="font-size:13px;padding:10px 14px">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-500" style="font-size:13px">Email Address</label>
                <div class="input-group">
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                </div>
                @error('email')
                <div class="invalid-feedback d-block" style="font-size:12px">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label class="form-label fw-500" style="font-size:13px">Password</label>
                    <a href="{{ route('password.request') }}" style="font-size:12px">Forgot password?</a>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="passwordInput" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Enter your password" required>
                    <span class="input-group-text" style="cursor:pointer" onclick="togglePwd()">
                        <i class="fas fa-eye" id="pwdIcon"></i>
                    </span>
                </div>
                @error('password')
                <div class="invalid-feedback d-block" style="font-size:12px">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 d-flex align-items-center gap-2">
                <input class="form-check-input mt-0" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:13px">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>Sign In
            </button>
        </form>

        {{-- Demo Credentials --}}
        <div class="demo-credentials mt-4">
            <div class="fw-600 mb-2" style="font-size:12px;color:#495057"><i class="fas fa-info-circle me-1"></i>Demo Login Credentials (password: <code>password</code>)</div>
            <div class="demo-row"><span class="text-muted">Administrator:</span><span>admin@hospital.com</span></div>
            <div class="demo-row"><span class="text-muted">Doctor:</span><span>james.wilson@hospital.com</span></div>
            <div class="demo-row"><span class="text-muted">Nurse:</span><span>nancy.clark@hospital.com</span></div>
            <div class="demo-row"><span class="text-muted">Receptionist:</span><span>receptionist@hospital.com</span></div>
            <div class="demo-row"><span class="text-muted">Laboratory:</span><span>lab@hospital.com</span></div>
            <div class="demo-row"><span class="text-muted">Pharmacist:</span><span>pharmacist@hospital.com</span></div>
            <div class="demo-row"><span class="text-muted">Patient:</span><span>patient@hospital.com</span></div>
        </div>
    </div>
</div>

<script>
function togglePwd() {
    const inp = document.getElementById('passwordInput');
    const icon = document.getElementById('pwdIcon');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
</body>
</html>
