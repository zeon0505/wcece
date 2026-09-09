@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')

@section('content')
<style>
    .header-actions { margin-bottom: 1.5rem; }
</style>

<div class="header-actions">
    <h1 style="font-size:1.5rem; font-weight:800; color:var(--ink);">Verifikasi Pembayaran</h1>
    <p style="font-size:0.875rem; color:var(--ink-soft); margin-top:0.2rem;">Daftar pembayaran manual yang menunggu konfirmasi admin.</p>
</div>

<div style="background:var(--surface); border:1px solid var(--line); border-radius:14px; overflow:hidden;">
    <div style="overflow-x:auto; width:100%;">
        <table style="width:100%; border-collapse:collapse; min-width:650px;">
            <thead>
                <tr style="background:rgba(0,0,0,0.02); border-bottom:1px solid var(--line);">
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Invoice</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Customer</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Shipment</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Nominal</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Metode</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Bukti</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr style="border-bottom:1px dashed var(--line); transition:background 0.15s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.82rem; color:var(--ink);">{{ $payment->invoice->invoice_number }}</div>
                            <div style="font-size:0.72rem; color:var(--ink-soft); margin-top:0.2rem;">{{ $payment->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td style="padding:0.875rem 1rem;">
                            <div style="font-weight:600; font-size:0.875rem; white-space:nowrap;">{{ $payment->invoice->user->name }}</div>
                            <div style="font-size:0.72rem; color:var(--ink-soft); white-space:nowrap;">{{ $payment->invoice->user->email }}</div>
                        </td>
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            <span style="font-family:'Space Mono',monospace; font-size:0.8rem; color:#4f46e5; font-weight:700; background:#ede9fe; padding:0.2rem 0.5rem; border-radius:6px;">{{ $payment->invoice->masterShipment->code }}</span>
                        </td>
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            <span style="font-weight:800; font-size:0.95rem; color:var(--ink);">Rp {{ number_format($payment->invoice->total_amount, 0, ',', '.') }}</span>
                        </td>
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            @if($payment->method === 'manual_transfer')
                                <span style="font-size:0.72rem; font-weight:700; background:#fef3c7; color:#d97706; padding:0.25rem 0.65rem; border-radius:20px;">Transfer Manual</span>
                            @else
                                <span style="font-size:0.72rem; font-weight:700; background:#eff6ff; color:#2563eb; padding:0.25rem 0.65rem; border-radius:20px;">Gateway</span>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            @if($payment->proof_file)
                                <a href="{{ Storage::url($payment->proof_file) }}" target="_blank" style="color:#2563eb; font-size:0.78rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:0.25rem;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat Bukti
                                </a>
                            @else
                                <span style="color:var(--ink-soft); font-size:0.8rem;">-</span>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1rem; text-align:right; white-space:nowrap;">
                            <div style="display:inline-flex; gap:0.4rem; justify-content:flex-end;">
                                <form id="pay-verify-{{ $payment->id }}" method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                                    @csrf
                                    <button type="button" style="font-size:0.75rem; padding:0.35rem 0.75rem; background:#059669; color:#fff; border:none; border-radius:6px; font-weight:700; cursor:pointer;"
                                        onclick="gConfirm({icon:'✅',iconBg:'#dcfce7',title:'Verifikasi Pembayaran?',body:'Pembayaran ini akan ditandai sebagai lunas dan invoice diterbitkan.',btnText:'Ya, Verifikasi',btnColor:'#059669',form:document.getElementById('pay-verify-{{ $payment->id }}')})">
                                        ✓ Verifikasi
                                    </button>
                                </form>
                                <form id="pay-reject-{{ $payment->id }}" method="POST" action="{{ route('admin.payments.reject', $payment) }}">
                                    @csrf
                                    <button type="button" style="font-size:0.75rem; padding:0.35rem 0.75rem; background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:6px; font-weight:600; cursor:pointer;"
                                        onclick="gConfirm({icon:'❌',iconBg:'#fee2e2',title:'Tolak Pembayaran?',body:'Pembayaran ini akan ditolak dan user perlu upload ulang bukti bayar.',btnText:'Ya, Tolak',btnColor:'#dc2626',form:document.getElementById('pay-reject-{{ $payment->id }}')})">
                                        ✗ Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:3rem 1rem; color:var(--ink-soft);">
                            🎉 Tidak ada pembayaran yang perlu diverifikasi.
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
