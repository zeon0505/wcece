@extends('layouts.admin')
@section('title', 'Detail Resi ' . $resi->resi_number)

@section('content')
<style>
    .admin-resi-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; align-items: start; }
    .header-flex { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem; }
    
    @media (max-width: 850px) {
        .admin-resi-grid { grid-template-columns: 1fr !important; }
    }
</style>

<div style="margin-bottom:1.25rem;">
    <a href="{{ route('admin.resis.index') }}" style="color:var(--ink-soft); text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; font-size:0.85rem; font-weight:600; margin-bottom:0.5rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali
    </a>
    <div class="header-flex">
        <div>
            <h1 class="page-title" style="font-family:'Space Mono',monospace; font-size:1.25rem; word-break:break-all; margin:0;">{{ $resi->resi_number }}</h1>
            <p class="page-subtitle" style="margin-top:0.2rem; font-size:0.85rem; word-break:break-word;">{{ $resi->item_name ?: 'Tanpa nama barang' }} &bull; {{ $resi->quantity }} pcs</p>
        </div>
        <div>
            <x-status-badge :status="$resi->status" />
        </div>
    </div>
</div>

<div class="admin-resi-grid">
    {{-- Left Column --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem; min-width:0;">
        {{-- Info Resi --}}
        <div class="card" style="border-radius:16px;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">Info Paket</h3>
            </div>
            <div style="padding:0.85rem 1.1rem;">
                <div style="display:flex; flex-direction:column; gap:0.6rem;">
                    <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Customer</div>
                        <div style="font-size:0.85rem; font-weight:600; word-break:break-word; margin-top:0.1rem;">{{ $resi->user ? $resi->user->name : ('Unmatched – ' . $resi->customer_name_snapshot) }}</div>
                    </div>
                    <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Nama Barang</div>
                        <div style="font-size:0.85rem; margin-top:0.1rem;">{{ $resi->item_name ?: '-' }}</div>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.6rem;">
                        <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Jumlah (Qty)</div>
                            <div style="font-size:0.85rem; font-weight:700; margin-top:0.1rem;">{{ $resi->quantity }} pcs</div>
                        </div>
                        <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Berat Final</div>
                            <div style="font-size:0.85rem; font-weight:700; margin-top:0.1rem;">{{ $resi->final_weight_gram ? number_format($resi->final_weight_gram, 0, ',', '.') . ' g' : '-' }}</div>
                        </div>
                    </div>
                    <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                        <div style="font-size:0.68rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Master Shipment</div>
                        <div style="margin-top:0.1rem;">
                            @if($resi->masterShipment)
                                <a href="{{ route('admin.shipments.show', $resi->masterShipment) }}" style="font-family:'Space Mono',monospace; font-size:0.85rem; color:var(--blue-deep); font-weight:700; text-decoration:none;">{{ $resi->masterShipment->code }}</a>
                            @else
                                <span style="font-size:0.85rem; color:var(--ink-soft);">Belum digabung</span>
                            @endif
                        </div>
                    </div>
                    @if($resi->notes)
                        <div style="padding:0.6rem 0.8rem; background:#f8fafc; border-radius:8px; border:1px solid var(--line);">
                            <div style="font-size:0.68rem; color:var(--ink-soft); font-weight:700; text-transform:uppercase;">Catatan</div>
                            <div style="font-size:0.85rem; white-space:pre-line; word-break:break-word; margin-top:0.1rem;">{{ $resi->notes }}</div>
                        </div>
                    @endif
                    @if($resi->proof_co)
                        <div style="padding:0.65rem 0.88rem; background:#eff6ff; border-radius:8px; border:1px solid #bfdbfe;">
                            <div style="font-size:0.68rem; color:#1d4ed8; font-weight:700; text-transform:uppercase;">Bukti Checkout / Proof CO</div>
                            @php $isPdf = Str::endsWith(strtolower($resi->proof_co), '.pdf'); @endphp
                            @if($isPdf)
                                <a href="{{ Storage::url($resi->proof_co) }}" target="_blank" style="color:#2563eb; font-weight:700; font-size:0.82rem; margin-top:0.2rem; display:inline-block; text-decoration:underline;">📄 Download/Lihat Dokumen Proof CO (PDF) ↗</a>
                            @else
                                <a href="{{ Storage::url($resi->proof_co) }}" target="_blank" style="color:#2563eb; font-weight:700; font-size:0.82rem; margin-top:0.2rem; display:inline-block; text-decoration:underline;">🖼️ Lihat Bukti Checkout (Gambar) ↗</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Foto Referensi User --}}
        @if($resi->photo_item)
        <div class="card" style="border-radius:16px; overflow:hidden;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.4rem;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">📷 Foto Referensi User</h3>
                <a href="{{ Storage::url($resi->photo_item) }}" target="_blank" style="font-size:0.75rem; color:var(--blue-deep); font-weight:600; text-decoration:none;">Buka Tab ↗</a>
            </div>
            <div style="padding:1rem;">
                <img src="{{ Storage::url($resi->photo_item) }}" alt="Foto referensi user"
                    style="width:100%; border-radius:10px; border:1px solid var(--line); cursor:pointer; object-fit:cover; max-height:260px;"
                    onclick="window.open(this.src, '_blank')">
            </div>
        </div>
        @endif

        {{-- Update Status --}}
        @php
            $nextStatuses = \App\Models\Resi::$statusTransitions[$resi->status] ?? [];
        @endphp
        @if(count($nextStatuses) > 0)
        <div class="card" style="border-radius:16px;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">Update Status</h3>
            </div>
            <div style="padding:1rem 1.1rem;">
                <form method="POST" action="{{ route('admin.resis.status', $resi) }}">
                    @csrf
                    <div style="display:flex; flex-direction:column; gap:0.75rem;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:0.8rem; font-weight:700;">Status Baru</label>
                            <select name="status" class="form-control" required style="width:100%; font-size:0.85rem; padding:0.6rem 0.8rem;">
                                @foreach($nextStatuses as $s)
                                    <option value="{{ $s }}">{{ \App\Models\Resi::$statusLabels[$s] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.6rem 1rem; font-size:0.85rem; font-weight:700;">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <div class="card" style="border-radius:16px;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">Upload Foto</h3>
            </div>
            <div style="padding:1rem 1.1rem; display:grid; gap:1rem;">
                {{-- Foto WH China --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.85rem;">
                    <p style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); margin-bottom:0.5rem; text-transform:uppercase;">📦 Foto Gudang China</p>
                    @if($resi->photo_wh_china)
                        <img src="{{ Storage::url($resi->photo_wh_china) }}" alt="Foto WH China" style="width:100%; border-radius:8px; margin-bottom:0.6rem; object-fit:cover; max-height:180px; border:1px solid var(--line);">
                    @endif
                    <form method="POST" action="{{ route('admin.resis.photo', $resi) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="wh_china">
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            <input type="file" name="photo" accept="image/*" class="form-control" style="width:100%; background:white; border:1px solid var(--line); border-radius:8px; padding:6px 10px; font-size:0.8rem;" required>
                            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.55rem; font-size:0.8rem; font-weight:700;">Upload Foto China</button>
                        </div>
                    </form>
                </div>

                {{-- Foto Tiba Indonesia --}}
                <div style="background:#f8fafc; border:1px solid var(--line); border-radius:12px; padding:0.85rem;">
                    <p style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); margin-bottom:0.5rem; text-transform:uppercase;">🇮🇩 Foto Tiba Indonesia</p>
                    @if($resi->photo_arrived_id)
                        <img src="{{ Storage::url($resi->photo_arrived_id) }}" alt="Foto Tiba Indonesia" style="width:100%; border-radius:8px; margin-bottom:0.6rem; object-fit:cover; max-height:180px; border:1px solid var(--line);">
                    @endif
                    <form method="POST" action="{{ route('admin.resis.photo', $resi) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="arrived_id">
                        <div style="display:flex; flex-direction:column; gap:0.5rem;">
                            <input type="file" name="photo" accept="image/*" class="form-control" style="width:100%; background:white; border:1px solid var(--line); border-radius:8px; padding:6px 10px; font-size:0.8rem;" required>
                            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.55rem; font-size:0.8rem; font-weight:700;">Upload Foto Indonesia</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Foto Box dari Master Shipment --}}
        @if($resi->masterShipment?->photo_box)
        <div class="card" style="border-radius:16px; overflow:hidden;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#f5f3ff; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.4rem;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0; color:#4f46e5;">🚀 Foto Box Pengiriman</h3>
                <a href="{{ Storage::url($resi->masterShipment->photo_box) }}" target="_blank" style="font-size:0.75rem; color:#4f46e5; font-weight:600; text-decoration:none;">Buka Tab ↗</a>
            </div>
            <div style="padding:1rem;">
                <img src="{{ Storage::url($resi->masterShipment->photo_box) }}" alt="Foto Box"
                    style="width:100%; border-radius:10px; border:1.5px solid #c7d2fe; cursor:pointer; object-fit:cover; max-height:220px;"
                    onclick="window.open(this.src, '_blank')">
                <div style="font-size:0.72rem; color:#6366f1; margin-top:0.5rem; text-align:center; font-weight:600;">
                    Box: <a href="{{ route('admin.shipments.show', $resi->masterShipment) }}" style="color:#4f46e5;">{{ $resi->masterShipment->name ?: $resi->masterShipment->code }}</a>
                    @if($resi->masterShipment->photo_box_uploaded_at)
                        · {{ $resi->masterShipment->photo_box_uploaded_at->format('d M Y, H:i') }}
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right Column: Timeline --}}
    <div style="min-width:0;">
        <div class="card" style="border-radius:16px;">
            <div class="card-header" style="padding:0.85rem 1.1rem; background:#fafafa;">
                <h3 style="font-size:0.9rem; font-weight:800; margin:0;">Riwayat Status</h3>
            </div>
            <div style="padding:1rem 1.1rem;">
                @forelse($resi->statusHistories as $history)
                    <div style="display:flex; gap:0.75rem; margin-bottom:1rem;">
                        <div style="display:flex; flex-direction:column; align-items:center;">
                            <div style="width:10px; height:10px; border-radius:50%; background:var(--blue-deep); flex-shrink:0; margin-top:4px;"></div>
                            @if(!$loop->last)
                                <div style="width:2px; flex:1; background:var(--line-dash); margin-top:4px;"></div>
                            @endif
                        </div>
                        <div style="flex:1; padding-bottom:0.75rem;">
                            <div style="font-size:0.85rem; font-weight:600;">{{ \App\Models\Resi::$statusLabels[$history->to_status] ?? $history->to_status }}</div>
                            @if($history->from_status)
                                <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.1rem;">Dari: {{ \App\Models\Resi::$statusLabels[$history->from_status] ?? $history->from_status }}</div>
                            @endif
                            <div style="font-size:0.72rem; color:var(--ink-soft); margin-top:0.2rem;">
                                {{ $history->created_at->format('d M Y, H:i') }}
                                @if($history->changedByUser)
                                    &bull; oleh {{ $history->changedByUser->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="color:var(--ink-soft); font-size:0.85rem; text-align:center; padding:1.5rem 0;">Belum ada riwayat status.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
