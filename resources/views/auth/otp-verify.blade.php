<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode OTP — WH CHINA by CECE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden; }
        .blob-1 { position:fixed; top:-10%; left:-10%; width:50vw; height:50vw; border-radius:50%; background:radial-gradient(circle, rgba(95,168,211,0.4) 0%, rgba(255,255,255,0) 70%); z-index:0; filter:blur(60px); pointer-events:none; }
        .blob-2 { position:fixed; bottom:-20%; right:-10%; width:60vw; height:60vw; border-radius:50%; background:radial-gradient(circle, rgba(224,143,178,0.3) 0%, rgba(255,255,255,0) 70%); z-index:0; filter:blur:80px; pointer-events:none; }
        .auth-wrap { width:100%; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; position:relative; z-index:10; }
        .auth-box { background:rgba(255,255,255,0.75); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.85); border-radius:24px; padding:2.5rem; width:100%; max-width:430px; box-shadow:0 20px 40px rgba(95,168,211,0.15); }
        .auth-logo { font-family:'Space Grotesk',sans-serif; font-weight:700; font-size:1.3rem; color:var(--ink); display:flex; align-items:center; gap:0.5rem; margin-bottom:1.75rem; justify-content:center; }
        .step-indicator { display:flex; gap:0.4rem; justify-content:center; margin-bottom:1.5rem; }
        .step-dot { width:32px; height:5px; border-radius:10px; }
        .otp-inputs { display:flex; gap:0.6rem; justify-content:center; margin:1rem 0; }
        .otp-input { width:50px; height:58px; text-align:center; font-size:1.5rem; font-weight:800; font-family:'Space Mono',monospace; border:2px solid #e2e8f0; border-radius:12px; background:rgba(255,255,255,0.9); color:var(--ink); outline:none; transition:all 0.2s; }
        .otp-input:focus { border-color:#4f46e5; box-shadow:0 0 0 4px rgba(79,70,229,0.15); background:#fff; }
        .otp-input.filled { border-color:#4f46e5; background:#eef2ff; }
        .resend-btn { background:none; border:none; cursor:pointer; color:var(--blue-deep); font-size:0.875rem; font-weight:700; padding:0; text-decoration:underline; }
        .resend-btn:disabled { color:#94a3b8; cursor:not-allowed; text-decoration:none; }
        .countdown { font-size:0.875rem; color:var(--ink-soft); }
        .countdown span { font-weight:800; color:#4f46e5; font-family:'Space Mono',monospace; }
        @media (max-width: 400px) { .otp-input { width:40px; height:48px; font-size:1.25rem; } .otp-inputs { gap:0.4rem; } }
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
            <div class="step-dot" style="background:var(--blue-deep);"></div>
            <div class="step-dot" style="background:#e2e8f0;"></div>
        </div>

        <div style="text-align:center; margin-bottom:1.75rem;">
            <div style="width:56px;height:56px;background:#dcfce7;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                <svg width="28" height="28" fill="none" stroke="#15803d" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h1 style="font-size:1.4rem;font-weight:800;font-family:'Space Grotesk',sans-serif;margin-bottom:0.3rem;color:var(--ink);">Masukkan Kode OTP</h1>
            <p style="font-size:0.85rem;color:var(--ink-soft);line-height:1.5;">
                Kode 6 digit dikirim ke<br>
                <strong style="color:var(--ink);">{{ $email }}</strong>
            </p>
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

        <form method="POST" action="{{ route('otp.verify') }}" id="otp-form">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp_code" id="otp_code_hidden">

            {{-- 6-box OTP input --}}
            <div class="otp-inputs" id="otp-boxes">
                @for($i = 0; $i < 6; $i++)
                    <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1"
                        class="otp-input" data-index="{{ $i }}"
                        autocomplete="off"
                        id="otp-box-{{ $i }}">
                @endfor
            </div>

            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:0.7rem 1rem;font-size:0.78rem;color:#15803d;font-weight:600;text-align:center;margin-bottom:1.25rem;">
                ⏱️ Kode berlaku <strong>10 menit</strong> · Hanya bisa dipakai <strong>1 kali</strong>
            </div>

            <button type="submit" id="otp-submit" class="btn btn-primary" style="width:100%;justify-content:center;" disabled>
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Verifikasi Kode
            </button>
        </form>

        {{-- Resend Section with Countdown --}}
        <div style="margin-top:1.5rem; text-align:center;">
            <div id="countdown-wrap" class="countdown">
                Kirim ulang kode dalam <span id="timer">60</span> detik
            </div>
            <div id="resend-wrap" style="display:none;">
                <p style="font-size:0.85rem;color:var(--ink-soft);margin-bottom:0.5rem;">Tidak menerima kode?</p>
                <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="resend-btn">🔄 Kirim Ulang Kode OTP</button>
                </form>
            </div>
        </div>

        <div style="height:1px;background:rgba(208,215,224,0.5);margin:1.5rem 0;"></div>
        <p style="text-align:center;font-size:0.875rem;color:var(--ink-soft);">
            <a href="{{ route('otp.email.form') }}" style="color:var(--blue-deep);font-weight:600;text-decoration:none;">← Ganti Email</a>
        </p>
    </div>
</div>

<script>
    // ── OTP Box Interaction ───────────────────────────────────────────────────
    (function() {
        const boxes = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('otp_code_hidden');
        const submitBtn = document.getElementById('otp-submit');

        function updateHidden() {
            let code = '';
            boxes.forEach(b => code += b.value);
            hiddenInput.value = code;
            submitBtn.disabled = code.length < 6;
            boxes.forEach(b => b.classList.toggle('filled', b.value.length > 0));
        }

        boxes.forEach((box, i) => {
            box.addEventListener('input', function() {
                const val = this.value.replace(/\D/g, '');
                this.value = val.slice(0, 1);
                if (val && i < 5) boxes[i + 1].focus();
                updateHidden();
            });
            box.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && i > 0) {
                    boxes[i - 1].focus();
                    boxes[i - 1].value = '';
                    updateHidden();
                }
                if (e.key === 'ArrowLeft' && i > 0) boxes[i - 1].focus();
                if (e.key === 'ArrowRight' && i < 5) boxes[i + 1].focus();
            });
            box.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                paste.split('').slice(0, 6).forEach((c, j) => { if (boxes[j]) boxes[j].value = c; });
                boxes[Math.min(paste.length, 5)].focus();
                updateHidden();
            });
        });
        boxes[0].focus();
    })();

    // ── Countdown Timer ───────────────────────────────────────────────────────
    (function() {
        // otp_sent_at from server (Unix timestamp in seconds)
        const sentAt = {{ session('otp_sent_at', now()->timestamp) }};
        const COOLDOWN = 60; // seconds
        const timerEl = document.getElementById('timer');
        const countdownWrap = document.getElementById('countdown-wrap');
        const resendWrap = document.getElementById('resend-wrap');

        function tick() {
            const elapsed = Math.floor(Date.now() / 1000) - sentAt;
            const remaining = COOLDOWN - elapsed;
            if (remaining <= 0) {
                countdownWrap.style.display = 'none';
                resendWrap.style.display = 'block';
                return;
            }
            timerEl.textContent = String(remaining).padStart(2, '0');
            setTimeout(tick, 1000);
        }
        tick();
    })();
</script>
</body>
</html>
