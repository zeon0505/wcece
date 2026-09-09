<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Gudangku') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * { font-family: 'Manrope', sans-serif; }
            body { background: #f4f6fb; }
            .glass-card {
                background: rgba(255,255,255,0.92);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(96,165,250,0.18);
                border-radius: 20px;
                box-shadow: 0 8px 32px rgba(96,165,250,0.10);
            }
            .input-custom {
                border: 1.5px solid rgba(96,165,250,0.25);
                border-radius: 12px;
                padding: 11px 14px;
                font-size: 0.9rem;
                transition: all 0.2s;
                background: rgba(255,255,255,0.95);
                width: 100%;
                color: #111827;
                display: block;
            }
            .input-custom:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96,165,250,0.15);
                outline: none;
            }
            .btn-primary-gk {
                background: linear-gradient(135deg, #60a5fa 0%, #f472b6 100%);
                color: white; border: none;
                border-radius: 12px;
                font-weight: 700; padding: 12px 28px;
                transition: all 0.25s;
                box-shadow: 0 4px 15px rgba(96,165,250,0.30);
                cursor: pointer; width: 100%;
                font-size: 0.95rem;
            }
            .btn-primary-gk:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(96,165,250,0.42);
            }
            .blob-blue {
                position: fixed; top: -80px; left: -80px;
                width: 400px; height: 400px;
                background: rgba(191,219,254,0.45);
                border-radius: 50%; filter: blur(70px);
                pointer-events: none; z-index: 0;
            }
            .blob-pink {
                position: fixed; bottom: -80px; right: -80px;
                width: 400px; height: 400px;
                background: rgba(251,207,232,0.45);
                border-radius: 50%; filter: blur(70px);
                pointer-events: none; z-index: 0;
            }
        </style>
    </head>
    <body>
        <div class="blob-blue"></div>
        <div class="blob-pink"></div>
        <div style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding: 24px; position:relative; z-index:1;">
            <a href="/" style="margin-bottom:28px; display:flex; align-items:center; gap:8px; text-decoration:none;">
                <svg style="width:34px;height:34px;color:#60a5fa;" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16.5C21 16.88 20.79 17.21 20.47 17.38L12.57 21.82C12.41 21.94 12.21 22 12 22C11.79 22 11.59 21.94 11.43 21.82L3.53 17.38C3.21 17.21 3 16.88 3 16.5V7.5C3 7.12 3.21 6.79 3.53 6.62L11.43 2.18C11.59 2.06 11.79 2 12 2C12.21 2 12.41 2.06 12.57 2.18L20.47 6.62C20.79 6.79 21 7.12 21 7.5V16.5ZM12 4.15L5.04 8.06L12 11.97L18.96 8.06L12 4.15Z"/></svg>
                <span style="font-size:1.5rem;font-weight:800;color:#111827;">Gudangku</span>
            </a>
            <div class="glass-card" style="width:100%;max-width:440px;padding:36px 40px;">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
