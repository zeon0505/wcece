<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Baru — WH CHINA by CECE</title>
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
        .pw-wrap { position:relative; }
        .pw-toggle { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:var(--ink-soft); padding:4px; }
        .strength-bar { height:4px; border-radius:4px; margin-top:6px; transition:all 0.3s; }
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
            <div class="step-dot" style="background:#10b981;"></div>
            <div class="step-dot" style="background:#10b981;"></div>
            <div class="step-dot" style="background:var(--blue-deep);"></div>
        </div>

        <div style="text-align:center; margin-bottom:1.75rem;">
            <div style="width:56px;height:56px;background:#e0e7ff;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <svg width="28" height="28" fill="none" stroke="#4f46e5" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <h1 style="font-size:1.4rem;font-weight:800;font-family:'Space Grotesk',sans-serif;margin-bottom:0.3rem;color:var(--ink);">Buat Password Baru</h1>
            <p style="font-size:0.85rem;color:var(--ink-soft);">Akun: <strong>{{ $email }}</strong></p>
        </div>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.reset') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="password">Password Baru</label>
                <div class="pw-wrap">
                    <input id="password" type="password" name="password" class="form-input" required minlength="8" placeholder="Min. 8 karakter" autocomplete="new-password" style="padding-right:40px;">
                    <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="strength-bar" id="strength-bar" style="background:#e2e8f0; width:0%;"></div>
                <p id="strength-text" style="font-size:0.72rem; color:var(--ink-soft); margin-top:4px;"></p>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <div class="pw-wrap">
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required minlength="8" placeholder="Ulangi password baru" autocomplete="new-password" style="padding-right:40px;">
                    <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Password Baru
            </button>
        </form>
    </div>
</div>

<script>
function togglePw(inputId, btn) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.style.opacity = isText ? '0.6' : '1';
}

document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    const bar = document.getElementById('strength-bar');
    const text = document.getElementById('strength-text');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const configs = [
        { pct: '0%', color: '#e2e8f0', label: '' },
        { pct: '25%', color: '#ef4444', label: '😬 Sangat Lemah' },
        { pct: '50%', color: '#f97316', label: '😐 Lemah' },
        { pct: '75%', color: '#eab308', label: '😊 Sedang' },
        { pct: '100%', color: '#22c55e', label: '💪 Kuat' },
    ];
    const c = configs[score] || configs[0];
    bar.style.width = val.length ? c.pct : '0%';
    bar.style.background = c.color;
    text.textContent = val.length ? c.label : '';
    text.style.color = c.color;
});
</script>
</body>
</html>
