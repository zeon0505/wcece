@extends(Auth::check() ? 'layouts.app' : 'layouts.public')
@section('title', 'Syarat & Ketentuan')

@section('content')
<div style="max-width:820px; margin:0 auto; padding-top:0.5rem;">
    
    {{-- Header Section Rata Tengah dengan Hiasan --}}
    <div style="text-align:center; margin-bottom:2.25rem;">
        <div style="display:inline-flex; align-items:center; gap:0.4rem; background:linear-gradient(135deg, #fef2f2, #fce7f3); border:1px solid #fbcfe8; color:#db2777; padding:0.35rem 1rem; border-radius:30px; font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.75rem; box-shadow:0 2px 8px rgba(219,39,119,0.08);">
            📜 INFORMASI &amp; PERATURAN RESMI
        </div>
        <h1 style="font-size:2.1rem; font-weight:800; color:var(--ink); margin:0; display:flex; align-items:center; justify-content:center; gap:0.6rem; letter-spacing:-0.02em;">
            <span style="font-size:1.6rem;">‼️</span> Terms &amp; Conditions <span style="font-size:1.6rem;">‼️</span>
        </h1>
        <p style="color:var(--ink-soft); font-size:0.9rem; margin-top:0.4rem; max-width:560px; margin-left:auto; margin-right:auto; line-height:1.5;">
            Harap baca dan pahami seluruh syarat &amp; ketentuan layanan WH CHINA by CECE di bawah ini sebelum menggunakan jasa pengiriman.
        </p>
    </div>

    @php
        $dynamicTnC = \App\Http\Controllers\Admin\SettingsController::get('tnc_content');
    @endphp

    {{-- Main Content Card --}}
    <div style="background:var(--white); border-radius:24px; border:1px solid var(--line); padding:2.25rem; box-shadow:0 8px 30px rgba(0,0,0,0.03); display:flex; flex-direction:column; gap:1.75rem;">

        {{-- Alert Hiasan Peringatan --}}
        <div style="background:linear-gradient(135deg, #fffbeb, #fef3c7); border:1.5px solid #fde68a; border-radius:16px; padding:1rem 1.25rem; display:flex; align-items:center; gap:0.85rem;">
            <div style="width:38px; height:38px; background:#fef08a; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; color:#b45309;">
                ⚠️
            </div>
            <div style="font-size:0.85rem; color:#92400e; font-weight:700; line-height:1.45;">
                Dilarang keras menyebarluaskan alamat WH. Wajib memahami seluruh Syarat &amp; Ketentuan sebelum menggunakan layanan WH CHINA by CECE.
            </div>
        </div>

        {{-- Dynamic TnC Main Content --}}
        @if($dynamicTnC)
            <div style="font-size:0.95rem; color:var(--ink); line-height:1.85; white-space:pre-line; font-weight:500; background:#fafafa; border:1px solid var(--line); padding:1.5rem; border-radius:16px;">{{ $dynamicTnC }}</div>
        @else
            <div style="text-align:center; padding:2rem; color:var(--ink-soft); font-size:0.9rem;">
                Belum ada Syarat &amp; Ketentuan yang diatur oleh Admin.
            </div>
        @endif

        {{-- Action Buttons Rata Tengah --}}
        <div style="padding-top:1.5rem; border-top:1px solid var(--line); display:flex; justify-content:center; align-items:center; gap:0.75rem; flex-wrap:wrap;">
            @auth
                <a href="{{ route('dashboard') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.7rem 1.5rem; background:var(--ink); color:#fff; font-weight:700; font-size:0.875rem; border-radius:12px; text-decoration:none; box-shadow:0 4px 14px rgba(0,0,0,0.12); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    ← Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.7rem 1.5rem; background:var(--ink); color:#fff; font-weight:700; font-size:0.875rem; border-radius:12px; text-decoration:none; box-shadow:0 4px 14px rgba(0,0,0,0.12); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    ← Kembali ke Pendaftaran
                </a>
            @endauth

            <a href="{{ route('privacy') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.7rem 1.5rem; background:var(--surface); color:var(--ink); font-weight:700; font-size:0.875rem; border-radius:12px; text-decoration:none; border:1.5px solid var(--line); transition:all 0.2s;">
                Kebijakan Privasi &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
