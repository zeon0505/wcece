@extends(Auth::check() ? 'layouts.app' : 'layouts.public')
@section('title', 'Kebijakan Privasi')

@section('content')
<div style="max-width:820px; margin:0 auto; padding-top:0.5rem;">
    
    {{-- Header Section Rata Tengah dengan Hiasan --}}
    <div style="text-align:center; margin-bottom:2.25rem;">
        <div style="display:inline-flex; align-items:center; gap:0.4rem; background:linear-gradient(135deg, #eff6ff, #e0f2fe); border:1px solid #bfdbfe; color:#2563eb; padding:0.35rem 1rem; border-radius:30px; font-size:0.75rem; font-weight:800; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.75rem; box-shadow:0 2px 8px rgba(37,99,235,0.08);">
            🔒 PRIVASI &amp; KEAMANAN DATA
        </div>
        <h1 style="font-size:2.1rem; font-weight:800; color:var(--ink); margin:0; display:flex; align-items:center; justify-content:center; gap:0.6rem; letter-spacing:-0.02em;">
            Kebijakan Privasi
        </h1>
        <p style="color:var(--ink-soft); font-size:0.9rem; margin-top:0.4rem; max-width:560px; margin-left:auto; margin-right:auto; line-height:1.5;">
            Informasi lengkap mengenai bagaimana kami mengumpulkan, mengelola, dan melindungi data pribadi Anda.
        </p>
    </div>

    {{-- Main Content Card --}}
    <div style="background:var(--white); border-radius:24px; border:1px solid var(--line); padding:2.25rem; box-shadow:0 8px 30px rgba(0,0,0,0.03); display:flex; flex-direction:column; gap:1.75rem;">

        <section>
            <h2 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">1. Data yang Kami Kumpulkan</h2>
            <p style="font-size:0.9rem; color:var(--ink-soft); line-height:1.7; margin-bottom:0.5rem;">Saat Anda mendaftar dan menggunakan layanan kami, kami mengumpulkan:</p>
            <ul style="font-size:0.9rem; color:var(--ink-soft); line-height:2; padding-left:1.5rem;">
                <li>Username / nama yang Anda daftarkan</li>
                <li>Alamat email</li>
                <li>Nomor HP (opsional)</li>
                <li>Data resi dan informasi paket yang Anda input</li>
                <li>Riwayat transaksi dan pembayaran</li>
            </ul>
        </section>

        <section>
            <h2 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">2. Penggunaan Data</h2>
            <p style="font-size:0.9rem; color:var(--ink-soft); line-height:1.7; margin-bottom:0.5rem;">Data yang Anda berikan digunakan untuk:</p>
            <ul style="font-size:0.9rem; color:var(--ink-soft); line-height:2; padding-left:1.5rem;">
                <li>Mengidentifikasi dan menghubungkan paket dengan akun Anda</li>
                <li>Mengirimkan notifikasi status pengiriman melalui email</li>
                <li>Menerbitkan tagihan dan memproses pembayaran</li>
                <li>Meningkatkan kualitas layanan kami</li>
            </ul>
        </section>

        <section>
            <h2 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">3. Keamanan Data</h2>
            <p style="font-size:0.9rem; color:var(--ink-soft); line-height:1.7;">
                Kami menerapkan langkah-langkah keamanan standar industri untuk melindungi data Anda, termasuk enkripsi password dan koneksi HTTPS. Namun kami tidak dapat menjamin keamanan mutlak dari transmisi data melalui internet.
            </p>
        </section>

        <section>
            <h2 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">4. Berbagi Data dengan Pihak Ketiga</h2>
            <p style="font-size:0.9rem; color:var(--ink-soft); line-height:1.7;">
                Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Data Anda hanya dibagikan kepada penyedia layanan pembayaran untuk keperluan pemrosesan transaksi, sesuai dengan kebijakan privasi mereka.
            </p>
        </section>

        <section>
            <h2 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">5. Hak Pengguna</h2>
            <ul style="font-size:0.9rem; color:var(--ink-soft); line-height:2; padding-left:1.5rem;">
                <li>Anda berhak meminta akses ke data pribadi yang kami simpan</li>
                <li>Anda berhak meminta koreksi atas data yang tidak akurat</li>
                <li>Anda berhak meminta penghapusan akun Anda</li>
            </ul>
            <p style="font-size:0.9rem; color:var(--ink-soft); line-height:1.7; margin-top:0.75rem;">
                Untuk mengajukan permintaan tersebut, silakan hubungi kami melalui admin.
            </p>
        </section>

        <section>
            <h2 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">6. Cookie</h2>
            <p style="font-size:0.9rem; color:var(--ink-soft); line-height:1.7;">
                Website kami menggunakan cookie sesi untuk menjaga keamanan login Anda. Tidak ada cookie pihak ketiga untuk tujuan pelacakan/iklan yang digunakan.
            </p>
        </section>

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

            <a href="{{ route('tnc') }}" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.7rem 1.5rem; background:var(--surface); color:var(--ink); font-weight:700; font-size:0.875rem; border-radius:12px; text-decoration:none; border:1.5px solid var(--line); transition:all 0.2s;">
                Syarat &amp; Ketentuan &rarr;
            </a>
        </div>
    </div>
</div>
@endsection
