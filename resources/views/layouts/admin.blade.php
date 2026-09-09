<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="WH CHINA by CECE Admin — Panel pengelolaan gudang dan pengiriman.">
    <title>@yield('title', 'Admin') — WH CE Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }

        /* ── Overlay ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45); z-index: 149;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active { display: block; }

        /* ── Sidebar ── */
        .admin-sidebar {
            position: fixed; left: 0; top: 0; bottom: 0;
            width: 220px; background: var(--white);
            border-right: 1px solid var(--line);
            overflow-y: auto; z-index: 150;
            transition: transform 0.3s ease;
            display: flex; flex-direction: column;
        }

        .admin-nav-item {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.55rem 0.65rem; text-decoration: none;
            border-radius: 8px; font-size: 0.85rem; font-weight: 600;
            color: var(--ink); transition: all 0.15s; margin-top: 2px;
        }
        .admin-nav-item:hover {
            background: var(--surface);
        }
        .admin-nav-item.active {
            background: rgba(79,70,229,0.1);
            color: #4f46e5;
            box-shadow: inset 3px 0 0 #4f46e5;
        }

        /* ── Topbar ── */
        .admin-topbar {
            position: fixed; top: 0; left: 220px; right: 0; height: 56px;
            background: rgba(255,255,255,0.95); backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--line);
            display: flex; justify-content: space-between; align-items: center;
            padding: 0 1.5rem; z-index: 140;
            transition: left 0.3s ease;
        }

        /* ── Main ── */
        .main-content {
            margin-left: 220px;
            padding-top: 56px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* ── Hamburger ── */
        .hamburger {
            display: none; flex-direction: column; gap: 5px;
            cursor: pointer; background: none; border: none;
            padding: 8px; border-radius: 8px; transition: background 0.2s;
        }
        .hamburger:hover { background: var(--surface); }
        .hamburger span {
            display: block; width: 20px; height: 2px;
            background: var(--ink); border-radius: 2px; transition: all 0.3s;
        }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            html, body { max-width: 100vw; overflow-x: hidden; }
            .admin-sidebar { transform: translateX(-100%); }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-topbar { left: 0; padding: 0 0.75rem; }
            .main-content { margin-left: 0; padding-top: 80px !important; padding-left: 0.75rem; padding-right: 0.75rem; max-width: 100vw; overflow-x: hidden; }
            .hamburger { display: flex; }
            .admin-topbar-title { font-size: 0.85rem; }
            .admin-topbar-right .pending-badge span { display: none; }
        }
    </style>
</head>
<body>

{{-- Overlay --}}
<div class="sidebar-overlay" id="adminOverlay" onclick="closeAdminSidebar()"></div>

{{-- Sidebar --}}
<aside class="admin-sidebar" id="adminSidebar">
    {{-- Sidebar Header --}}
    <div style="padding: 1rem 1rem 0.75rem; border-bottom: 1px solid var(--line); flex-shrink:0;">
        <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:0.6rem; text-decoration:none;">
            <span style="display:flex; align-items:center; justify-content:center; width:30px; height:30px; background:linear-gradient(135deg, var(--ink), #4f46e5); border-radius:8px; color:#fff; flex-shrink:0;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </span>
            <span style="font-weight:800; font-size:1rem; color:var(--ink); letter-spacing:-0.02em;">WH CE <span style="color:var(--ink-soft); font-weight:500;">Admin</span></span>
        </a>
    </div>

    {{-- Nav --}}
    <nav style="padding: 0.75rem 0.75rem; flex:1; overflow-y:auto; display:flex; flex-direction:column; gap:1.25rem;">

        <div>
            <p style="font-size:0.65rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.08em; padding:0 0.5rem; margin-bottom:0.4rem;">Overview</p>
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
        </div>

        <div>
            <p style="font-size:0.65rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.08em; padding:0 0.5rem; margin-bottom:0.4rem;">Paket &amp; Resi</p>
            <a href="{{ route('admin.resis.index') }}" class="admin-nav-item {{ request()->routeIs('admin.resis.index') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Semua Resi
            </a>
            <a href="{{ route('admin.resis.received') }}" class="admin-nav-item {{ request()->routeIs('admin.resis.received') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Paket Diterima
            </a>
        </div>

        <div>
            <p style="font-size:0.65rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.08em; padding:0 0.5rem; margin-bottom:0.4rem;">Pengiriman</p>
            <a href="{{ route('admin.shipments.index') }}" class="admin-nav-item {{ request()->routeIs('admin.shipments.index') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Semua Box Shipment
            </a>
            <a href="{{ route('admin.shipments.create') }}" class="admin-nav-item {{ request()->routeIs('admin.shipments.create') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Buat Box Baru
            </a>
        </div>

        <div>
            <p style="font-size:0.65rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.08em; padding:0 0.5rem; margin-bottom:0.4rem;">Unclaimed</p>
            <a href="{{ route('admin.claims.index') }}" class="admin-nav-item {{ request()->routeIs('admin.claims.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Verifikasi Klaim
                @php $pendingClaim = \App\Models\ResiClaim::where('status','pending')->count(); @endphp
                @if($pendingClaim)
                    <span style="margin-left:auto; font-size:0.65rem; background:#f59e0b; color:#fff; padding:1px 6px; border-radius:20px;">{{ $pendingClaim }}</span>
                @endif
            </a>
        </div>

        <div>
            <p style="font-size:0.65rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.08em; padding:0 0.5rem; margin-bottom:0.4rem;">Member</p>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Manajemen User
                @php $pendingUser = \App\Models\User::where('is_approved', false)->where('role','!=','admin')->count(); @endphp
                @if($pendingUser)
                    <span style="margin-left:auto; font-size:0.65rem; background:#f59e0b; color:#fff; padding:1px 6px; border-radius:20px;">{{ $pendingUser }}</span>
                @endif
            </a>
        </div>


        <div>
            <p style="font-size:0.65rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.08em; padding:0 0.5rem; margin-bottom:0.4rem;">Keuangan</p>
            <a href="{{ route('admin.payments.index') }}" class="admin-nav-item {{ request()->routeIs('admin.payments.index') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Verifikasi Bayar
                @php $pendingPay = \App\Models\Payment::where('status','pending')->count(); @endphp
                @if($pendingPay)
                    <span style="margin-left:auto; font-size:0.65rem; background:#dc2626; color:#fff; padding:1px 6px; border-radius:20px;">{{ $pendingPay }}</span>
                @endif
            </a>
            <a href="{{ route('admin.payments.history') }}" class="admin-nav-item {{ request()->routeIs('admin.payments.history*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                History Pembayaran
            </a>
            <a href="{{ route('admin.settings.index') }}" class="admin-nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                Pengaturan Admin
            </a>
        </div>
    </nav>

    {{-- Sidebar Footer --}}
    <div style="padding: 0.75rem; border-top: 1px solid var(--line); flex-shrink:0;">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem;">
            <div style="display:flex; align-items:center; gap:0.6rem; overflow:hidden;">
                <div style="width:30px; height:30px; border-radius:8px; background:linear-gradient(135deg, #60a5fa, #f472b6); display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.8rem; color:white; flex-shrink:0;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="overflow:hidden;">
                    <div style="font-size:0.8rem; font-weight:700; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
                    <div style="font-size:0.68rem; color:var(--ink-soft);">Admin Panel</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="flex-shrink:0;">
                @csrf
                <button type="submit" title="Keluar" style="display:flex; align-items:center; justify-content:center; width:30px; height:30px; background:var(--surface); border:1px solid var(--line); border-radius:7px; cursor:pointer; color:var(--ink-soft); transition:all 0.2s;" onmouseover="this.style.background='#fef2f2'; this.style.color='#dc2626'; this.style.borderColor='#fecaca';" onmouseout="this.style.background='var(--surface)'; this.style.color='var(--ink-soft)'; this.style.borderColor='var(--line)';">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Topbar --}}
<nav class="admin-topbar">
    <div style="display:flex; align-items:center; gap:0.75rem;">
        <button class="hamburger" onclick="toggleAdminSidebar()" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
        <span class="admin-topbar-title" style="font-size:0.95rem; font-weight:700; color:var(--ink);">@yield('title', 'Dashboard')</span>
    </div>
    <div class="admin-topbar-right" style="display:flex; align-items:center; gap:0.75rem;">
        @php $pendingCount = \App\Models\Payment::where('status','pending')->count(); @endphp
        @if($pendingCount)
            <a href="{{ route('admin.payments.index') }}" class="pending-badge" style="display:flex; align-items:center; gap:0.4rem; background:#fef2f2; color:#dc2626; padding:0.35rem 0.65rem; border-radius:20px; font-size:0.72rem; font-weight:700; text-decoration:none; border:1px solid #fecaca; white-space:nowrap;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $pendingCount }} Verifikasi</span>
            </a>
        @endif
        <div style="font-size:0.8rem; font-weight:700; color:var(--ink); white-space:nowrap;">{{ auth()->user()->name }}</div>
    </div>
</nav>

{{-- Main --}}
<main class="main-content">
    <div style="padding: 1.5rem;">
        @if(session('success'))
            <div class="alert alert-success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-error" style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:1rem; border-radius:10px; margin-bottom:1.5rem;">
                <div style="font-weight:700; margin-bottom:0.5rem;">Terdapat Kesalahan:</div>
                <ul style="margin:0; padding-left:1.5rem; font-size:0.875rem;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</main>

<script>
function toggleAdminSidebar() {
    document.getElementById('adminSidebar').classList.toggle('open');
    document.getElementById('adminOverlay').classList.toggle('active');
}
function closeAdminSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('adminOverlay').classList.remove('active');
}
// Close on nav click on mobile
document.querySelectorAll('.admin-sidebar a').forEach(a => {
    a.addEventListener('click', () => {
        if (window.innerWidth <= 768) closeAdminSidebar();
    });
});
</script>
</body>
</html>
