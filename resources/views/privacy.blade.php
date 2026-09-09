@extends('layouts.app')
@section('title', 'Kebijakan Privasi')

@section('content')
<div style="max-width:760px; margin:0 auto;">
    <div style="margin-bottom:2rem;">
        <h1 style="font-size:2rem; font-weight:800; color:var(--ink); margin-bottom:0.5rem;">Kebijakan Privasi</h1>
        <p style="color:var(--ink-soft); font-size:0.9rem;">Terakhir diperbarui: September 2026</p>
    </div>

    <div style="background:var(--white); border-radius:24px; border:1px solid var(--line); padding:2.5rem; display:flex; flex-direction:column; gap:2rem;">

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
                Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Data Anda hanya dibagikan kepada penyedia layanan pembayaran (Midtrans) untuk keperluan pemrosesan transaksi, sesuai dengan kebijakan privasi mereka.
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

        <div style="padding-top:1.5rem; border-top:1px solid var(--line); display:flex; gap:1rem;">
            <a href="{{ route('register') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.5rem; background:var(--ink); color:#fff; font-weight:700; font-size:0.875rem; border-radius:12px; text-decoration:none;">
                ← Kembali ke Pendaftaran
            </a>
            <a href="{{ route('tnc') }}" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.5rem; background:var(--surface); color:var(--ink); font-weight:700; font-size:0.875rem; border-radius:12px; text-decoration:none; border:1px solid var(--line);">
                Syarat & Ketentuan
            </a>
        </div>
    </div>
</div>
@endsection
