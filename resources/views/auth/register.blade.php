<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — WH CHINA by CECE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            background: linear-gradient(135deg, var(--blue) 0%, var(--pink) 100%);
            background-attachment: fixed;
            color: var(--ink);
            position: relative;
            overflow-x: hidden;
        }
        .blob-1 { position: absolute; top: -10%; left: -10%; width: 50vw; height: 50vw; border-radius: 50%; background: radial-gradient(circle, rgba(95, 168, 211, 0.4) 0%, rgba(255,255,255,0) 70%); z-index: -1; filter: blur(60px); }
        .blob-2 { position: absolute; bottom: -20%; right: -10%; width: 60vw; height: 60vw; border-radius: 50%; background: radial-gradient(circle, rgba(224, 143, 178, 0.3) 0%, rgba(255,255,255,0) 70%); z-index: -1; filter: blur(80px); }
        
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
<div class="auth-wrap">
    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="auth-box">
        <div class="auth-logo">
            <span style="width:10px;height:10px;background:var(--blue-deep);border-radius:50%;display:inline-block;"></span>
            WH CHINA by CECE
        </div>
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size:1.5rem;font-weight:800;font-family:'Space Grotesk',sans-serif;margin-bottom:0.375rem;color:var(--ink);">Buat akun baru</h1>
            <p style="font-size:0.875rem;color:var(--ink-soft);">Daftar untuk mulai tracking paket Anda.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="name">Username</label>
                <input id="name" type="text" name="name" class="form-input {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Username kamu">
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" required autocomplete="email" placeholder="budi@email.com">
            </div>
            <div class="form-group">
                <label class="form-label" for="phone">No. HP <span style="font-weight:400;color:var(--ink-soft);">(opsional)</span></label>
                <input id="phone" type="tel" name="phone" class="form-input" value="{{ old('phone') }}" autocomplete="tel" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" class="form-input" required autocomplete="new-password" placeholder="Min. 8 karakter">
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="Ulangi password">
            </div>
            @php
                $tncText = \App\Http\Controllers\Admin\SettingsController::get('tnc_content', '');
            @endphp
            @if($tncText)
                <div style="margin-bottom:1rem;">
                    <label class="form-label" style="display:block; font-size:0.8rem; font-weight:700; color:var(--ink); margin-bottom:0.3rem;">Syarat &amp; Ketentuan (TnC)</label>
                    <div style="max-height:130px; overflow-y:auto; background:rgba(255,255,255,0.9); border:1px solid rgba(208, 215, 224, 0.8); border-radius:10px; padding:0.65rem 0.85rem; font-size:0.75rem; color:var(--ink); line-height:1.45; white-space:pre-line;">{{ $tncText }}</div>
                </div>
            @endif

            <div class="form-group" style="display:flex; align-items:flex-start; gap:0.5rem; margin-bottom:1.5rem; padding-top:0.25rem;">
                <input type="checkbox" id="tnc" name="tnc" required style="margin-top:0.2rem; cursor:pointer;" {{ old('tnc') ? 'checked' : '' }}>
                <label for="tnc" style="font-size:0.8rem; color:var(--ink-soft); line-height:1.4; cursor:pointer; font-weight:normal;">
                    Saya menyetujui <a href="{{ route('tnc') }}" target="_blank" style="color:var(--blue-deep); text-decoration:none; font-weight:600;">Syarat & Ketentuan</a> di atas serta <a href="{{ route('privacy') }}" target="_blank" style="color:var(--blue-deep); text-decoration:none; font-weight:600;">Kebijakan Privasi</a> yang berlaku.
                </label>
            </div>
            <button type="submit" id="register-submit" class="btn btn-primary" style="width:100%; justify-content:center;">Buat Akun</button>
        </form>

        <div class="divider"></div>
        <p style="text-align:center; font-size:0.875rem; color:var(--ink-soft);">
            Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--blue-deep); font-weight:600; text-decoration:none;">Masuk</a>
        </p>
    </div>
</div>
</body>
</html>
