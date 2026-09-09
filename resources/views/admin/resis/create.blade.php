@extends('layouts.admin')
@section('title', 'Tambah Resi Unclaimed')

@section('content')
<style>
    .create-resi-header { margin-bottom: 1.25rem; }
    .form-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; justify-content: flex-end; }
    
    @media (max-width: 640px) {
        .form-actions { flex-direction: column-reverse; }
        .form-actions a, .form-actions button { width: 100%; text-align: center; justify-content: center; }
    }
</style>

<div class="create-resi-header">
    <a href="{{ route('admin.resis.index') }}" style="color:var(--ink-soft); text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem; font-size:0.85rem; font-weight:600; margin-bottom:0.5rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Resi
    </a>
    <div>
        <h1 class="page-title" style="font-size:1.35rem; font-weight:800; color:var(--ink); margin:0;">Tambah Resi Unclaimed</h1>
        <p class="page-subtitle" style="font-size:0.85rem; color:var(--ink-soft); margin-top:0.2rem;">Input data paket yang tidak diketahui pemiliknya.</p>
    </div>
</div>

<div class="card" style="border-radius:16px; max-width:600px; width:100%;">
    <div style="padding:1.25rem;">
        <form method="POST" action="{{ route('admin.resis.store') }}" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="status" value="arrived_wh_china">

            <div class="form-group" style="margin-bottom:1.25rem;">
                <label class="form-label" style="font-size:0.85rem; font-weight:700;">Nomor Resi / Tracking Number *</label>
                <input type="text" name="resi_number" class="form-input" placeholder="Contoh: YT888011579" value="{{ old('resi_number') }}" required style="width:100%; font-size:0.875rem;">
                @error('resi_number')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom:1.25rem;">
                <label class="form-label" style="font-size:0.85rem; font-weight:700;">Username / Keterangan Pemilik</label>
                <input type="text" name="customer_name_snapshot" class="form-input" placeholder="Cth: Caca, Unknown, atau kosongkan" value="{{ old('customer_name_snapshot') }}" style="width:100%; font-size:0.875rem;">
                <p style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.25rem;">Isi jika ada petunjuk nama, kosongkan jika tidak tahu sama sekali (akan otomatis diisi "Unclaimed").</p>
                @error('customer_name_snapshot')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group" style="margin-bottom:1.25rem;">
                <label class="form-label" style="font-size:0.85rem; font-weight:700;">Foto Barang Unclaimed *</label>
                <input type="file" id="photo_item" name="photo_item" class="form-input" accept="image/*" style="width:100%; padding:0.4rem 0.5rem; font-size:0.8rem;" required>
                <p style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.25rem;">Wajib diisi agar customer bisa mengenali paketnya.</p>
                @error('photo_item')
                    <p style="color:var(--red); font-size:0.8rem; margin-top:0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.resis.index') }}" class="btn btn-secondary" style="padding:0.6rem 1.25rem;">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding:0.6rem 1.25rem;">Simpan Resi</button>
            </div>
        </form>
    </div>
</div>
@endsection
