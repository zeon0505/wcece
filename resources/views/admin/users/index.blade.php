@extends('layouts.admin')
@section('title', 'Manajemen User')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     CUSTOM MODAL POPUP
═══════════════════════════════════════════════════════════ --}}
<div id="customModal" style="display:none; position:fixed; inset:0; z-index:9999; align-items:center; justify-content:center;">
    {{-- Backdrop --}}
    <div id="modalBackdrop" onclick="closeModal()" style="position:absolute; inset:0; background:rgba(15,23,42,0.45); backdrop-filter:blur(4px);"></div>

    {{-- Modal Card --}}
    <div style="position:relative; background:#fff; border-radius:20px; padding:2rem 2rem 1.5rem; max-width:400px; width:90%; box-shadow:0 25px 60px rgba(0,0,0,0.18); animation:modalIn 0.22s cubic-bezier(0.34,1.56,0.64,1) forwards;">

        {{-- Icon --}}
        <div id="modalIcon" style="width:56px; height:56px; border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.75rem; margin:0 auto 1rem;"></div>

        {{-- Title --}}
        <h3 id="modalTitle" style="font-size:1.1rem; font-weight:800; color:#0f172a; text-align:center; margin:0 0 0.5rem;"></h3>

        {{-- Body --}}
        <p id="modalBody" style="font-size:0.875rem; color:#64748b; text-align:center; margin:0 0 1.5rem; line-height:1.5;"></p>

        {{-- Buttons --}}
        <div style="display:flex; gap:0.75rem;">
            <button onclick="closeModal()" style="flex:1; padding:0.65rem; background:#f1f5f9; color:#475569; font-size:0.875rem; font-weight:700; border:none; border-radius:12px; cursor:pointer; transition:background 0.15s;"
                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                Batal
            </button>
            <button id="modalConfirmBtn" style="flex:1; padding:0.65rem; font-size:0.875rem; font-weight:700; border:none; border-radius:12px; cursor:pointer; color:#fff; transition:filter 0.15s;"
                onmouseover="this.style.filter='brightness(0.9)'" onmouseout="this.style.filter='none'">
                Konfirmasi
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity:0; transform:scale(0.88) translateY(10px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}
</style>

{{-- ═══════════════════════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════════════════════ --}}
<div class="page-header">
    <h1 class="page-title">Manajemen User</h1>
    <p class="page-subtitle">Setujui atau tolak pendaftaran akun customer baru.</p>
</div>

{{-- Pending Users --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
        <h2 style="font-size:1rem; font-weight:800; color:var(--ink);">
            ⏳ Menunggu Persetujuan
            @if($pendingUsers->count() > 0)
                <span style="background:#fef9c3; color:#854d0e; font-size:0.72rem; font-weight:700; padding:0.2rem 0.6rem; border-radius:999px; margin-left:0.5rem;">{{ $pendingUsers->count() }} baru</span>
            @endif
        </h2>
    </div>

    @if($pendingUsers->isEmpty())
        <p style="color:var(--ink-soft); font-size:0.875rem; text-align:center; padding:2rem 0;">Tidak ada pendaftaran baru yang menunggu persetujuan.</p>
    @else
        <div class="table-wrap">
            <table class="manifest">
                <thead>
                    <tr>
                        <th>Waktu Daftar</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr>
                            <td style="font-size:0.8rem; color:var(--ink-soft);">{{ $user->created_at->format('d M Y, H:i') }}</td>
                            <td style="font-weight:700;">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <div style="display:flex; gap:0.5rem;">
                                    {{-- Form Setujui --}}
                                    <form id="form-approve-{{ $user->id }}" method="POST" action="{{ route('admin.users.approve', $user) }}">
                                        @csrf
                                        <button type="button"
                                            onclick="showModal('approve', '{{ addslashes($user->name) }}', '{{ $user->id }}')"
                                            style="padding:0.35rem 0.85rem; background:#16a34a; color:#fff; font-size:0.78rem; font-weight:700; border:none; border-radius:8px; cursor:pointer;">
                                            ✅ Setujui
                                        </button>
                                    </form>

                                    {{-- Form Tolak --}}
                                    <form id="form-reject-{{ $user->id }}" method="POST" action="{{ route('admin.users.reject', $user) }}">
                                        @csrf
                                        <button type="button"
                                            onclick="showModal('reject', '{{ addslashes($user->name) }}', '{{ $user->id }}')"
                                            style="padding:0.35rem 0.85rem; background:#dc2626; color:#fff; font-size:0.78rem; font-weight:700; border:none; border-radius:8px; cursor:pointer;">
                                            ❌ Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Approved Users --}}
<div class="card">
    <h2 style="font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:1rem;">✅ User Aktif ({{ $approvedUsers->count() }})</h2>
    @if($approvedUsers->isEmpty())
        <p style="color:var(--ink-soft); font-size:0.875rem; text-align:center; padding:2rem 0;">Belum ada user aktif.</p>
    @else
        <div class="table-wrap">
            <table class="manifest">
                <thead>
                    <tr>
                        <th>Tanggal Daftar</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Jumlah Resi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approvedUsers as $user)
                        <tr>
                            <td style="font-size:0.8rem; color:var(--ink-soft);">{{ $user->created_at->format('d M Y') }}</td>
                            <td style="font-weight:700;">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->resis()->count() }} resi</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
let pendingFormId = null;

function showModal(type, userName, userId) {
    const modal    = document.getElementById('customModal');
    const icon     = document.getElementById('modalIcon');
    const title    = document.getElementById('modalTitle');
    const body     = document.getElementById('modalBody');
    const btn      = document.getElementById('modalConfirmBtn');

    if (type === 'approve') {
        icon.style.background = '#dcfce7';
        icon.textContent = '✅';
        title.textContent = 'Setujui Akun?';
        body.innerHTML = `Akun <strong>${userName}</strong> akan diaktifkan dan dapat mulai menggunakan layanan.`;
        btn.style.background = '#16a34a';
        btn.textContent = 'Ya, Setujui';
        pendingFormId = 'form-approve-' + userId;
    } else {
        icon.style.background = '#fee2e2';
        icon.textContent = '❌';
        title.textContent = 'Tolak & Hapus Akun?';
        body.innerHTML = `Akun <strong>${userName}</strong> akan dihapus permanen. Aksi ini <strong>tidak bisa dibatalkan</strong>.`;
        btn.style.background = '#dc2626';
        btn.textContent = 'Ya, Tolak & Hapus';
        pendingFormId = 'form-reject-' + userId;
    }

    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('customModal').style.display = 'none';
    document.body.style.overflow = '';
    pendingFormId = null;
}

document.getElementById('modalConfirmBtn').addEventListener('click', function () {
    if (pendingFormId) {
        document.getElementById(pendingFormId).submit();
    }
});

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

@endsection
