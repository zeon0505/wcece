<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — WH CHINA by CECE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
        .blob-1 { position:fixed; top:-10%; left:-10%; width:50vw; height:50vw; border-radius:50%; background:radial-gradient(circle, rgba(95,168,211,0.4) 0%, rgba(255,255,255,0) 70%); z-index:0; filter:blur(60px); pointer-events:none; }
        .blob-2 { position:fixed; bottom:-20%; right:-10%; width:60vw; height:60vw; border-radius:50%; background:radial-gradient(circle, rgba(224,143,178,0.3) 0%, rgba(255,255,255,0) 70%); z-index:0; filter:blur:80px; pointer-events:none; }
        .auth-wrap { width:100%; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; position:relative; z-index:10; }
        .auth-box { background:rgba(255,255,255,0.75); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.85); border-radius:24px; padding:2.5rem; width:100%; max-width:430px; box-shadow:0 20px 40px rgba(95,168,211,0.15); }
        .auth-logo { font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.3rem; color:var(--ink); display:flex; align-items:center; gap:0.5rem; margin-bottom:1.75rem; justify-content:center; }
        .form-input { background:rgba(255,255,255,0.8); border:1px solid rgba(208,215,224,0.8); transition:all 0.3s ease; }
        .form-input:focus { background:#fff; border-color:var(--blue-deep); box-shadow:0 0 0 4px rgba(95,168,211,0.15); }
        .step-indicator { display:flex; gap:0.4rem; justify-content:center; margin-bottom:1.5rem; }
        .step-dot { width:32px; height:5px; border-radius:10px; }
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

        {{-- Step indicator --}}
        <div class="step-indicator">
            <div class="step-dot" style="background:var(--blue-deep);"></div>
            <div class="step-dot" style="background:#e2e8f0;"></div>
            <div class="step-dot" style="background:#e2e8f0;"></div>
        </div>

        <div style="text-align:center; margin-bottom:1.75rem;">
            <div style="width:56px;height:56px;background:#e0e7ff;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <svg width="28" height="28" fill="none" stroke="#4f46e5" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
            <h1 style="font-size:1.4rem;font-weight:800;font-family:'Space Grotesk',sans-serif;margin-bottom:0.3rem;color:var(--ink);">Lupa Password?</h1>
            <p style="font-size:0.85rem;color:var(--ink-soft);line-height:1.5;">Masukkan email akun Anda. Kami akan mengirimkan<br>kode OTP 6 digit untuk mereset password.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success" style="margin-bottom:1rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.send') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Kirim Kode OTP
            </button>
        </form>

        <div style="height:1px;background:rgba(208,215,224,0.5);margin:1.5rem 0;"></div>
        <p style="text-align:center;font-size:0.875rem;color:var(--ink-soft);">
            Ingat password? <a href="{{ route('login') }}" style="color:var(--blue-deep);font-weight:600;text-decoration:none;">Kembali Masuk</a>
        </p>
    </div>
</div>
</body>
</html>
