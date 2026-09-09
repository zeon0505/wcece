@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<style>
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; font-size: 0.85rem; font-weight: 700; color: var(--ink, #111827); margin-bottom: 0.4rem; }
</style>
<div style="max-width: 600px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">
    <div>
        <h1 class="page-title" style="margin: 0;">Pengaturan Profil</h1>
        <p class="page-subtitle" style="margin-top: 0.25rem;">Kelola informasi profil, alamat email, dan password Anda.</p>
    </div>

    @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
        <div class="alert alert-success">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Perubahan berhasil disimpan.
        </div>
    @endif

    {{-- 1. Update Profile Info --}}
    <div style="background: var(--white); border-radius: 16px; border: 1px solid var(--line); box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--line); background: #fafafa;">
            <h2 style="font-size: 1rem; font-weight: 800; color: var(--ink); margin: 0;">Informasi Profil</h2>
            <p style="font-size: 0.8rem; color: var(--ink-soft); margin: 0.25rem 0 0 0;">Perbarui nama akun dan alamat email Anda.</p>
        </div>
        
        <form method="post" action="{{ route('profile.update') }}" style="padding: 1.25rem;">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" name="name" type="text" class="input-gk" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" style="width: 100%;">
                @error('name', 'userProfile')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
                @error('name')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" name="email" type="email" class="input-gk" value="{{ old('email', $user->email) }}" required autocomplete="username" style="width: 100%;">
                @error('email', 'userProfile')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
                @error('email')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-gk-primary">Simpan Profil</button>
        </form>
    </div>

    {{-- 2. Update Password --}}
    <div style="background: var(--white); border-radius: 16px; border: 1px solid var(--line); box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--line); background: #fafafa;">
            <h2 style="font-size: 1rem; font-weight: 800; color: var(--ink); margin: 0;">Ubah Password</h2>
            <p style="font-size: 0.8rem; color: var(--ink-soft); margin: 0.25rem 0 0 0;">Pastikan akun Anda menggunakan password yang panjang dan acak demi keamanan.</p>
        </div>
        
        <form method="post" action="{{ route('password.update') }}" style="padding: 1.25rem;">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="current_password" class="form-label">Password Saat Ini</label>
                <input id="current_password" name="current_password" type="password" class="input-gk" autocomplete="current-password" style="width: 100%;">
                @error('current_password', 'updatePassword')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password Baru</label>
                <input id="password" name="password" type="password" class="input-gk" autocomplete="new-password" style="width: 100%;">
                @error('password', 'updatePassword')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="input-gk" autocomplete="new-password" style="width: 100%;">
                @error('password_confirmation', 'updatePassword')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-gk-primary">Ubah Password</button>
        </form>
    </div>

    {{-- 3. Delete Account (Optional, usually we put it inside a danger zone) --}}
    <div style="background: var(--white); border-radius: 16px; border: 1px solid var(--line); box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #fecaca; background: #fef2f2;">
            <h2 style="font-size: 1rem; font-weight: 800; color: #dc2626; margin: 0;">Hapus Akun</h2>
            <p style="font-size: 0.8rem; color: #991b1b; margin: 0.25rem 0 0 0;">Sekali akun dihapus, semua data dan riwayat paket Anda akan hilang permanen.</p>
        </div>
        
        <form method="post" action="{{ route('profile.destroy') }}" style="padding: 1.25rem;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun secara permanen?');">
            @csrf
            @method('delete')

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="password_delete" class="form-label">Masukkan password untuk konfirmasi</label>
                <input id="password_delete" name="password" type="password" class="input-gk" required style="width: 100%;">
                @error('password', 'userDeletion')
                    <p style="color: #dc2626; font-size: 0.78rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-gk-primary" style="background: #ef4444; color: white; border: none; font-weight: 700;">Hapus Akun Permanen</button>
        </form>
    </div>

</div>
@endsection
