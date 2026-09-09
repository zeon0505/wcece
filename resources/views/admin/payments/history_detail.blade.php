@extends('layouts.admin')
@section('title', 'Detail History Pembayaran')

@section('content')
<style>
    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; }
    
    @media (max-width: 850px) {
        .detail-grid { grid-template-columns: 1fr !important; }
    }
</style>

<div style="margin-bottom:1.25rem;">
    <a href="{{ route('admin.payments.history') }}" style="color:var(--ink-soft); text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; font-size:0.85rem; font-weight:600; margin-bottom:0.5rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke History Pembayaran
    </a>
    <div>
        <h1 class="page-title" style="font-family:'Space Mono',monospace; font-size:1.25rem; word-break:break-all; margin:0;">{{ $payment->invoice?->invoice_number ?: 'Invoice #' . $payment->invoice_id }}</h1>
        <p class="page-subtitle" style="margin-top:0.15rem; font-size:0.85rem; color:var(--ink-soft);">Detail riwayat pembayaran &amp; rincian paket barang customer.</p>
    </div>
</div>

<div class="detail-grid">
    {{-- Left Column: Invoice & Payment Info --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem; min-width:0;">
        
        {{-- Payment Status Card --}}
        <div class="card" style="border-radius:16px; overflow:hidden;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
                <span style="font-weight:800; font-size:0.9rem;">Status Pembayaran</span>
                @if($payment->status === 'verified')
                    <span style="font-size:0.75rem; font-weight:700; background:#dcfce7; color:#15803d; padding:0.25rem 0.65rem; border-radius:20px;">✓ Terverifikasi / Paid</span>
                @elseif($payment->status === 'pending')
                    <span style="font-size:0.75rem; font-weight:700; background:#fef3c7; color:#d97706; padding:0.25rem 0.65rem; border-radius:20px;">⏳ Menunggu Verifikasi</span>
                @else
                    <span style="font-size:0.75rem; font-weight:700; background:#fee2e2; color:#b91c1c; padding:0.25rem 0.65rem; border-radius:20px;">✗ Ditolak</span>
                @endif
            </div>

            <div style="padding:1rem 1.1rem; display:flex; flex-direction:column; gap:0.6rem;">
                <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                    <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Customer</div>
                    <div style="font-size:0.85rem; font-weight:600; word-break:break-word; margin-top:0.1rem;">
                        {{ $payment->invoice?->user?->name ?: '-' }}
                        @if($payment->invoice?->user?->email)
                            <span style="color:var(--ink-soft); font-weight:400; font-size:0.78rem; display:block;">({{ $payment->invoice->user->email }})</span>
                        @endif
                    </div>
                </div>

                <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                    <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Nominal Bayar</div>
                    <div style="font-weight:800; color:#059669; font-size:1.1rem; margin-top:0.1rem;">
                        {{ $payment->invoice?->formatted_total ?: 'Rp ' . number_format($payment->amount ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.6rem;">
                    <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Metode</div>
                        <div style="font-size:0.85rem; font-weight:600; margin-top:0.1rem;">
                            {{ $payment->method === 'manual_transfer' ? 'Transfer Bank Manual' : $payment->method }}
                        </div>
                    </div>

                    <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Waktu Bayar</div>
                        <div style="font-size:0.82rem; font-weight:600; margin-top:0.1rem;">
                            {{ $payment->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                @if($payment->verified_at)
                    <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Diverifikasi Oleh</div>
                        <div style="font-size:0.85rem; font-weight:600; margin-top:0.1rem; word-break:break-word;">
                            {{ $payment->verifier?->name ?: 'Admin' }}
                            <span style="font-size:0.75rem; color:var(--ink-soft); font-weight:500; display:block;">({{ $payment->verified_at->format('d M Y, H:i') }})</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Proof File --}}
        <div class="card" style="border-radius:16px; overflow:hidden;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">📷 Bukti Transfer Customer</h3>
            </div>
            <div style="padding:1rem 1.1rem;">
                @if($payment->proof_file)
                    @php $ext = pathinfo($payment->proof_file, PATHINFO_EXTENSION); @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                        <img src="{{ Storage::url($payment->proof_file) }}" alt="Bukti Transfer"
                            style="width:100%; border-radius:10px; border:1px solid var(--line); cursor:pointer; object-fit:contain; max-height:350px;"
                            onclick="window.open(this.src, '_blank')">
                        <p style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.5rem; text-align:center;">Klik gambar untuk membuka ukuran penuh</p>
                    @else
                        <a href="{{ Storage::url($payment->proof_file) }}" target="_blank" style="display:inline-flex; align-items:center; gap:0.5rem; background:#eff6ff; color:#2563eb; padding:0.65rem 1rem; border-radius:8px; text-decoration:none; font-weight:700; font-size:0.85rem; width:100%; justify-content:center;">
                            📄 Lihat Dokumen Bukti (PDF / File)
                        </a>
                    @endif
                @else
                    <div style="background:#f8fafc; padding:1.5rem 1rem; text-align:center; border-radius:10px; border:1px solid var(--line); color:var(--ink-soft); font-size:0.85rem;">
                        Tidak ada file bukti transfer diupload.
                    </div>
                @endif
            </div>
        </div>

        {{-- Cost Breakdown --}}
        @if($payment->invoice)
        <div class="card" style="border-radius:16px; overflow:hidden;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">🧾 Rincian Perhitungan Tagihan</h3>
            </div>
            <div style="padding:1rem 1.1rem; display:flex; flex-direction:column; gap:0.5rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.85rem;">
                    <span style="color:var(--ink-soft);">Total Berat Resi</span>
                    <span style="font-weight:600;">{{ number_format($payment->invoice->total_weight_gram, 0, ',', '.') }} gram</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.85rem;">
                    <span style="color:var(--ink-soft);">Tarif / Gram</span>
                    <span style="font-weight:600;">Rp {{ number_format($payment->invoice->rate_per_gram, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.85rem;">
                    <span style="color:var(--ink-soft);">Handling Fee (Total)</span>
                    <span style="font-weight:600;">Rp {{ number_format($payment->invoice->handling_fee, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; border-top:1px solid var(--line); padding-top:0.6rem; font-weight:800; font-size:0.95rem; margin-top:0.2rem;">
                    <span>Total Dibayar</span>
                    <span style="color:#059669;">{{ $payment->invoice->formatted_total }}</span>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Right Column: Resi & Items Included --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem; min-width:0;">
        <div class="card" style="border-radius:16px; overflow:hidden;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.4rem;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">📦 Daftar Resi &amp; Barang</h3>
                <span style="font-size:0.72rem; background:#e0e7ff; color:#4f46e5; padding:0.2rem 0.55rem; border-radius:12px; font-weight:700;">
                    {{ $resis->count() }} Resi
                </span>
            </div>
            
            <div style="padding:1rem 1.1rem; display:flex; flex-direction:column; gap:0.75rem;">
                @forelse($resis as $resi)
                    <div style="background:#f8fafc; border:1px solid var(--line); border-radius:10px; padding:0.75rem; display:flex; align-items:center; gap:0.75rem;">
                        @if($resi->photo_item)
                            <img src="{{ Storage::url($resi->photo_item) }}" alt="Foto" style="width:46px; height:46px; border-radius:8px; object-fit:cover; border:1px solid var(--line); flex-shrink:0;">
                        @else
                            <div style="width:46px; height:46px; border-radius:8px; background:white; border:1px dashed var(--line); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--ink-soft); font-size:1rem;">
                                📦
                            </div>
                        @endif
                        <div style="flex:1; overflow:hidden;">
                            <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.85rem; color:var(--ink); word-break:break-all;">{{ $resi->resi_number }}</div>
                            <div style="font-size:0.8rem; font-weight:600; color:var(--ink); margin-top:0.1rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $resi->item_name ?: 'Tanpa nama barang' }}</div>
                            <div style="font-size:0.72rem; color:var(--ink-soft); margin-top:0.15rem;">
                                {{ $resi->quantity }} pcs &bull; {{ number_format($resi->final_weight_gram ?? 0, 0, ',', '.') }} g
                            </div>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <x-status-badge :status="$resi->status" />
                        </div>
                    </div>
                @empty
                    <div style="text-align:center; padding:1.5rem 0; color:var(--ink-soft); font-size:0.85rem;">
                        Tidak ada detail resi ditemukan untuk transaksi ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
