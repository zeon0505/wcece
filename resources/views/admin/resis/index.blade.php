@extends('layouts.admin')
@section('title', 'Semua Resi')

@section('content')
<style>
    .header-actions { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .header-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .filter-card { background: var(--surface); border: 1px solid var(--line); border-radius: 14px; padding: 1rem 1.25rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
    .filter-input-wrap { flex: 1; min-width: 200px; position: relative; }
    .filter-select { padding: 0.5rem 1rem; border: 1px solid var(--line); border-radius: 8px; font-size: 0.875rem; background: var(--bg); color: var(--ink); outline: none; min-width: 160px; }
    
    @media (max-width: 640px) {
        .header-actions { flex-direction: column; align-items: flex-start; }
        .header-btns { width: 100%; }
        .header-btns a { flex: 1; justify-content: center; text-align: center; }
        .filter-input-wrap { min-width: 100%; }
        .filter-select { width: 100%; }
        .filter-card button { width: 100%; justify-content: center; }
    }
</style>

<div class="header-actions">
    <div>
        <h1 style="font-size:1.5rem; font-weight:800; color:var(--ink);">Semua Resi</h1>
        <p style="font-size:0.875rem; color:var(--ink-soft); margin-top:0.2rem;">Kelola dan pantau seluruh resi paket customer.</p>
    </div>
    <div class="header-btns">
        <a href="{{ route('admin.resis.create') }}" style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--ink); color:#fff; font-size:0.875rem; font-weight:600; padding:0.5rem 1.1rem; border-radius:10px; text-decoration:none;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Manual
        </a>
        <a href="{{ route('admin.resis.export') }}" style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--surface); color:var(--ink); border:1px solid var(--line); font-size:0.875rem; font-weight:600; padding:0.5rem 1.1rem; border-radius:10px; text-decoration:none;">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Ekspor Excel
        </a>
    </div>
</div>

{{-- Filter & Search --}}
<form method="GET" action="{{ route('admin.resis.index') }}">
    <div class="filter-card">
        <div class="filter-input-wrap">
            <svg style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:var(--ink-soft);" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari resi, nama customer, atau barang..." style="width:100%; padding:0.5rem 1rem 0.5rem 2.25rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem; background:var(--bg); color:var(--ink); outline:none;">
        </div>
        <select name="status" class="filter-select">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Resi::$statusLabels as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" style="background:var(--ink); color:#fff; font-size:0.875rem; font-weight:600; padding:0.5rem 1.25rem; border:none; border-radius:8px; cursor:pointer;">Filter</button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.resis.index') }}" style="font-size:0.875rem; color:var(--ink-soft); text-decoration:none; padding:0.5rem 0.75rem;">Reset</a>
        @endif
    </div>
</form>

<div style="background:var(--surface); border:1px solid var(--line); border-radius:14px; overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; min-width: 600px;">
            <thead>
                <tr style="border-bottom:1px solid var(--line); background:rgba(0,0,0,0.02);">
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Nomor Resi</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Customer</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Item & Qty</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Status</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Box</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Foto</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resis as $resi)
                    <tr style="border-bottom:1px dashed var(--line); transition:background 0.15s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.875rem 1rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                @if($resi->photo_item)
                                    <a href="{{ Storage::url($resi->photo_item) }}" target="_blank" onclick="event.stopPropagation();">
                                        <img src="{{ Storage::url($resi->photo_item) }}" alt="Foto"
                                            style="width:44px; height:44px; border-radius:8px; object-fit:cover; border:1.5px solid var(--line); flex-shrink:0;">
                                    </a>
                                @else
                                    <div style="width:44px; height:44px; border-radius:8px; background:var(--surface); border:1.5px dashed var(--line); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                        <svg width="16" height="16" fill="none" stroke="var(--ink-soft)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.82rem; color:var(--ink);">{{ $resi->resi_number }}</div>
                                    <div style="font-size:0.72rem; color:var(--ink-soft); margin-top:0.2rem;">{{ $resi->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:0.875rem 1rem;">
                            @if($resi->user)
                                <div style="font-weight:600; font-size:0.875rem;">{{ $resi->user->name }}</div>
                                <div style="font-size:0.72rem; color:var(--ink-soft);">{{ $resi->user->email }}</div>
                            @elseif($resi->customer_name_snapshot)
                                <div style="font-weight:600; font-size:0.875rem;">{{ $resi->customer_name_snapshot }}</div>
                                <div style="font-size:0.72rem; color:#f59e0b;">Unclaimed / Snapshot</div>
                            @else
                                <div style="font-size:0.8rem; color:var(--ink-soft); font-style:italic;">—</div>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1rem;">
                            <div style="font-size:0.875rem; color:var(--ink);">{{ $resi->item_name ?: '—' }}</div>
                            <div style="font-size:0.72rem; color:var(--ink-soft);">{{ $resi->quantity }} pcs</div>
                        </td>
                        <td style="padding:0.875rem 1rem;">
                            <x-status-badge :status="$resi->status" />
                        </td>
                        <td style="padding:0.875rem 1rem;">
                            @if($resi->masterShipment)
                                <span style="font-family:'Space Mono',monospace; font-size:0.78rem; font-weight:700; color:#4f46e5; background:#ede9fe; padding:0.2rem 0.5rem; border-radius:6px;">
                                    {{ $resi->masterShipment->name ?: $resi->masterShipment->code }}
                                </span>
                            @else
                                <span style="font-size:0.78rem; color:var(--ink-soft);">—</span>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1rem;">
                            <div style="display:flex; gap:0.3rem;">
                                @if($resi->photo_wh_china)
                                    <span style="font-size:0.68rem; background:#ecfdf5; color:#059669; padding:0.2rem 0.5rem; border-radius:4px; font-weight:700;">CN ✓</span>
                                @endif
                                @if($resi->photo_arrived_id)
                                    <span style="font-size:0.68rem; background:#eff6ff; color:#2563eb; padding:0.2rem 0.5rem; border-radius:4px; font-weight:700;">ID ✓</span>
                                @endif
                                @if(!$resi->photo_wh_china && !$resi->photo_arrived_id)
                                    <span style="font-size:0.78rem; color:var(--ink-soft);">—</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:0.875rem 1rem; text-align:right; white-space:nowrap;">
                            <div style="display:inline-flex; gap:0.4rem;">
                                <a href="{{ route('admin.resis.show', $resi) }}" style="font-size:0.75rem; padding:0.3rem 0.7rem; background:var(--bg); border:1px solid var(--line); border-radius:6px; text-decoration:none; color:var(--ink); font-weight:500;">Detail</a>
                                <form method="POST" action="{{ route('admin.resis.destroy', $resi) }}" style="display:inline;" onsubmit="return confirm('Hapus resi {{ $resi->resi_number }}? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="font-size:0.75rem; padding:0.3rem 0.7rem; background:#fef2f2; border:1px solid #fecaca; border-radius:6px; color:#dc2626; font-weight:500; cursor:pointer;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:3rem 1rem; color:var(--ink-soft);">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 0.75rem; opacity:0.3; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Tidak ada resi ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($resis->hasPages())
        <div style="padding:1rem 1.25rem; border-top:1px dashed var(--line);">
            {{ $resis->links() }}
        </div>
    @endif
</div>
@endsection
