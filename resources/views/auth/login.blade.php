<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — WH CHINA by CECE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .blob-1 { position: fixed; top: -10%; left: -10%; width: 50vw; height: 50vw; border-radius: 50%; background: radial-gradient(circle, rgba(95, 168, 211, 0.4) 0%, rgba(255,255,255,0) 70%); z-index: 0; filter: blur(60px); pointer-events:none; }
        .blob-2 { position: fixed; bottom: -20%; right: -10%; width: 60vw; height: 60vw; border-radius: 50%; background: radial-gradient(circle, rgba(224, 143, 178, 0.3) 0%, rgba(255,255,255,0) 70%); z-index: 0; filter: blur(80px); pointer-events:none; }
        .auth-wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; position: relative; z-index: 10; }
        .auth-box {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 24px; padding: 2.5rem; width: 100%; max-width: 420px;
            box-shadow: 0 20px 40px rgba(95, 168, 211, 0.15);
        }
        .auth-logo { font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.3rem; color:var(--ink); display:flex; align-items:center; gap:0.5rem; margin-bottom:1.75rem; justify-content: center; }
        .divider { height:1px; background:rgba(208, 215, 224, 0.6); margin:1.5rem 0; }
        .form-input { background: rgba(255,255,255,0.8); border: 1px solid rgba(208, 215, 224, 0.8); transition: all 0.3s ease; }
        .form-input:focus { background: #fff; border-color: var(--blue-deep); box-shadow: 0 0 0 4px rgba(95, 168, 211, 0.15); }
    </style>
</head>
<body>
<div class="blob-1"></div>
<div class="blob-2"></div>
<div class="auth-wrap">
    <div class="auth-box">
        <div class="auth-logo">
            <span style="width:10px;height:10px;background:var(--blue-deep);border-radius:50%;display:inline-block;"></span>
            WH CHINA by CECE
        </div>

        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size:1.5rem;font-weight:800;font-family:'Space Grotesk',sans-serif;margin-bottom:0.375rem;color:var(--ink);">Selamat Datang</h1>
            <p style="font-size:0.875rem;color:var(--ink-soft);">Masuk ke dashboard akun Anda.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success" style="margin-bottom:1rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@email.com">
            </div>
            <div class="form-group">
                <label class="form-label" for="password" style="display:flex;justify-content:space-between;">
                    Password
                    <a href="{{ route('otp.email.form') }}" style="font-weight:400; color:var(--blue-deep); text-decoration:none; font-size:0.8125rem;">Lupa password?</a>
                </label>
                <input id="password" type="password" name="password" class="form-input" required autocomplete="current-password" placeholder="••••••••">
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem;">
                <input type="checkbox" name="remember" id="remember" style="width:15px;height:15px;accent-color:var(--blue-deep);">
                <label for="remember" style="font-size:0.875rem; color:var(--ink-soft);">Ingat saya</label>
            </div>
            <button type="submit" id="login-submit" class="btn btn-primary" style="width:100%; justify-content:center;">Masuk</button>
        </form>

        <div class="divider"></div>
        <p style="text-align:center; font-size:0.875rem; color:var(--ink-soft);">
            Belum punya akun? <a href="{{ route('register') }}" style="color:var(--blue-deep); font-weight:600; text-decoration:none;">Daftar sekarang</a>
        </p>
    </div>
</div>
</body>
</html>
