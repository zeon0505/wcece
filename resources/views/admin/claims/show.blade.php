@extends('layouts.admin')
@section('title', 'Detail Klaim Resi')

@section('content')
<div class="page-header" style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
    <a href="{{ route('admin.claims.index') }}" style="color:var(--ink-soft); text-decoration:none; display:flex; align-items:center; gap:0.5rem; font-size:0.875rem;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <div>
        <h1 class="page-title">Detail Klaim Resi</h1>
        <p class="page-subtitle">Verifikasi pengajuan klaim dari customer.</p>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

    {{-- Left: Resi & Claim Info --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        {{-- Resi Info --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Info Resi Unclaimed</span>
                <span class="badge badge-china">Unclaimed</span>
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Nomor Resi</span>
                    <span class="info-value resi-number">{{ $claim->resi->resi_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">{{ $claim->resi->status_label ?? $claim->resi->status }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama di Data</span>
                    <span class="info-value">{{ $claim->resi->customer_name_snapshot }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tgl Dibuat</span>
                    <span class="info-value">{{ $claim->resi->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Foto Barang Unclaimed (dari Admin) --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">📦 Foto Barang Unclaimed</span>
                <span style="font-size:0.75rem; color:var(--ink-soft);">Diupload Admin</span>
            </div>
            <div class="card-body">
                @if($claim->resi->photo_item)
                    <img src="{{ Storage::url($claim->resi->photo_item) }}" alt="Foto Barang"
                        style="width:100%; border-radius:10px; border:1px solid var(--line); cursor:pointer;"
                        onclick="window.open(this.src, '_blank')">
                    <p style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.5rem; text-align:center;">Klik untuk buka di tab baru</p>
                @else
                    <div style="background:var(--surface); padding:2rem; text-align:center; border-radius:8px; color:var(--ink-soft);">
                        Tidak ada foto barang
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Customer Claim Detail --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        {{-- Customer Info --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">👤 Data Customer Pengaju</span>
                @if($claim->status === 'pending')
                    <span class="badge badge-pending-ver">Menunggu Verifikasi</span>
                @elseif($claim->status === 'approved')
                    <span class="badge badge-paid">Disetujui</span>
                @else
                    <span class="badge badge-unpaid">Ditolak</span>
                @endif
            </div>
            <div class="card-body">
                <div class="info-row">
                    <span class="info-label">Nama Akun</span>
                    <span class="info-value" style="font-weight:700;">{{ $claim->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $claim->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Username</span>
                    <span class="info-value" style="font-weight:700; color:#4f46e5;">{{ $claim->line_name ?: '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama Barang (Claim)</span>
                    <span class="info-value" style="font-weight:600; color:var(--ink);">{{ $claim->item_name ?: '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jumlah (Claim)</span>
                    <span class="info-value" style="font-weight:700;">{{ $claim->quantity ? $claim->quantity . ' pcs' : '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipe Pengiriman (Claim)</span>
                    <span class="info-value" style="font-weight:700;">{{ $claim->shipment_type ?: '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pickup Code</span>
                    <span class="info-value">
                        <span style="font-family:'Space Mono', monospace; font-weight:700; background:var(--surface); padding:2px 8px; border-radius:4px; border:1px solid var(--line);">{{ $claim->pickup_code }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Waktu Klaim</span>
                    <span class="info-value">{{ $claim->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Foto Bukti Pembelian --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">🧾 Foto Bukti Pembelian</span>
                <span style="font-size:0.75rem; color:var(--ink-soft);">Diupload Customer</span>
            </div>
            <div class="card-body">
                @if($claim->proof_photo)
                    @php $ext = pathinfo($claim->proof_photo, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                        <img src="{{ Storage::url($claim->proof_photo) }}" alt="Bukti Pembelian"
                            style="width:100%; border-radius:10px; border:1px solid var(--line); cursor:pointer;"
                            onclick="window.open(this.src, '_blank')">
                        <p style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.5rem; text-align:center;">Klik untuk buka di tab baru</p>
                    @else
                        <a href="{{ Storage::url($claim->proof_photo) }}" target="_blank" class="btn btn-blue" style="width:100%; justify-content:center;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            Lihat File PDF
                        </a>
                    @endif
                @else
                    <div style="background:var(--surface); padding:2rem; text-align:center; border-radius:8px; color:var(--ink-soft);">
                        Tidak ada foto bukti
                    </div>
                @endif
            </div>
        </div>

        {{-- Action Buttons --}}
        @if($claim->status === 'pending')
        <div class="card">
            <div class="card-body" style="display:flex; gap:1rem;">
                <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" style="flex:1;"
                    onsubmit="return confirm('Setujui klaim ini? Resi akan masuk ke dashboard user.');">
                    @csrf
                    <button type="submit" class="btn" style="width:100%; justify-content:center; background:#10b981; color:#fff; border-color:#10b981; font-size:1rem; padding:0.75rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Setujui Klaim
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.claims.reject', $claim) }}" style="flex:1;"
                    onsubmit="return confirm('Tolak klaim ini?');">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width:100%; justify-content:center; font-size:1rem; padding:0.75rem;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tolak Klaim
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
