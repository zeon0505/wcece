@extends('layouts.app')
@section('title', 'Klaim Resi Unclaimed')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
        <a href="{{ route('track.index', ['q' => $resi->resi_number]) }}" style="color:var(--ink-soft); text-decoration:none; display:flex; align-items:center; gap:0.5rem; font-size:0.875rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    <div style="background: white; border-radius: 24px; padding: 2rem; box-shadow: 0 4px 20px rgba(96,165,250,0.08);">
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem;">Klaim Paket Unclaimed</h2>
        <p style="color: #6b7280; font-size: 0.95rem; margin-bottom: 2rem;">Silakan lengkapi data di bawah ini untuk membuktikan bahwa paket ini milik Anda. Admin akan melakukan verifikasi.</p>

        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; margin-bottom:2rem; display:flex; gap:1.5rem; align-items:flex-start;">
            @if($resi->photo_item)
                <div style="width:120px; flex-shrink:0;">
                    <img src="{{ Storage::url($resi->photo_item) }}" alt="Unclaimed Item" style="width:100%; border-radius:8px; border:1px solid #e2e8f0;">
                </div>
            @endif
            <div>
                <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; font-family: 'Space Mono', monospace; letter-spacing: 0.05em; margin-bottom:0.5rem;">{{ $resi->resi_number }}</h4>
                @if($resi->item_name)
                    <div style="margin-bottom:0.5rem;">
                        <span style="display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Nama Barang</span>
                        <span style="font-weight: 700; color: #334155; font-size: 0.95rem;">{{ $resi->item_name }}</span>
                    </div>
                @endif
                <div style="display: flex; gap:1rem;">
                    <div>
                        <span style="display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Tipe</span>
                        <span style="font-weight: 600; color: #334155; font-size: 0.875rem;">{{ $resi->shipment_type ?? 'SEA' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('track.claim.submit', $resi) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#374151; margin-bottom:0.5rem;">Nama Barang *</label>
                <input type="text" name="item_name" value="{{ old('item_name') }}" placeholder="Contoh: Sepatu Sneakers Putih" required
                    style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6';"
                    onblur="this.style.borderColor='#e2e8f0';">
                @error('item_name')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
                <p style="color:#94a3b8; font-size:0.8rem; margin-top:0.25rem;">Deskripsikan barang dengan jelas agar admin mudah memverifikasi.</p>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#374151; margin-bottom:0.5rem;">Jumlah (Pcs) *</label>
                <input type="number" name="quantity" value="{{ old('quantity') }}" placeholder="Contoh: 2" min="1" required
                    style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6';"
                    onblur="this.style.borderColor='#e2e8f0';">
                @error('quantity')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
                <p style="color:#94a3b8; font-size:0.8rem; margin-top:0.25rem;">Masukkan jumlah pcs dari barang ini.</p>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#374151; margin-bottom:0.6rem;">Tipe Pengiriman *</label>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(130px, 1fr)); gap:0.65rem;">
                    {{-- SEA --}}
                    <label style="cursor:pointer;">
                        <input type="radio" name="shipment_type" value="SEA" class="peer" style="display:none;" {{ old('shipment_type', 'SEA') === 'SEA' ? 'checked' : '' }}>
                        <div style="padding:0.85rem; border:2px solid var(--line); border-radius:14px; text-align:center; transition:all 0.2s;" class="peer-checked:border-ink peer-checked:bg-surface">
                            <div style="width:36px; height:36px; background:#e0f2fe; color:#0284c7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.4rem;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div style="font-weight:800; font-size:0.8rem; color:var(--ink);">Jalur Laut (SEA)</div>
                            <div style="font-size:0.68rem; color:var(--ink-soft); margin-top:0.15rem;">Estimasi 3-4 Minggu</div>
                        </div>
                    </label>
                    {{-- AIR --}}
                    <label style="cursor:pointer;">
                        <input type="radio" name="shipment_type" value="AIR" class="peer" style="display:none;" {{ old('shipment_type') === 'AIR' ? 'checked' : '' }}>
                        <div style="padding:0.85rem; border:2px solid var(--line); border-radius:14px; text-align:center; transition:all 0.2s;" class="peer-checked:border-ink peer-checked:bg-surface">
                            <div style="width:36px; height:36px; background:#ffedd5; color:#ea580c; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.4rem;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 15h18M5 15l2.5-8.5A2 2 0 019.4 5h5.2a2 2 0 011.9 1.5L19 15m-9 0v4m-3 0h6"/></svg>
                            </div>
                            <div style="font-weight:800; font-size:0.8rem; color:var(--ink);">Jalur Udara (AIR)</div>
                            <div style="font-size:0.68rem; color:var(--ink-soft); margin-top:0.15rem;">Estimasi 5-7 Hari</div>
                        </div>
                    </label>
                    {{-- HANDCARRY --}}
                    <label style="cursor:pointer;">
                        <input type="radio" name="shipment_type" value="HANDCARRY" class="peer" style="display:none;" {{ old('shipment_type') === 'HANDCARRY' ? 'checked' : '' }}>
                        <div style="padding:0.85rem; border:2px solid var(--line); border-radius:14px; text-align:center; transition:all 0.2s;" class="peer-checked:border-ink peer-checked:bg-surface">
                            <div style="width:36px; height:36px; background:#fce7f3; color:#db2777; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.4rem;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
                            <div style="font-weight:800; font-size:0.8rem; color:var(--ink);">Handcarry</div>
                            <div style="font-size:0.68rem; color:var(--ink-soft); margin-top:0.15rem;">Layanan Khusus VIP</div>
                        </div>
                    </label>
                </div>
                @error('shipment_type')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
                <p style="color:#94a3b8; font-size:0.8rem; margin-top:0.25rem;">Pilih metode pengiriman yang Anda gunakan untuk paket ini.</p>
                
                <style>
                    input[type="radio"]:checked + div {
                        border-color: var(--ink) !important;
                        background-color: var(--surface) !important;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                    }
                </style>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#374151; margin-bottom:0.5rem;">Username Anda *</label>
                <input type="text" name="line_name" value="{{ old('line_name') }}" placeholder="Masukkan username / nama yang tertera pada paket" required
                    style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6';"
                    onblur="this.style.borderColor='#e2e8f0';">
                @error('line_name')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
                <p style="color:#94a3b8; font-size:0.8rem; margin-top:0.25rem;">Username / nama yang terdaftar saat pembelian.</p>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#374151; margin-bottom:0.5rem;">Pickup Code (Opsional)</label>
                <input type="text" name="pickup_code" value="{{ old('pickup_code') }}" placeholder="Masukkan Pickup Code atau Keterangan Tambahan (jika ada)"
                    style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 0.95rem; outline: none; transition: border-color 0.2s;"
                    onfocus="this.style.borderColor='#3b82f6';"
                    onblur="this.style.borderColor='#e2e8f0';">
                @error('pickup_code')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
                <p style="color:#94a3b8; font-size:0.8rem; margin-top:0.25rem;">Kode unik pengambilan atau informasi tambahan untuk admin (kosongkan jika tidak ada).</p>
            </div>

            <div style="margin-bottom:2rem;">
                <label style="display:block; font-size:0.875rem; font-weight:600; color:#374151; margin-bottom:0.5rem;">Foto Bukti Pembelian (Proof) *</label>
                <input type="file" name="proof_photo" accept="image/*,application/pdf" required
                    style="width:100%; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; font-size: 0.95rem;">
                @error('proof_photo')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
                <p style="color:#94a3b8; font-size:0.8rem; margin-top:0.25rem;">Upload screenshot transaksi / bukti resi. Format: JPG, PNG, PDF. Maks 4MB.</p>
            </div>

            <button type="submit" style="width: 100%; background: #f59e0b; color: white; border: none; border-radius: 12px; padding: 14px; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); transition: all 0.2s;"
                onmouseover="this.style.background='#d97706';"
                onmouseout="this.style.background='#f59e0b';">
                Ajukan Klaim Sekarang
            </button>
        </form>
    </div>
</div>
@endsection
