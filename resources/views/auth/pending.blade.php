@extends('layouts.app')

@section('title', 'Menunggu Persetujuan')

@section('content')
<div style="min-height:80vh; display:flex; align-items:center; justify-content:center; padding:2rem;">
    <div style="background:var(--white); border-radius:24px; border:1px solid var(--line); box-shadow:0 8px 40px rgba(0,0,0,0.06); padding:2.5rem 2rem; max-width:480px; width:100%; text-align:center;">
        <div style="width:72px; height:72px; background:linear-gradient(135deg,#fef9c3,#fde68a); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem; font-size:2rem;">
            ⏳
        </div>
        <h1 style="font-size:1.4rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">Pendaftaran Berhasil!</h1>
        <p style="color:var(--ink-soft); font-size:0.9rem; line-height:1.6; margin-bottom:1.5rem;">
            Akun Anda sudah terdaftar dan sedang menunggu persetujuan dari admin. Anda akan bisa masuk ke aplikasi setelah admin menyetujui akun Anda.
        </p>
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:1rem; margin-bottom:1.5rem;">
            <p style="color:#15803d; font-size:0.85rem; font-weight:600; margin:0;">
                ✅ Silakan tunggu notifikasi dari admin. Proses persetujuan biasanya berlangsung dalam 1x24 jam.
            </p>
        </div>
        <a href="{{ route('login') }}" style="display:inline-block; padding:0.65rem 1.5rem; background:linear-gradient(135deg, var(--ink), #4f46e5); color:#fff; font-weight:700; font-size:0.875rem; border-radius:10px; text-decoration:none;">
            Kembali ke Login
        </a>
    </div>
</div>
@endsection
