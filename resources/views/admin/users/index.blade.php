@extends('layouts.admin')
@section('title', 'Manajemen User')

@section('content')
<div class="page-header">
    <h1 class="page-title">Manajemen User</h1>
    <p class="page-subtitle">Setujui atau tolak pendaftaran akun customer baru.</p>
</div>



{{-- Pending Users --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
        <h2 style="font-size:1rem; font-weight:800; color:var(--ink);">
            ⏳ Menunggu Persetujuan
            @if($pendingUsers->count() > 0)
                <span style="background:#fef9c3; color:#854d0e; font-size:0.72rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:999px; margin-left:0.5rem;">{{ $pendingUsers->count() }} baru</span>
            @endif
        </h2>
    </div>

    @if($pendingUsers->isEmpty())
        <p style="color:var(--ink-soft); font-size:0.875rem; text-align:center; padding:2rem 0;">Tidak ada pendaftaran baru yang menunggu persetujuan.</p>
    @else
        <div class="table-wrap">
            <table class="manifest">
                <thead>
                    <tr>
                        <th>Waktu Daftar</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr>
                            <td style="font-size:0.8rem; color:var(--ink-soft);">{{ $user->created_at->format('d M Y, H:i') }}</td>
                            <td style="font-weight:700;">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div style="display:flex; gap:0.5rem;">
                                    <form method="POST" action="{{ route('admin.users.approve', $user) }}" onsubmit="return confirm('Setujui akun {{ $user->name }}?')">
                                        @csrf
                                        <button type="submit" style="padding:0.35rem 0.85rem; background:#16a34a; color:#fff; font-size:0.78rem; font-weight:700; border:none; border-radius:8px; cursor:pointer;">
                                            ✅ Setujui
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user) }}" onsubmit="return confirm('Tolak & hapus akun {{ $user->name }}? Aksi ini tidak bisa dibatalkan.')">
                                        @csrf
                                        <button type="submit" style="padding:0.35rem 0.85rem; background:#dc2626; color:#fff; font-size:0.78rem; font-weight:700; border:none; border-radius:8px; cursor:pointer;">
                                            ❌ Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Approved Users --}}
<div class="card">
    <h2 style="font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:1rem;">✅ User Aktif ({{ $approvedUsers->count() }})</h2>
    @if($approvedUsers->isEmpty())
        <p style="color:var(--ink-soft); font-size:0.875rem; text-align:center; padding:2rem 0;">Belum ada user aktif.</p>
    @else
        <div class="table-wrap">
            <table class="manifest">
                <thead>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Jumlah Resi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedUsers as $user)
                        <tr>
                            <td style="font-size:0.8rem; color:var(--ink-soft);">{{ $user->created_at->format('d M Y') }}</td>
                            <td style="font-weight:700;">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->resis()->count() }} resi</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
