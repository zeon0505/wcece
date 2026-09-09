@extends('layouts.admin')
@section('title', 'Verifikasi Klaim Resi')

@section('content')
<div class="page-header">
    <h1 class="page-title">Verifikasi Klaim Resi</h1>
    <p class="page-subtitle">Daftar pengajuan klaim untuk resi unclaimed dari customer.</p>
</div>


@if(session('error'))
    <div class="alert alert-error">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="table-wrap">
        <table class="manifest">
            <thead>
                <tr>
                    <th>Waktu Pengajuan</th>
                    <th>Nomor Resi</th>
                    <th>User Pengaju</th>
                    <th>Username</th>
                    <th>Pickup Code</th>
                    <th>Foto Bukti (Proof)</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                    <tr>
                        <td style="white-space:nowrap; font-size:0.8125rem;">
                            {{ $claim->created_at->format('d M Y, H:i') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.resis.show', $claim->resi) }}" style="font-family:'Space Mono', monospace; font-weight:700; color:var(--ink); text-decoration:none;">
                                {{ $claim->resi->resi_number }}
                            </a>
                        </td>
                        <td>
                            <div style="font-weight:600; color:var(--ink);">{{ $claim->user->name }}</div>
                            <div style="font-size:0.75rem; color:var(--ink-soft);">{{ $claim->user->email }}</div>
                        </td>
                        <td>
                            <span style="font-weight:600; color:var(--ink);">{{ $claim->line_name ?: '-' }}</span>
                        </td>
                        <td>
                            <span style="font-family:'Space Mono', monospace; font-size:0.8125rem; font-weight:700; background:var(--surface); padding:2px 6px; border-radius:4px; border:1px solid var(--line);">
                                {{ $claim->pickup_code }}
                            </span>
                        </td>
                        <td>
                            @if($claim->proof_photo)
                                <a href="{{ Storage::url($claim->proof_photo) }}" target="_blank" style="display:inline-flex; align-items:center; gap:0.25rem; font-size:0.75rem; font-weight:600; color:var(--blue-deep); text-decoration:none; background:var(--blue-mid); padding:4px 8px; border-radius:6px;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Lihat Bukti
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($claim->status === 'pending')
                                <span class="badge badge-pending-ver">Pending</span>
                            @elseif($claim->status === 'approved')
                                <span class="badge badge-paid">Disetujui</span>
                            @else
                                <span class="badge badge-unpaid">Ditolak</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                <a href="{{ route('admin.claims.show', $claim) }}" class="btn btn-outline btn-sm">Detail</a>
                                @if($claim->status === 'pending')
                                    <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" onsubmit="return confirm('Setujui klaim ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="background:#10b981; color:#fff;">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.claims.reject', $claim) }}" onsubmit="return confirm('Tolak klaim ini?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Tolak</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:2rem; color:var(--ink-soft);">
                            Tidak ada data pengajuan klaim saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1.5rem;">
    {{ $claims->links('pagination::bootstrap-4') }}
</div>
@endsection
