<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'WH CE') }} - {{ $title ?? 'Dashboard' }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * { font-family: 'Manrope', sans-serif; box-sizing: border-box; }
            body { 
                background: linear-gradient(135deg, #e0f2fe 0%, #fce7f3 100%); 
                background-attachment: fixed;
                margin: 0; 
                color: #111827; 
            }

            /* ── Sidebar ───────────────────────── */
            .sidebar {
                position: fixed; left: 0; top: 0; height: 100vh; width: 240px;
                background: white;
                border-right: 1px solid rgba(96,165,250,0.12);
                box-shadow: 4px 0 20px rgba(96,165,250,0.06);
                display: flex; flex-direction: column;
                z-index: 200; overflow-y: auto; overflow-x: hidden;
                transition: transform 0.3s ease;
            }
            .sidebar-logo {
                padding: 22px 20px 16px;
                display: flex; align-items: center; gap: 10px;
                border-bottom: 1px solid rgba(96,165,250,0.10);
                flex-shrink: 0;
            }
            .sidebar-logo span { font-size: 1.2rem; font-weight: 800; color: #111827; }
            .sidebar-nav { padding: 16px 12px; flex: 1; overflow-y: auto; display: flex; flex-direction: column; align-items: stretch; }
            .sidebar-section {
                font-size: 0.68rem; font-weight: 700; color: #9ca3af;
                letter-spacing: 0.10em; text-transform: uppercase;
                padding: 4px 8px; margin: 16px 0 4px;
            }
            .nav-item {
                display: flex; align-items: center; gap: 10px;
                padding: 9px 12px; border-radius: 10px;
                font-size: 0.875rem; font-weight: 600; color: #6b7280;
                text-decoration: none; transition: all 0.2s; margin-bottom: 2px;
                width: 100%; box-sizing: border-box;
            }
            .nav-item:hover { background: rgba(96,165,250,0.08); color: #3b82f6; }
            .nav-item.active {
                background: linear-gradient(135deg, rgba(96,165,250,0.15) 0%, rgba(244,114,182,0.15) 100%);
                color: #3b82f6;
                box-shadow: inset 3px 0 0 #3b82f6;
            }
            .nav-item svg { flex-shrink: 0; }
            .sidebar-footer {
                padding: 16px 12px;
                border-top: 1px solid rgba(96,165,250,0.10);
                flex-shrink: 0;
            }
            .user-chip {
                display: flex; align-items: center; gap: 10px;
                padding: 10px 12px; border-radius: 12px;
                background: rgba(96,165,250,0.06);
            }
            .user-avatar {
                width: 34px; height: 34px; border-radius: 10px;
                background: linear-gradient(135deg, #60a5fa, #f472b6);
                display: flex; align-items: center; justify-content: center;
                font-weight: 800; font-size: 0.85rem; color: white;
                flex-shrink: 0;
            }
            .user-name { font-weight: 700; font-size: 0.85rem; color: #111827; }
            .user-role { font-size: 0.72rem; color: #9ca3af; font-weight: 500; }

            /* ── Topbar ────────────────────────── */
            .topbar {
                position: fixed; left: 240px; right: 0; top: 0; height: 64px;
                background: rgba(255,255,255,0.92); backdrop-filter: blur(20px);
                border-bottom: 1px solid rgba(96,165,250,0.10);
                display: flex; align-items: center; justify-content: space-between;
                padding: 0 28px; z-index: 190;
                transition: left 0.3s ease;
            }
            .topbar-title { font-size: 1.1rem; font-weight: 700; color: #111827; }
            .topbar-badge {
                padding: 5px 14px; border-radius: 999px; font-size: 0.75rem; font-weight: 700;
                background: linear-gradient(135deg, rgba(96,165,250,0.15), rgba(244,114,182,0.15));
                color: #3b82f6;
            }

            /* ── Hamburger ────────────────────── */
            .hamburger {
                display: none;
                flex-direction: column; gap: 5px; cursor: pointer;
                background: none; border: none; padding: 8px;
                border-radius: 8px; transition: background 0.2s;
            }
            .hamburger:hover { background: rgba(96,165,250,0.08); }
            .hamburger span {
                display: block; width: 22px; height: 2px;
                background: #374151; border-radius: 2px; transition: all 0.3s;
            }

            /* ── Overlay ──────────────────────── */
            .sidebar-overlay {
                display: none; position: fixed; inset: 0;
                background: rgba(0,0,0,0.4); z-index: 195;
                backdrop-filter: blur(2px);
            }
            .sidebar-overlay.active { display: block; }

            /* ── Main Content ──────────────────── */
            .main-content { margin-left: 240px; padding-top: 64px; min-height: 100vh; }
            .main-inner { padding: 28px; }

            /* ── Cards ─────────────────────────── */
            .glass-card {
                background: white;
                border: 1px solid rgba(96,165,250,0.10);
                border-radius: 16px;
                box-shadow: 0 2px 16px rgba(96,165,250,0.07);
            }
            .stat-card {
                background: white;
                border: 1px solid rgba(96,165,250,0.10);
                border-radius: 14px;
                padding: 20px;
                box-shadow: 0 2px 12px rgba(96,165,250,0.06);
            }

            /* ── Table ─────────────────────────── */
            .table-gk { width: 100%; border-collapse: separate; border-spacing: 0; }
            .table-gk thead th {
                background: #f9fafb; font-size: 0.75rem; font-weight: 700;
                color: #9ca3af; letter-spacing: 0.05em; text-transform: uppercase;
                padding: 12px 16px; border-bottom: 1px solid rgba(96,165,250,0.10);
                text-align: left;
            }
            .table-gk tbody td {
                padding: 13px 16px; border-bottom: 1px solid #f3f4f6;
                font-size: 0.875rem; color: #374151; vertical-align: middle;
            }
            .table-gk tbody tr:hover td { background: rgba(96,165,250,0.03); }
            .table-gk tbody tr:last-child td { border-bottom: none; }

            /* ── Badges ────────────────────────── */
            .badge-gk { padding:4px 12px; border-radius:999px; font-size:0.75rem; font-weight:700; display:inline-block; }
            .badge-blue   { background:rgba(96,165,250,0.12);  color:#1d4ed8; }
            .badge-pink   { background:rgba(244,114,182,0.12); color:#be185d; }
            .badge-green  { background:rgba(34,197,94,0.12);   color:#15803d; }
            .badge-yellow { background:rgba(234,179,8,0.12);   color:#a16207; }
            .badge-red    { background:rgba(239,68,68,0.12);   color:#b91c1c; }
            .badge-gray   { background:rgba(156,163,175,0.15); color:#4b5563; }

            /* ── Buttons ───────────────────────── */
            .btn-gk-primary {
                background: linear-gradient(135deg, #60a5fa, #f472b6);
                color: white; border: none; border-radius: 10px;
                font-weight: 700; font-size: 0.85rem; padding: 9px 20px;
                cursor: pointer; transition: all 0.2s;
                display: inline-flex; align-items: center; gap: 6px;
                box-shadow: 0 3px 12px rgba(96,165,250,0.28);
                text-decoration: none;
            }
            .btn-gk-primary:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(96,165,250,0.38);
                color: white;
            }
            .btn-gk-outline {
                background: white; color: #3b82f6;
                border: 1.5px solid rgba(96,165,250,0.30);
                border-radius: 10px; font-weight: 700; font-size: 0.85rem; padding: 8px 18px;
                cursor: pointer; transition: all 0.2s;
                display: inline-flex; align-items: center; gap: 6px;
                text-decoration: none;
            }
            .btn-gk-outline:hover {
                border-color: #60a5fa;
                background: rgba(96,165,250,0.05);
                color: #3b82f6;
            }

            /* ── Form Inputs ───────────────────── */
            .input-gk {
                border: 1.5px solid rgba(96,165,250,0.20);
                border-radius: 10px; padding: 10px 14px; font-size: 0.875rem;
                background: white; width: 100%; color: #111827; transition: all 0.2s;
            }
            .input-gk:focus {
                border-color: #60a5fa;
                box-shadow: 0 0 0 3px rgba(96,165,250,0.13);
                outline: none;
            }

            /* ── Mobile Responsive ─────────────── */
            @media (max-width: 768px) {
                html, body { max-width: 100vw; overflow-x: hidden; }
                .sidebar {
                    transform: translateX(-100%);
                    width: 260px;
                }
                .sidebar.open { transform: translateX(0); }
                .topbar { left: 0; padding: 0 14px; }
                .main-content { margin-left: 0; padding-top: 80px !important; max-width: 100vw; overflow-x: hidden; }
                .main-inner { padding: 12px 10px !important; max-width: 100vw; overflow-x: hidden; }
                .hamburger { display: flex; }
                .topbar-title { font-size: 0.9rem; }
            }
        </style>
    </head>
    <body>

        {{-- ── Sidebar Overlay (mobile) ───────────────────────────────── --}}
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        {{-- ── Custom Sidebar ─────────────────────────────────────────── --}}
        <div class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                    <rect width="28" height="28" rx="8" fill="url(#logoGrad)"/>
                    <path d="M7 10l7-4 7 4v8l-7 4-7-4V10z" fill="white" opacity="0.9"/>
                    <defs>
                        <linearGradient id="logoGrad" x1="0" y1="0" x2="28" y2="28">
                            <stop stop-color="#60a5fa"/>
                            <stop offset="1" stop-color="#f472b6"/>
                        </linearGradient>
                    </defs>
                </svg>
                <span>{{ config('app.name', 'WH CE') }}</span>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-section">Menu Utama</div>

                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('track.index') }}" class="nav-item {{ request()->routeIs('track.*') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/></svg>
                    Lacak Resi
                </a>

                <a href="{{ route('resi.create') }}" class="nav-item {{ request()->routeIs('resi.create') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Input Resi
                </a>

                <a href="{{ route('address.index') }}" class="nav-item {{ request()->routeIs('address.*') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Alamat WH
                </a>

                <a href="{{ route('tnc') }}" class="nav-item {{ request()->routeIs('tnc') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Syarat &amp; Ketentuan
                </a>

                <div class="sidebar-section">Keuangan</div>

                <a href="{{ route('invoices.index') }}" class="nav-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Tagihan Saya
                    @php $unpaid = Auth::check() && Auth::user() ? Auth::user()->invoices()->where('status','unpaid')->count() : 0; @endphp
                    @if($unpaid > 0)
                        <span style="margin-left:auto; background:#dc2626; color:white; font-size:0.65rem; font-weight:800; padding:2px 7px; border-radius:99px;">{{ $unpaid }}</span>
                    @endif
                </a>
            </nav>

            <div class="sidebar-footer" style="padding: 16px; border-top: 1px solid rgba(96,165,250,0.15); background: #fafbfc;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div class="user-avatar" style="width: 36px; height: 36px; border-radius: 12px; background: linear-gradient(135deg, #60a5fa, #f472b6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem;">
                            {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : '?' }}
                        </div>
                        <div>
                            <div class="user-name" style="font-weight: 700; font-size: 0.9rem; color: #111827;">{{ Auth::user()?->name }}</div>
                            <div class="user-role" style="font-size: 0.75rem; color: #6b7280; font-weight: 500;">Customer</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" title="Keluar" style="background: none; border: none; padding: 6px; cursor: pointer; color: #9ca3af; border-radius: 8px; transition: all 0.2s;" onmouseover="this.style.background='rgba(239,68,68,0.1)'; this.style.color='#ef4444';" onmouseout="this.style.background='none'; this.style.color='#9ca3af';">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Topbar ───────────────────────────────────────────────────── --}}
        <div class="topbar">
            <div style="display:flex; align-items:center; gap:12px;">
                <button class="hamburger" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <span></span><span></span><span></span>
                </button>
                <div class="topbar-title">@yield('title', 'Dashboard')</div>
            </div>
            <div style="display:flex; align-items:center; gap:12px;">
                @php $unpaidCount = Auth::check() && Auth::user() ? Auth::user()->invoices()->where('status','unpaid')->count() : 0; @endphp
                @if($unpaidCount > 0)
                    <a href="{{ route('invoices.index') }}" class="topbar-badge" style="text-decoration:none;">
                        {{ $unpaidCount }} Tagihan Belum Dibayar
                    </a>
                @endif
                @if(Auth::check())
                    <a href="{{ route('profile.edit') }}" style="font-size:0.85rem; font-weight:600; color:#6b7280; text-decoration:none;">⚙ Profil</a>
                @endif
            </div>
        </div>

        {{-- ── Main Content ──────────────────────────────────────────────── --}}
        <div class="main-content">
            <div class="main-inner">
                @if(session('success'))
                    <div style="background:#dcfce7; color:#15803d; border:1px solid #86efac; border-radius:12px; padding:12px 16px; margin-bottom:20px; font-size:0.875rem; font-weight:600; display:flex; align-items:center; gap:8px;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background:#fee2e2; color:#b91c1c; border:1px solid #fca5a5; border-radius:12px; padding:12px 16px; margin-bottom:20px; font-size:0.875rem; font-weight:600;">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>

        <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
        // Close sidebar when navigating on mobile
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768) closeSidebar();
            });
        });
        </script>
    </body>
</html>
