@extends('layouts.admin')
@section('title', 'History Pembayaran')

@section('content')
<style>
    .header-actions { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .filter-card { background: var(--surface); border: 1px solid var(--line); border-radius: 14px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
    .filter-input-wrap { flex: 1; min-width: 220px; position: relative; }
    .filter-select { padding: 0.5rem 1rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.875rem; background: var(--bg); color: var(--ink); outline: none; min-width: 160px; }
    
    @media (max-width: 640px) {
        .filter-input-wrap { min-width: 100%; }
        .filter-select { width: 100%; }
        .filter-card button { width: 100%; justify-content: center; }
    }
</style>

<div class="header-actions">
    <div>
        <h1 style="font-size:1.4rem; font-weight:800; color:var(--ink); margin:0;">History Pembayaran &amp; Transaksi</h1>
        <p style="font-size:0.85rem; color:var(--ink-soft); margin-top:0.2rem;">Riwayat seluruh pembayaran, tagihan, dan daftar resi barang customer.</p>
    </div>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('admin.payments.history') }}">
    <div class="filter-card">
        <div class="filter-input-wrap">
            <svg style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:var(--ink-soft);" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice, nama customer, atau box shipment..." style="width:100%; padding:0.5rem 1rem 0.5rem 2.25rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem; background:var(--bg); color:var(--ink); outline:none;">
        </div>
        <select name="status" class="filter-select">
            <option value="">Semua Status</option>
            <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified (Disetujui)</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
        </select>
        <button type="submit" style="background:var(--ink); color:#fff; font-size:0.875rem; font-weight:600; padding:0.5rem 1.25rem; border:none; border-radius:8px; cursor:pointer;">Filter</button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.payments.history') }}" style="font-size:0.875rem; color:var(--ink-soft); text-decoration:none; padding:0.5rem 0.75rem;">Reset</a>
        @endif
    </div>
</form>

<div class="card" style="border-radius:16px; overflow:hidden;">
    <div style="overflow-x:auto; width:100%;">
        <table style="width:100%; border-collapse:collapse; min-width:700px;">
            <thead>
                <tr style="border-bottom:1px solid var(--line); background:#fafafa;">
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Tanggal &amp; Invoice</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Customer</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Resi &amp; Barang</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Total Nominal</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Status</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    @php
                        $invoice = $payment->invoice;
                        $resis = $invoice && $invoice->masterShipment ? $invoice->masterShipment->resis->where('user_id', $invoice->user_id) : collect();
                    @endphp
                    <tr style="border-bottom:1px dashed var(--line); transition:background 0.15s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.85rem 1rem; white-space:nowrap;">
                            <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.82rem; color:var(--ink);">{{ $invoice?->invoice_number ?: 'INV-#' . $payment->invoice_id }}</div>
                            <div style="font-size:0.72rem; color:var(--ink-soft); margin-top:0.15rem;">{{ $payment->created_at->format('d M Y, H:i') }}</div>
                        </td>
                        <td style="padding:0.85rem 1rem; white-space:nowrap;">
                            @if($invoice?->user)
                                <div style="font-weight:600; font-size:0.85rem;">{{ $invoice->user->name }}</div>
                                <div style="font-size:0.72rem; color:var(--ink-soft);">{{ $invoice->user->email }}</div>
                            @else
                                <div style="font-size:0.8rem; color:var(--ink-soft);">—</div>
                            @endif
                        </td>
                        <td style="padding:0.85rem 1rem;">
                            <div style="font-size:0.82rem; font-weight:700; color:#4f46e5; margin-bottom:0.15rem;">
                                📦 {{ $invoice?->masterShipment?->name ?: ($invoice?->masterShipment?->code ?: '-') }}
                            </div>
                            @if($resis->count() > 0)
                                <div style="font-size:0.75rem; color:var(--ink); white-space:nowrap;">
                                    {{ $resis->count() }} Resi: {{ Str::limit($resis->pluck('resi_number')->implode(', '), 30) }}
                                </div>
                            @else
                                <div style="font-size:0.75rem; color:var(--ink-soft);">—</div>
                            @endif
                        </td>
                        <td style="padding:0.85rem 1rem; white-space:nowrap;">
                            <div style="font-weight:800; font-size:0.9rem; color:var(--ink);">{{ $invoice?->formatted_total ?: 'Rp ' . number_format($payment->amount ?? 0, 0, ',', '.') }}</div>
                            <div style="font-size:0.72rem; color:var(--ink-soft);">{{ $payment->method === 'manual_transfer' ? 'Transfer Manual' : $payment->method }}</div>
                        </td>
                        <td style="padding:0.85rem 1rem; white-space:nowrap;">
                            @if($payment->status === 'verified')
                                <span style="font-size:0.72rem; font-weight:700; background:#dcfce7; color:#15803d; padding:0.25rem 0.6rem; border-radius:20px; white-space:nowrap;">✓ Terverifikasi</span>
                            @elseif($payment->status === 'pending')
                                <span style="font-size:0.72rem; font-weight:700; background:#fef3c7; color:#d97706; padding:0.25rem 0.6rem; border-radius:20px; white-space:nowrap;">⏳ Menunggu</span>
                            @else
                                <span style="font-size:0.72rem; font-weight:700; background:#fee2e2; color:#b91c1c; padding:0.25rem 0.6rem; border-radius:20px; white-space:nowrap;">✗ Ditolak</span>
                            @endif
                        </td>
                        <td style="padding:0.85rem 1rem; text-align:right; white-space:nowrap;">
                            <a href="{{ route('admin.payments.history.detail', $payment) }}" style="font-size:0.78rem; padding:0.4rem 0.85rem; background:white; border:1.5px solid var(--line); border-radius:8px; text-decoration:none; color:var(--ink); font-weight:700; display:inline-flex; align-items:center; gap:0.3rem; white-space:nowrap; box-shadow:0 2px 5px rgba(0,0,0,0.03);">
                                Detail ↗
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:3rem 1rem; color:var(--ink-soft);">
                            Tidak ada riwayat pembayaran ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
        <div style="padding:1rem 1.25rem; border-top:1px dashed var(--line);">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
