<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Informasi') — WH CHINA by CECE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { 
            background: linear-gradient(135deg, var(--blue) 0%, var(--pink) 100%);
            background-attachment: fixed;
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: 'Manrope', sans-serif;
        }

        .landing-navbar {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            position: fixed; width: 100%; top: 0; z-index: 1000;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 5%; transition: all 0.3s ease;
        }
        
        .landing-brand { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.5rem; color: var(--ink); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; }
        .landing-nav { display: flex; gap: 2rem; align-items: center; }
        .landing-nav a { color: var(--ink); text-decoration: none; font-weight: 500; font-size: 0.9rem; transition: color 0.2s; }
        .landing-nav a:hover { color: var(--blue-deep); }
        @media (max-width: 768px) { .landing-nav { display: none; } }


        .content-container {
            flex: 1;
            max-width: 860px;
            width: 100%;
            margin: 5rem auto 4rem;
            padding: 0 1.25rem;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.9);
            padding: 2.5rem;
            box-shadow: 0 12px 40px rgba(95, 168, 211, 0.12);
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .footer {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--ink-soft);
            font-size: 0.85rem;
            border-top: 1px solid rgba(255,255,255,0.4);
        }

        @media (max-width: 640px) {
            .main-card { padding: 1.5rem; }
            .landing-nav { gap: 0.85rem; }
            .landing-nav a:not(.btn-nav) { display: none; }
        }
    </style>
</head>
<body>

    {{-- Navbar Navigation --}}
    <nav class="landing-navbar">
        <a href="{{ route('home') }}" class="landing-brand">
            <span style="width:12px;height:12px;background:var(--pink-deep);border-radius:50%;display:inline-block;"></span>
            WH CHINA by CECE
        </a>
        <div class="landing-nav">
            <a href="{{ route('home') }}#cara-kerja">Cara Kerja</a>
            <a href="{{ route('home') }}#harga">Harga &amp; Info</a>
            <a href="{{ route('tnc') }}" style="{{ request()->routeIs('tnc') ? 'color:var(--blue-deep); font-weight:700;' : '' }}">Syarat &amp; Ketentuan</a>
            <a href="{{ route('privacy') }}" style="{{ request()->routeIs('privacy') ? 'color:var(--blue-deep); font-weight:700;' : '' }}">Kebijakan Privasi</a>
            @auth
                <a href="{{ route('dashboard') }}" style="background:var(--ink); color:var(--white); padding:0.6rem 1.25rem; border-radius:50px; font-weight:600; text-decoration:none;">Ke Dashboard</a>
            @else
                <a href="{{ route('login') }}" style="font-weight:600;">Masuk</a>
                <a href="{{ route('register') }}" style="background:var(--ink); color:var(--white); padding:0.6rem 1.25rem; border-radius:50px; font-weight:600; text-decoration:none;">Daftar</a>
            @endauth
        </div>
    </nav>


    {{-- Main Content --}}
    <div class="content-container">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="footer">
        &copy; {{ date('Y') }} WH CHINA by CECE. All rights reserved.
    </footer>

</body>
</html>
