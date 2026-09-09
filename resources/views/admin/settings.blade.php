@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')

@section('content')
<div style="max-width:900px;">
    <h1 style="font-size:1.5rem; font-weight:800; color:var(--ink); margin-bottom:0.25rem;">Pengaturan Informasi &amp; Sistem</h1>
    <p style="font-size:0.875rem; color:var(--ink-soft); margin-bottom:1.5rem;">Kelola rekening bank, alamat gudang China, TnC pendaftaran, dan informasi tarif di satu tempat.</p>



    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        {{-- Section 1: Rekening Bank --}}
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 4px 20px rgba(0,0,0,0.02);">
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; border-bottom:1px solid var(--line); padding-bottom:0.75rem;">
                <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <h3 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin:0;">Rekening Bank</h3>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Nama Bank</label>
                    <input type="text" name="bank_name" value="{{ old('bank_name', $settings['bank_name']) }}" placeholder="BCA, Mandiri, BRI, DLL"
                        style="width:100%; padding:0.6rem 0.9rem; border:1.5px solid var(--line); border-radius:10px; font-size:0.9rem;">
                </div>
                <div>
                    <label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Nomor Rekening</label>
                    <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $settings['bank_account_number']) }}" placeholder="1234567890"
                        style="width:100%; padding:0.6rem 0.9rem; border:1.5px solid var(--line); border-radius:10px; font-size:0.9rem; font-family:'Space Mono',monospace;">
                </div>
            </div>
            <div style="margin-top:1rem;">
                <label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Nama Pemilik Rekening (A/N)</label>
                <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $settings['bank_account_name']) }}" placeholder="Nama Lengkap Pemilik Rekening"
                    style="width:100%; padding:0.6rem 0.9rem; border:1.5px solid var(--line); border-radius:10px; font-size:0.9rem;">
            </div>
        </div>

        {{-- Section 2: Alamat Gudang China (AIR & SEA) --}}
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 4px 20px rgba(0,0,0,0.02);">
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; border-bottom:1px solid var(--line); padding-bottom:0.75rem;">
                <svg width="20" height="20" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <h3 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin:0;">Alamat Gudang China (Ditampilkan di Dashboard Customer)</h3>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                {{-- Air Address --}}
                <div style="background:#eff6ff; border:1px solid #bfdbfe; padding:1.25rem; border-radius:14px;">
                    <h4 style="font-size:0.95rem; font-weight:800; color:#1e40af; margin-bottom:1rem;">✈️ AIR &amp; HANDCARRY</h4>
                    <div style="margin-bottom:0.75rem;">
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#1e3a8a; margin-bottom:0.3rem;">Nama Penerima</label>
                        <input type="text" name="wh_air_name" value="{{ old('wh_air_name', $settings['wh_air_name']) }}"
                            style="width:100%; padding:0.5rem; border:1px solid #93c5fd; border-radius:8px; font-size:0.85rem;">
                    </div>
                    <div style="margin-bottom:0.75rem;">
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#1e3a8a; margin-bottom:0.3rem;">No Telepon</label>
                        <input type="text" name="wh_air_phone" value="{{ old('wh_air_phone', $settings['wh_air_phone']) }}"
                            style="width:100%; padding:0.5rem; border:1px solid #93c5fd; border-radius:8px; font-size:0.85rem; font-family:'Space Mono',monospace;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#1e3a8a; margin-bottom:0.3rem;">Alamat Lengkap</label>
                        <textarea name="wh_air_address" rows="3"
                            style="width:100%; padding:0.5rem; border:1px solid #93c5fd; border-radius:8px; font-size:0.85rem;">{{ old('wh_air_address', $settings['wh_air_address']) }}</textarea>
                    </div>
                </div>

                {{-- Sea Address --}}
                <div style="background:#fce7f3; border:1px solid #fbcfe8; padding:1.25rem; border-radius:14px;">
                    <h4 style="font-size:0.95rem; font-weight:800; color:#9d174d; margin-bottom:1rem;">🚢 SEA CARGO</h4>
                    <div style="margin-bottom:0.75rem;">
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#831843; margin-bottom:0.3rem;">Nama Penerima</label>
                        <input type="text" name="wh_sea_name" value="{{ old('wh_sea_name', $settings['wh_sea_name']) }}"
                            style="width:100%; padding:0.5rem; border:1px solid #f472b6; border-radius:8px; font-size:0.85rem;">
                    </div>
                    <div style="margin-bottom:0.75rem;">
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#831843; margin-bottom:0.3rem;">No Telepon</label>
                        <input type="text" name="wh_sea_phone" value="{{ old('wh_sea_phone', $settings['wh_sea_phone']) }}"
                            style="width:100%; padding:0.5rem; border:1px solid #f472b6; border-radius:8px; font-size:0.85rem; font-family:'Space Mono',monospace;">
                    </div>
                    <div>
                        <label style="display:block; font-size:0.78rem; font-weight:700; color:#831843; margin-bottom:0.3rem;">Alamat Lengkap</label>
                        <textarea name="wh_sea_address" rows="3"
                            style="width:100%; padding:0.5rem; border:1px solid #f472b6; border-radius:8px; font-size:0.85rem;">{{ old('wh_sea_address', $settings['wh_sea_address']) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 3: Syarat & Ketentuan (TnC) --}}
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); padding:1.5rem; margin-bottom:1.5rem; box-shadow:0 4px 20px rgba(0,0,0,0.02);">
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem; border-bottom:1px solid var(--line); padding-bottom:0.75rem;">
                <svg width="20" height="20" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin:0;">Syarat &amp; Ketentuan (TnC) &amp; Informasi Harga</h3>
            </div>

            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Teks Syarat &amp; Ketentuan (TnC Pendaftaran &amp; Landing Page)</label>
                <p style="font-size:0.75rem; color:var(--ink-soft); margin-bottom:0.5rem;">Tulis per baris atau dengan angka 1., 2., 3., dst.</p>
                <textarea name="tnc_content" rows="8"
                    style="width:100%; padding:0.75rem; border:1.5px solid var(--line); border-radius:10px; font-size:0.875rem; font-family:inherit;">{{ old('tnc_content', $settings['tnc_content']) }}</textarea>
            </div>

            <div>
                <label style="display:block; font-size:0.85rem; font-weight:700; margin-bottom:0.4rem;">Informasi Harga / Catatan Pengiriman</label>
                <textarea name="pricing_info" rows="4"
                    style="width:100%; padding:0.75rem; border:1.5px solid var(--line); border-radius:10px; font-size:0.875rem; font-family:inherit;">{{ old('pricing_info', $settings['pricing_info']) }}</textarea>
            </div>
        </div>

        <button type="submit" style="width:100%; padding:0.85rem; background:var(--ink); color:#fff; font-weight:800; font-size:1rem; border:none; border-radius:12px; cursor:pointer; box-shadow:0 4px 15px rgba(0,0,0,0.15);">
            💾 Simpan Semua Pengaturan
        </button>
    </form>
</div>
@endsection
