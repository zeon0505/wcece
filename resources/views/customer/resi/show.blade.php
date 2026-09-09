@extends('layouts.app')
@section('title', 'Detail Resi ' . $resi->resi_number)

@section('content')
<style>
    .resi-detail-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.25rem; align-items: start; }
    .badge-type { font-size:0.72rem; font-weight:800; padding:0.2rem 0.55rem; border-radius:6px; display:inline-block; }
    .badge-sea { background:#e0f2fe; color:#0284c7; }
    .badge-air { background:#ffedd5; color:#ea580c; }
    .badge-handcarry { background:#fce7f3; color:#db2777; }
    
    @media (max-width: 850px) {
        .resi-detail-grid { grid-template-columns: 1fr !important; }
    }
</style>

<div class="page-header" style="margin-bottom:1.25rem; padding-top:0.25rem;">
    <a href="{{ route('dashboard') }}" style="color:var(--ink-soft); text-decoration:none; font-size:0.85rem; display:inline-flex; align-items:center; gap:0.35rem; margin-bottom:0.6rem; font-weight:600;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Dashboard
    </a>
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
        <div>
            <h1 style="font-family:'Space Mono',monospace; font-size:1.25rem; font-weight:800; color:var(--ink); letter-spacing:-0.02em; word-break:break-all; margin:0;">{{ $resi->resi_number }}</h1>
            @if($resi->item_name)
                <p style="color:var(--ink-soft); font-size:0.85rem; margin-top:0.15rem;">{{ $resi->item_name }}</p>
            @endif
        </div>
        <div>
            <x-status-badge :status="$resi->status" />
        </div>
    </div>
</div>

{{-- Banner Konfirmasi Barang Diterima --}}
@if($resi->status === 'ready_to_ship')
    <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5); border:1.5px solid #a7f3d0; padding:1.25rem; border-radius:16px; margin-bottom:1.25rem;">
        <div style="font-weight:800; color:#065f46; font-size:0.95rem; margin-bottom:0.3rem;">🎁 Paket Sudah Anda Terima di Rumah?</div>
        <p style="font-size:0.82rem; color:#047857; margin:0 0 1rem 0; line-height:1.4;">
            Silakan konfirmasi bahwa barang telah sampai dan unggah foto bukti penerimaan (unboxing/paket).
        </p>

        <form method="POST" action="{{ route('resi.confirm-received', $resi) }}" enctype="multipart/form-data">
            @csrf
            <div style="display:flex; flex-direction:column; gap:0.6rem;">
                <div style="background:white; border:1px solid #a7f3d0; border-radius:10px; padding:0.75rem;">
                    <label style="font-size:0.75rem; font-weight:700; color:#065f46; display:block; margin-bottom:0.4rem; text-transform:uppercase;">📷 Upload Foto Paket Diterima *</label>
                    <input type="file" name="photo_received" accept="image/*" required style="width:100%; font-size:0.8rem; background:white; padding:4px;">
                    @error('photo_received')
                        <p style="color:#dc2626; font-size:0.78rem; margin-top:0.25rem;">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" style="background:#10b981; color:white; border:none; padding:0.7rem 1.25rem; border-radius:10px; font-weight:800; font-size:0.875rem; cursor:pointer; width:100%; box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                    ✓ Konfirmasi Barang Diterima
                </button>
            </div>
        </form>
    </div>
@elseif($resi->status === 'completed' || $resi->photo_received)
    <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; padding:1.1rem; border-radius:16px; margin-bottom:1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
            <div>
                <div style="font-weight:800; color:#15803d; font-size:0.9rem;">✓ Paket Berhasil Diterima Customer</div>
                @if($resi->received_at)
                    <div style="font-size:0.78rem; color:#166534; margin-top:0.15rem;">Dikonfirmasi pada {{ $resi->received_at->format('d M Y, H:i') }}</div>
                @endif
            </div>
            <span style="font-size:0.75rem; background:#dcfce7; color:#15803d; padding:0.25rem 0.65rem; border-radius:20px; font-weight:700;">✓ Selesai</span>
        </div>
    </div>
@endif

{{-- Awaiting payment banner --}}
@if($resi->status === 'awaiting_payment')
    @php $invoice = auth()->user()->invoices()->where('master_shipment_id', $resi->master_shipment_id)->first(); @endphp
    @if($invoice && $invoice->status === 'unpaid')
        <div style="background:#fef3c7; border:1px solid #fde68a; padding:0.875rem 1rem; border-radius:14px; display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; gap:0.5rem;">
            <div>
                <div style="font-weight:800; color:#92400e; font-size:0.85rem;">Tagihan Menunggu Pembayaran</div>
                <div style="font-size:0.8rem; color:#a16207; margin-top:0.15rem;">Total: <strong>{{ $invoice->formatted_total }}</strong></div>
            </div>
            <a href="{{ route('invoices.show', $invoice) }}" style="display:inline-flex; align-items:center; gap:0.35rem; background:#d97706; color:#fff; padding:0.45rem 0.85rem; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none;">
                Lihat Tagihan &rarr;
            </a>
        </div>
    @elseif(!$invoice && $resi->status === 'awaiting_payment')
        <div style="background:#fef3c7; border:1px solid #fde68a; padding:0.875rem 1rem; border-radius:14px; margin-bottom:1.25rem;">
            <div style="font-weight:800; color:#92400e; font-size:0.85rem;">Menunggu Tagihan (Sedang Diproses)</div>
            <div style="font-size:0.78rem; color:#a16207; margin-top:0.15rem;">Admin sedang membuatkan detail tagihan untuk resi ini.</div>
        </div>
    @endif
@endif

<div class="resi-detail-grid">

    {{-- Left: Detail & Photos --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem; min-width:0;">
        {{-- Info Paket Card --}}
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); box-shadow:0 4px 15px rgba(0,0,0,0.02); overflow:hidden;">
            <div style="padding:0.85rem 1.1rem; border-bottom:1px solid var(--line); background:#fafafa;">
                <span style="font-size:0.9rem; font-weight:800; color:var(--ink);">Info Paket</span>
            </div>
            <div style="padding:1rem 1.1rem;">
                <div style="display:flex; flex-direction:column; gap:0.75rem;">
                    {{-- Nomor Resi --}}
                    <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem;">Nomor Resi</div>
                        <div style="font-family:'Space Mono',monospace; font-weight:700; color:var(--ink); font-size:0.9rem; word-break:break-all;">{{ $resi->resi_number }}</div>
                    </div>

                    {{-- Nama Barang --}}
                    <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem;">Nama Barang</div>
                        <div style="font-weight:600; color:var(--ink); font-size:0.875rem;">{{ $resi->item_name ?: '—' }}</div>
                    </div>

                    {{-- Qty & Tipe --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
                        <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem;">Jumlah Qty</div>
                            <div style="font-weight:700; color:var(--ink); font-size:0.875rem;">{{ $resi->quantity }} pcs</div>
                        </div>
                        <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Tipe</div>
                            @php
                                $shipType = strtoupper($resi->shipment_type ?? 'SEA');
                                $typeClass = match($shipType) {
                                    'AIR' => 'badge-air',
                                    'HANDCARRY' => 'badge-handcarry',
                                    default => 'badge-sea'
                                };
                                $typeLabel = match($shipType) {
                                    'AIR' => '✈️ AIR',
                                    'HANDCARRY' => '🛍️ HC',
                                    default => '🚢 SEA'
                                };
                            @endphp
                            <div><span class="badge-type {{ $typeClass }}">{{ $typeLabel }}</span></div>
                        </div>
                    </div>

                    @if($resi->final_weight_gram)
                        <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem;">Berat Akhir</div>
                            <div style="font-weight:700; color:var(--ink); font-size:0.875rem;">{{ number_format($resi->final_weight_gram, 0, ',', '.') }} gram</div>
                        </div>
                    @endif

                    @if($resi->masterShipment)
                        <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem;">Master Shipment</div>
                            <div style="font-family:'Space Mono',monospace; font-weight:700; color:#4f46e5; font-size:0.85rem;">{{ $resi->masterShipment->code }}</div>
                        </div>
                    @endif

                    <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem;">Didaftarkan</div>
                        <div style="font-weight:600; color:var(--ink); font-size:0.82rem;">{{ $resi->created_at->format('d M Y, H:i') }}</div>
                    </div>

                    @if($resi->notes)
                        <div style="padding:0.75rem 0.9rem; background:#f8fafc; border-radius:10px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.15rem;">Catatan</div>
                            <div style="font-weight:500; color:var(--ink); font-size:0.85rem; white-space:pre-line;">{{ $resi->notes }}</div>
                        </div>
                    @endif

                    @if($resi->proof_co)
                        <div style="padding:0.75rem 0.9rem; background:#eff6ff; border-radius:10px; border:1px solid #bfdbfe;">
                            <div style="font-size:0.68rem; font-weight:700; color:#1d4ed8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.25rem;">Bukti Checkout / Proof CO</div>
                            @php $isPdf = Str::endsWith(strtolower($resi->proof_co), '.pdf'); @endphp
                            @if($isPdf)
                                <a href="{{ Storage::url($resi->proof_co) }}" target="_blank" style="color:#2563eb; font-weight:700; font-size:0.82rem; display:inline-flex; align-items:center; gap:0.3rem; text-decoration:underline;">📄 Download/Lihat Bukti CO (PDF)</a>
                            @else
                                <a href="{{ Storage::url($resi->proof_co) }}" target="_blank" style="color:#2563eb; font-weight:700; font-size:0.82rem; display:inline-flex; align-items:center; gap:0.3rem; text-decoration:underline;">🖼️ Lihat Bukti Checkout (Gambar)</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Photos --}}
        @php $hasBoxPhoto = $resi->masterShipment?->photo_box; @endphp
        @if($resi->photo_item || $resi->photo_wh_china || $hasBoxPhoto || $resi->photo_arrived_id || $resi->photo_received)
            <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); box-shadow:0 4px 15px rgba(0,0,0,0.02); overflow:hidden;">
                <div style="padding:0.85rem 1.1rem; border-bottom:1px solid var(--line); background:#fafafa;">
                    <span style="font-size:0.9rem; font-weight:800; color:var(--ink);">Foto Paket</span>
                </div>
                <div style="padding:1rem; display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:0.875rem;">
                    @if($resi->photo_item)
                        <div>
                            <p style="font-size:0.72rem; font-weight:700; color:var(--ink-soft); margin-bottom:0.4rem;">📷 Foto Referensi</p>
                            <img src="{{ Storage::url($resi->photo_item) }}" alt="Foto referensi barang" style="width:100%; border-radius:10px; border:1.5px solid var(--line); object-fit:cover; aspect-ratio:4/3; cursor:pointer;" onclick="window.open(this.src, '_blank')">
                        </div>
                    @endif
                    @if($resi->photo_wh_china)
                        <div>
                            <p style="font-size:0.72rem; font-weight:700; color:var(--ink-soft); margin-bottom:0.4rem;">📦 Gudang China</p>
                            <img src="{{ Storage::url($resi->photo_wh_china) }}" alt="Foto gudang China" style="width:100%; border-radius:10px; border:1.5px solid var(--line); object-fit:cover; aspect-ratio:4/3; cursor:pointer;" onclick="window.open(this.src, '_blank')">
                        </div>
                    @endif
                    @if($hasBoxPhoto)
                        <div>
                            <p style="font-size:0.72rem; font-weight:700; color:#4f46e5; margin-bottom:0.4rem;">🚀 Foto Box Pengiriman</p>
                            <img src="{{ Storage::url($resi->masterShipment->photo_box) }}" alt="Foto box pengiriman"
                                style="width:100%; border-radius:10px; border:1.5px solid #c7d2fe; object-fit:cover; aspect-ratio:4/3; cursor:pointer;"
                                onclick="window.open(this.src, '_blank')">
                            @if($resi->masterShipment->photo_box_uploaded_at)
                                <p style="font-size:0.68rem; color:#6366f1; margin-top:0.25rem; text-align:center;">{{ $resi->masterShipment->photo_box_uploaded_at->format('d M Y') }}</p>
                            @endif
                        </div>
                    @endif
                    @if($resi->photo_arrived_id)
                        <div>
                            <p style="font-size:0.72rem; font-weight:700; color:var(--ink-soft); margin-bottom:0.4rem;">🇮🇩 Tiba Indonesia</p>
                            <img src="{{ Storage::url($resi->photo_arrived_id) }}" alt="Foto tiba Indonesia" style="width:100%; border-radius:10px; border:1.5px solid var(--line); object-fit:cover; aspect-ratio:4/3; cursor:pointer;" onclick="window.open(this.src, '_blank')">
                        </div>
                    @endif
                    @if($resi->photo_received)
                        <div>
                            <p style="font-size:0.72rem; font-weight:700; color:#15803d; margin-bottom:0.4rem;">🎁 Foto Diterima</p>
                            <img src="{{ Storage::url($resi->photo_received) }}" alt="Foto diterima customer" style="width:100%; border-radius:10px; border:1.5px solid #bbf7d0; object-fit:cover; aspect-ratio:4/3; cursor:pointer;" onclick="window.open(this.src, '_blank')">
                        </div>
                    @endif
                </div>
            </div>
        @endif

    </div>

    {{-- Right: Timeline --}}
    <div style="min-width:0;">
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); box-shadow:0 4px 15px rgba(0,0,0,0.02); overflow:hidden;">
            <div style="padding:0.85rem 1.1rem; border-bottom:1px solid var(--line); background:#fafafa;">
                <span style="font-size:0.9rem; font-weight:800; color:var(--ink);">Timeline Status</span>
            </div>
            <div style="padding:1rem 1.1rem;">
                <x-tracking-stepper :history="$resi->statusHistories" :current-status="$resi->status" />
            </div>
        </div>
    </div>

</div>
@endsection
