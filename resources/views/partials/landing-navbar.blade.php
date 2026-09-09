<nav class="landing-navbar">
    <a href="{{ route('home') }}" class="landing-brand">
        <span style="width:12px;height:12px;background:var(--pink-deep);border-radius:50%;display:inline-block;"></span>
        WH CHINA by CECE
    </a>
    <div class="landing-nav">
        <a href="{{ request()->routeIs('home') ? '#cara-kerja' : route('home') . '#cara-kerja' }}">Cara Kerja</a>
        <a href="{{ request()->routeIs('home') ? '#harga' : route('home') . '#harga' }}">Harga &amp; Info</a>
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
