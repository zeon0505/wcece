@extends('layouts.app')
@section('title', 'Detail Tagihan ' . $invoice->invoice_number)

@section('content')
<style>
    .invoice-show-grid { display: grid; grid-template-columns: 1fr 360px; gap: 1.25rem; align-items: start; }
    .invoice-header-flex { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem; }
    
    @media (max-width: 850px) {
        .invoice-show-grid { grid-template-columns: 1fr !important; }
    }
</style>

{{-- Proof submitted success overlay --}}
@if(session('success'))
<div id="successOverlay" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); backdrop-filter:blur(8px); z-index:9999; display:flex; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:24px; padding:2.5rem 1.5rem; text-align:center; max-width:400px; width:90%; box-shadow:0 25px 50px rgba(0,0,0,0.3); animation:slideUp 0.5s cubic-bezier(0.34,1.56,0.64,1) both;">
        <div style="width:80px; height:80px; margin:0 auto 1.25rem; position:relative;">
            <svg viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%; height:100%;">
                <circle cx="45" cy="45" r="42" fill="#dcfce7" stroke="#16a34a" stroke-width="3"/>
                <path d="M25 45 L38 58 L65 30" stroke="#16a34a" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"
                    style="stroke-dasharray:60; stroke-dashoffset:60; animation:drawCheck 0.6s 0.3s ease forwards;"/>
            </svg>
        </div>
        <h2 style="font-size:1.35rem; font-weight:800; color:#111827; margin-bottom:0.4rem;">Bukti Terkirim! 🎉</h2>
        <p style="font-size:0.9rem; color:#6b7280; margin-bottom:1.5rem; line-height:1.5;">{{ session('success') }}</p>
        <button onclick="document.getElementById('successOverlay').style.display='none';"
            style="background:linear-gradient(135deg, #10b981, #059669); color:white; border:none; border-radius:12px; padding:12px 28px; font-weight:800; font-size:0.95rem; cursor:pointer; width:100%;">
            Lihat Status Tagihan
        </button>
    </div>
</div>
<style>
@keyframes slideUp { from { opacity:0; transform:translateY(40px) scale(0.9); } to { opacity:1; transform:translateY(0) scale(1); } }
@keyframes drawCheck { to { stroke-dashoffset: 0; } }
</style>
@endif

<div style="margin-bottom:1.25rem; padding-top:0.25rem;">
    <a href="{{ route('invoices.index') }}" style="color:var(--ink-soft); text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; font-size:0.85rem; font-weight:600; margin-bottom:0.5rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Tagihan
    </a>
    <div class="invoice-header-flex">
        <div>
            <h1 class="page-title" style="font-family:'Space Mono',monospace; font-size:1.25rem; word-break:break-all; margin:0;">{{ $invoice->invoice_number }}</h1>
            <p class="page-subtitle" style="font-size:0.85rem; color:var(--ink-soft); margin-top:0.2rem;">Diterbitkan pada {{ $invoice->created_at->format('d M Y') }}</p>
        </div>
        <div>
            @if($invoice->status === 'paid')
                <span class="badge" style="background:#dcfce7; color:#16a34a; font-size:0.85rem; padding:0.4rem 0.8rem; border-radius:8px; font-weight:800;">✅ LUNAS</span>
            @elseif($invoice->status === 'pending_verification')
                <span class="badge" style="background:#fef3c7; color:#d97706; font-size:0.85rem; padding:0.4rem 0.8rem; border-radius:8px; font-weight:800;">⏳ MENUNGGU VERIFIKASI</span>
            @else
                <span class="badge" style="background:#fee2e2; color:#dc2626; font-size:0.85rem; padding:0.4rem 0.8rem; border-radius:8px; font-weight:800;">💳 BELUM BAYAR</span>
            @endif
        </div>
    </div>
</div>

<div class="invoice-show-grid">
    {{-- Left: Rincian Resi --}}
    <div class="card" style="border-radius:16px; min-width:0; overflow:hidden;">
        <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa;">
            <h3 style="font-size:0.9rem; font-weight:800; margin:0;">Rincian Paket &amp; Biaya</h3>
        </div>
        <div style="padding:1rem 1.1rem;">
            <div style="width:100%; overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; min-width:320px; margin-bottom:1.25rem;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--line);">
                            <th style="padding:0.6rem 0; text-align:left; font-size:0.75rem; color:var(--ink-soft); font-weight:700; text-transform:uppercase;">No Resi / Tracking</th>
                            <th style="padding:0.6rem 0; text-align:left; font-size:0.75rem; color:var(--ink-soft); font-weight:700; text-transform:uppercase;">Item</th>
                            <th style="padding:0.6rem 0; text-align:right; font-size:0.75rem; color:var(--ink-soft); font-weight:700; text-transform:uppercase;">Berat</th>
                            <th style="padding:0.6rem 0; text-align:right; font-size:0.75rem; color:var(--ink-soft); font-weight:700; text-transform:uppercase;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resis as $resi)
                            @php $subtotal = $resi->final_weight_gram * $invoice->masterShipment->rate_per_gram; @endphp
                            <tr style="border-bottom:1px dashed #e2e8f0;">
                                <td style="padding:0.75rem 0;">
                                    <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.85rem; word-break:break-all;">{{ $resi->resi_number }}</div>
                                </td>
                                <td style="padding:0.75rem 0; font-size:0.85rem;">{{ $resi->item_name ?: '-' }}</td>
                                <td style="padding:0.75rem 0; text-align:right; font-size:0.85rem; white-space:nowrap;">{{ number_format($resi->final_weight_gram, 0, ',', '.') }} g</td>
                                <td style="padding:0.75rem 0; text-align:right; font-weight:700; font-size:0.85rem; white-space:nowrap;">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display:flex; justify-content:flex-end;">
                <div style="width:100%; max-width:320px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.85rem;">
                        <span style="color:var(--ink-soft); font-weight:600;">Rate/gram</span>
                        <span style="font-weight:600;">Rp {{ number_format($invoice->masterShipment->rate_per_gram, 0, ',', '.') }} / gram</span>
                    </div>
                    @if($invoice->masterShipment->handling_fee > 0)
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.85rem;">
                            <span style="color:var(--ink-soft); font-weight:600;">Handling Fee</span>
                            <span style="font-weight:600;">Rp {{ number_format($invoice->masterShipment->handling_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($invoice->masterShipment->packing_fee > 0)
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem; font-size:0.85rem;">
                            <span style="color:var(--ink-soft); font-weight:600;">Biaya Packing</span>
                            <span style="font-weight:600;">Rp {{ number_format($invoice->masterShipment->packing_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div style="display:flex; justify-content:space-between; border-top:2px solid var(--ink); padding-top:0.75rem; margin-top:0.5rem;">
                        <span style="font-weight:800; font-size:1rem;">TOTAL</span>
                        <span style="font-weight:800; font-size:1.15rem; color:#4f46e5;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Pembayaran --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem; min-width:0;">

        @if($invoice->status === 'unpaid' || $invoice->status === 'failed')
            {{-- Bank account info --}}
            <div style="background:linear-gradient(135deg,#eff6ff,#f0fdf4); border:1.5px solid #bfdbfe; border-radius:16px; padding:1.1rem;">
                <div style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#1d4ed8; margin-bottom:0.75rem;">📋 Transfer ke Rekening Berikut</div>
                <div style="display:flex; flex-direction:column; gap:0.6rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; background:white; border-radius:10px; padding:0.65rem 0.85rem; border:1px solid #e2e8f0;">
                        <span style="font-size:0.78rem; color:var(--ink-soft); font-weight:600;">Bank</span>
                        <span style="font-weight:800; font-size:0.95rem; color:var(--ink);">{{ $bankName }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; background:white; border-radius:10px; padding:0.65rem 0.85rem; border:1px solid #e2e8f0;">
                        <span style="font-size:0.78rem; color:var(--ink-soft); font-weight:600;">No. Rekening</span>
                        <div style="display:flex; align-items:center; gap:0.4rem;">
                            <span id="acc-number" style="font-weight:800; font-size:0.95rem; font-family:'Space Mono',monospace; color:var(--ink);">{{ $bankNumber }}</span>
                            <button onclick="navigator.clipboard.writeText('{{ $bankNumber }}'); this.textContent='✓ Copied'; setTimeout(()=>this.textContent='Copy',2000);"
                                style="font-size:0.75rem; background:#e0e7ff; color:#4f46e5; border:none; border-radius:6px; padding:0.25rem 0.5rem; cursor:pointer; font-weight:700;">Copy</button>
                        </div>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; background:white; border-radius:10px; padding:0.65rem 0.85rem; border:1px solid #e2e8f0;">
                        <span style="font-size:0.78rem; color:var(--ink-soft); font-weight:600;">A/N</span>
                        <span style="font-weight:700; font-size:0.875rem; color:var(--ink);">{{ $bankHolder }}</span>
                    </div>
                    <div style="background:#fef3c7; border-radius:10px; padding:0.75rem 0.85rem; text-align:center; border:1px solid #fde68a;">
                        <div style="font-size:0.72rem; color:#92400e; font-weight:700; text-transform:uppercase;">Jumlah Transfer</div>
                        <div style="font-size:1.3rem; font-weight:900; color:#d97706; margin-top:0.15rem;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            {{-- Upload proof form --}}
            <div class="card" style="border-radius:16px; border:2px solid var(--ink);">
                <div style="padding:1.1rem;">
                    <h3 style="font-size:0.95rem; font-weight:800; margin-bottom:0.3rem; color:var(--ink);">Upload Bukti Transfer</h3>
                    <p style="font-size:0.78rem; color:var(--ink-soft); margin-bottom:1rem; line-height:1.4;">Setelah melakukan transfer, upload screenshot / foto bukti transfer di sini. Admin akan memverifikasi dalam 1x24 jam.</p>

                    @if($errors->any())
                        <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; padding:0.6rem 0.85rem; border-radius:8px; font-size:0.8rem; margin-bottom:0.85rem;">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('invoices.manual-proof', $invoice) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="border:2px dashed var(--line); border-radius:12px; padding:1.1rem; text-align:center; background:#fafafa; margin-bottom:0.85rem;">
                            <input type="file" name="proof_file" id="proof_file" accept="image/*,.pdf" style="display:none;"
                                onchange="document.getElementById('proof-name').textContent = this.files[0] ? this.files[0].name : 'Pilih File';">
                            <label for="proof_file" style="display:inline-flex; align-items:center; gap:0.4rem; background:var(--ink); color:#fff; padding:0.5rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:700; cursor:pointer; margin-bottom:0.4rem;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Pilih Foto/PDF
                            </label>
                            <p id="proof-name" style="font-size:0.78rem; color:var(--ink-soft); margin:0;">Pilih File (JPG, PNG, PDF, maks 5MB)</p>
                        </div>
                        <button type="submit" style="width:100%; padding:0.75rem; background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-weight:800; font-size:0.9rem; border:none; border-radius:10px; cursor:pointer; box-shadow:0 4px 15px rgba(16,185,129,0.25);">
                            Kirim Bukti Transfer →
                        </button>
                    </form>
                </div>
            </div>

        @elseif($invoice->status === 'pending_verification')
            <div class="card" style="border-radius:16px; text-align:center; padding:1.5rem 1.1rem;">
                <svg width="48" height="48" fill="none" stroke="#d97706" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 style="font-size:1rem; font-weight:800; margin-bottom:0.4rem;">Menunggu Verifikasi Admin</h3>
                <p style="font-size:0.8rem; color:var(--ink-soft); line-height:1.5;">Bukti pembayaran Anda sudah diterima. Admin akan memverifikasi dan mengkonfirmasi dalam 1x24 jam.</p>
                @if($invoice->payment && $invoice->payment->proof_file)
                    <a href="{{ Storage::url($invoice->payment->proof_file) }}" target="_blank"
                        style="display:inline-flex; align-items:center; gap:0.4rem; margin-top:1rem; font-size:0.8rem; color:var(--blue-deep); font-weight:700; text-decoration:none; background:#eff6ff; padding:0.45rem 0.85rem; border-radius:8px;">
                        Lihat Bukti Terkirim →
                    </a>
                @endif
            </div>

        @elseif($invoice->status === 'paid')
            <div class="card" style="border-radius:16px; text-align:center; padding:1.5rem 1.1rem; background:linear-gradient(135deg, rgba(220,252,231,0.5), rgba(255,255,255,0.8));">
                <svg width="48" height="48" fill="none" stroke="#16a34a" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 0.75rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 style="font-size:1rem; font-weight:800; margin-bottom:0.4rem; color:#16a34a;">Pembayaran Lunas ✅</h3>
                <p style="font-size:0.8rem; color:var(--ink-soft); line-height:1.5;">Terima kasih! Pembayaran Anda telah diverifikasi oleh Admin. Paket Anda sedang dipersiapkan untuk pengiriman.</p>
                <div style="background:#f0fdf4; border:1px solid #bbf7d0; padding:0.75rem; border-radius:8px; margin-top:0.85rem; font-size:0.78rem; color:#15803d; font-weight:600;">
                    Diverifikasi pada: {{ optional($invoice->payment)->verified_at ? $invoice->payment->verified_at->format('d M Y, H:i') : '-' }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
