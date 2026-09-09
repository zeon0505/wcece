@extends('layouts.admin')
@section('title', 'History Paket Diterima')

@section('content')
<style>
    .admin-resis-grid { display: grid; gap: 1.25rem; }
    .search-flex { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    .table-card { background: var(--white); border-radius: 16px; border: 1px solid var(--line); box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: hidden; }
    .table-container { width: 100%; overflow-x: auto; }
    .admin-table { width: 100%; border-collapse: collapse; min-width: 900px; }
    .admin-table th { background: #fafafa; padding: 0.85rem 1.1rem; text-align: left; font-size: 0.75rem; font-weight: 700; color: var(--ink-soft); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--line); white-space: nowrap; }
    .admin-table td { padding: 1.1rem; border-bottom: 1px solid var(--line); font-size: 0.85rem; color: var(--ink); vertical-align: middle; }
    .admin-table tr:last-child td { border-bottom: none; }
    .admin-table tr:hover { background: #f8fafc; }
</style>

<div class="table-card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <div class="search-flex" style="margin-bottom: 0;">
        <div>
            <h1 class="page-title" style="margin:0;">History Paket Diterima</h1>
            <p class="page-subtitle" style="margin-top:0.25rem;">Daftar paket yang sudah dikonfirmasi diterima oleh customer beserta bukti fotonya.</p>
        </div>
        
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <form method="GET" action="{{ route('admin.resis.received') }}" style="display:flex; gap:0.5rem;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari Resi / Barang / User..." 
                       class="form-control" style="width:260px; font-size:0.85rem;">
                <button type="submit" class="btn" style="background:var(--surface); border:1px solid var(--line); color:var(--ink); padding:0.55rem 1rem;">Cari</button>
                @if($search)
                    <a href="{{ route('admin.resis.received') }}" class="btn" style="background:var(--surface); border:1px solid var(--line); color:var(--ink); text-decoration:none; display:flex; align-items:center;">Reset</a>
                @endif
            </form>
        </div>
    </div>
</div>

<div class="admin-resis-grid">
    <div class="table-card">
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tgl Konfirmasi</th>
                        <th>Resi &amp; Barang</th>
                        <th>Customer</th>
                        <th>Bukti Foto</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resis as $resi)
                    <tr>
                        <td style="white-space:nowrap;">
                            <div style="font-weight:700;">{{ $resi->received_at ? $resi->received_at->format('d M Y') : '-' }}</div>
                            <div style="font-size:0.75rem; color:var(--ink-soft);">{{ $resi->received_at ? $resi->received_at->format('H:i') : '-' }}</div>
                        </td>
                        <td>
                            <div style="font-family:'Space Mono',monospace; font-weight:800; color:var(--ink); font-size:0.9rem;">
                                <a href="{{ route('admin.resis.show', $resi) }}" style="color:var(--blue-deep); text-decoration:none;">{{ $resi->resi_number }}</a>
                            </div>
                            <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.2rem;">{{ $resi->item_name ?: 'Tanpa nama barang' }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $resi->user ? $resi->user->name : '-' }}</div>
                            <div style="font-size:0.75rem; color:var(--ink-soft);">{{ $resi->customer_name_snapshot }}</div>
                        </td>
                        <td>
                            @if($resi->photo_received)
                                <img src="{{ Storage::url($resi->photo_received) }}" alt="Bukti Terima" style="height:60px; width:80px; object-fit:cover; border-radius:6px; border:1px solid var(--line); cursor:pointer;" onclick="window.open(this.src, '_blank')">
                            @else
                                <span style="font-size:0.75rem; color:var(--ink-soft); font-style:italic;">Tidak ada foto</span>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('admin.resis.show', $resi) }}" class="btn btn-primary" style="padding:0.4rem 0.8rem; font-size:0.75rem;">
                                Detail Resi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:3rem; color:var(--ink-soft);">
                            @if($search)
                                Tidak ditemukan data history untuk pencarian "{{ $search }}".
                            @else
                                Belum ada riwayat paket yang diterima customer.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($resis->hasPages())
        <div style="padding:1.1rem; border-top:1px solid var(--line); background:#fafafa;">
            {{ $resis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
