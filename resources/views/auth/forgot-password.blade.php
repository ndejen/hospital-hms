<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Hospital HMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { max-width: 420px; width: 100%; border: none; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="card p-4">
    <div class="text-center mb-4">
        <div style="width:60px;height:60px;background:linear-gradient(135deg,#0d6efd,#6f42c1);border-radius:16px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:24px;margin-bottom:12px">
            <i class="fas fa-lock"></i>
        </div>
        <h5 class="fw-bold">Forgot Password?</h5>
        <p class="text-muted" style="font-size:13px">Enter your email and we'll send you a reset link</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success" style="font-size:13px">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label" style="font-size:13px">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="your@email.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-paper-plane me-2"></i>Send Reset Link
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" style="font-size:13px"><i class="fas fa-arrow-left me-1"></i>Back to Login</a>
    </div>
</div>
</body>
</html>
