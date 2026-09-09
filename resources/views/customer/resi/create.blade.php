@extends('layouts.app')
@section('title', 'Input Resi Baru')

@section('content')
<style>
    .create-resi-card { background:var(--white); border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 30px rgba(0,0,0,0.03); max-width:720px; width:100%; overflow:hidden; }
    .form-row-item-qty { display:grid; grid-template-columns: 1fr 120px; gap:1.25rem; margin-bottom:1.25rem; }
    .create-form-actions { display:flex; gap:0.75rem; justify-content:flex-end; padding-top:1.25rem; border-top:1px solid var(--line); }
    
    @media (max-width: 640px) {
        .form-row-item-qty { grid-template-columns: 1fr; gap:1rem; }
        .owner-banner { flex-direction: column; align-items: flex-start !important; gap: 0.5rem !important; }
        .owner-badge { align-self: flex-start; }
        .create-form-actions { flex-direction: column-reverse; }
        .create-form-actions a, .create-form-actions button { width: 100% !important; text-align: center; justify-content: center; }
    }
</style>

<div class="page-header" style="margin-bottom:1.5rem;">
    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
        <a href="{{ route('dashboard') }}" style="color:var(--ink-soft); text-decoration:none; font-size:0.85rem; font-weight:600; display:flex; align-items:center; gap:0.4rem;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>
    <h1 class="page-title" style="font-size:1.4rem; font-weight:800; color:var(--ink); margin:0;">Input Resi Baru</h1>
    <p class="page-subtitle" style="color:var(--ink-soft); margin-top:0.2rem; font-size:0.85rem;">Daftarkan nomor resi dari seller China dan pilih metode pengiriman Anda.</p>
</div>

<div class="create-resi-card">
    <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--line); background:#fafafa;">
        <span style="font-size:1rem; font-weight:800; color:var(--ink);">Detail Paket</span>
    </div>

    {{-- Owner info banner --}}
    <div class="owner-banner" style="padding:1rem 1.25rem; background:linear-gradient(135deg,#f0f7ff,#f8f4ff); border-bottom:1px solid var(--line); display:flex; align-items:center; gap:1rem;">
        <div style="width:36px; height:36px; border-radius:50%; background:var(--ink); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.9rem; flex-shrink:0;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div style="flex:1; overflow:hidden;">
            <div style="font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:var(--ink-soft); margin-bottom:0.1rem;">Pemilik Paket</div>
            <div style="font-weight:800; color:var(--ink); font-size:0.875rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
            <div style="font-size:0.75rem; color:var(--ink-soft); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->email }}</div>
        </div>
        <div class="owner-badge" style="font-size:0.7rem; background:#dcfce7; color:#16a34a; padding:0.25rem 0.6rem; border-radius:20px; font-weight:700; white-space:nowrap; flex-shrink:0;">✓ Terverifikasi</div>
    </div>

    <div style="padding:1.25rem;">
        <form method="POST" action="{{ route('resi.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Resi Number --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
                <label class="form-label" for="resi_number" style="display:block; font-size:0.85rem; font-weight:700; color:var(--ink); margin-bottom:0.4rem;">Nomor Resi <span style="color:var(--red);">*</span></label>
                <input id="resi_number" type="text" name="resi_number" class="form-input {{ $errors->has('resi_number') ? 'is-invalid' : '' }}"
                    value="{{ old('resi_number') }}" required
                    placeholder="CONTOH: JD000123456789"
                    style="width:100%; padding:0.7rem 0.9rem; border-radius:10px; border:1.5px solid var(--line); font-family:'Space Mono',monospace; text-transform:uppercase; font-size:0.9rem; outline:none;"
                    oninput="this.value=this.value.toUpperCase()">
                @error('resi_number')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.3rem; font-weight:500;">{{ $message }}</p>
                @enderror
                <p style="color:var(--ink-soft); font-size:0.75rem; margin-top:0.3rem;">Nomor resi dari seller di Taobao, 1688, dll. Hanya huruf, angka, strip, underscore.</p>
            </div>

            {{-- Item Name & Quantity --}}
            <div class="form-row-item-qty">
                <div class="form-group">
                    <label class="form-label" for="item_name" style="display:block; font-size:0.85rem; font-weight:700; color:var(--ink); margin-bottom:0.4rem;">Nama Barang <span style="font-weight:400; color:var(--ink-soft);">(opsional)</span></label>
                    <input id="item_name" type="text" name="item_name" class="form-input {{ $errors->has('item_name') ? 'is-invalid' : '' }}"
                        value="{{ old('item_name') }}" placeholder="Baju, tas, elektronik..."
                        style="width:100%; padding:0.7rem 0.9rem; border-radius:10px; border:1.5px solid var(--line); font-size:0.875rem; outline:none;">
                    @error('item_name')
                        <p style="color:var(--red); font-size:0.8rem; margin-top:0.3rem; font-weight:500;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="quantity" style="display:block; font-size:0.85rem; font-weight:700; color:var(--ink); margin-bottom:0.4rem;">Qty <span style="color:var(--red);">*</span></label>
                    <input id="quantity" type="number" name="quantity" class="form-input {{ $errors->has('quantity') ? 'is-invalid' : '' }}"
                        value="{{ old('quantity', 1) }}" required min="1"
                        style="width:100%; padding:0.7rem 0.9rem; border-radius:10px; border:1.5px solid var(--line); font-size:0.875rem; outline:none;">
                    @error('quantity')
                        <p style="color:var(--red); font-size:0.8rem; margin-top:0.3rem; font-weight:500;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Shipment Type --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--ink); margin-bottom:0.6rem;">Metode Pengiriman <span style="color:var(--red);">*</span></label>
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
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.3rem; font-weight:500;">{{ $message }}</p>
                @enderror
                
                <style>
                    input[type="radio"]:checked + div {
                        border-color: var(--ink) !important;
                        background-color: var(--surface) !important;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                    }
                </style>
            </div>

            {{-- Photo Upload --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--ink); margin-bottom:0.4rem;">Foto Referensi Barang <span style="font-weight:400; color:var(--ink-soft);">(opsional)</span></label>
                <div style="border:2px dashed var(--line-dash); border-radius:14px; padding:1.25rem 1rem; text-align:center; background:#fafafa;">
                    <input type="file" name="photo_item" id="photo_item" accept="image/*" style="display:none;" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Pilih File Foto';">
                    <div style="width:36px; height:36px; background:var(--white); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.05); color:var(--ink-soft);">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <label for="photo_item" style="display:inline-block; background:var(--ink); color:#fff; padding:0.45rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:700; cursor:pointer; margin-bottom:0.4rem;">Pilih Foto Barang</label>
                    <p id="file-name" style="font-size:0.78rem; color:var(--ink-soft); font-weight:500; margin:0;">Pilih File / Drag & Drop</p>
                    <p style="font-size:0.7rem; color:var(--ink-soft); margin-top:0.2rem;">Maksimal 5MB (JPG, PNG)</p>
                </div>
                @error('photo_item')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.3rem; font-weight:500;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Proof Checkout (Proof CO) --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--ink); margin-bottom:0.4rem;">Bukti Checkout / Proof CO <span style="font-weight:400; color:var(--ink-soft);">(opsional)</span></label>
                <div style="border:2px dashed #bfdbfe; border-radius:14px; padding:1.25rem 1rem; text-align:center; background:#eff6ff;">
                    <input type="file" name="proof_co" id="proof_co" accept="image/*,application/pdf" style="display:none;" onchange="document.getElementById('proof-co-file-name').textContent = this.files[0] ? this.files[0].name : 'Pilih File Bukti Checkout';">
                    <div style="width:36px; height:36px; background:var(--white); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 0.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.05); color:#2563eb;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <label for="proof_co" style="display:inline-block; background:#2563eb; color:#fff; padding:0.45rem 1rem; border-radius:8px; font-size:0.8rem; font-weight:700; cursor:pointer; margin-bottom:0.4rem;">Upload Bukti CO</label>
                    <p id="proof-co-file-name" style="font-size:0.78rem; color:#1e40af; font-weight:500; margin:0;">Pilih File / Drag & Drop (Format: Gambar / PDF)</p>
                    <p style="font-size:0.7rem; color:#3b82f6; margin-top:0.2rem;">Maksimal 5MB (JPG, PNG, PDF)</p>
                </div>
                @error('proof_co')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.3rem; font-weight:500;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
                <label class="form-label" for="notes" style="display:block; font-size:0.85rem; font-weight:700; color:var(--ink); margin-bottom:0.4rem;">Catatan <span style="font-weight:400; color:var(--ink-soft);">(opsional)</span></label>
                <textarea id="notes" name="notes" rows="3" placeholder="Barang fragile, perlu bubble wrap ekstra, dll."
                    style="width:100%; padding:0.7rem 0.9rem; border-radius:10px; border:1.5px solid var(--line); font-size:0.875rem; outline:none; resize:vertical;">{{ old('notes') }}</textarea>
            </div>

            {{-- Buttons --}}
            <div class="create-form-actions">
                <a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; justify-content:center; padding:0.65rem 1.25rem; border-radius:10px; font-weight:700; font-size:0.875rem; text-decoration:none; color:var(--ink); border:2px solid var(--line); text-align:center;">Batal</a>
                <button type="submit" id="submit-resi" style="display:inline-flex; align-items:center; justify-content:center; padding:0.65rem 1.25rem; border-radius:10px; font-weight:700; font-size:0.875rem; color:#fff; background:linear-gradient(135deg, var(--ink), #4f46e5); border:none; cursor:pointer; box-shadow:0 4px 15px rgba(79,70,229,0.25); text-align:center;">
                    Daftarkan Resi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Info box --}}
<div style="display:flex; align-items:center; gap:0.75rem; background:#e0f2fe; border:1px solid #bae6fd; padding:0.85rem 1.1rem; border-radius:14px; max-width:720px; width:100%; margin-top:1.25rem;">
    <div style="color:#0284c7; flex-shrink:0;">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div style="font-size:0.8rem; color:#0369a1; line-height:1.35;">
        Setelah input resi, status awal adalah <strong>Menunggu Tiba di Gudang China</strong>. Tim admin akan update status dan foto paket saat diterima di gudang China.
    </div>
</div>
@endsection
