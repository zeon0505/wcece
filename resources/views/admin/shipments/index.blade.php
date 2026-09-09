@extends('layouts.admin')
@section('title', 'Daftar Box Shipment')

@section('content')
<style>
    .header-actions { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
    .filter-tab { padding: 0.4rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; text-decoration: none; display: inline-block; }
    .filter-tab.active { background: var(--ink); color: #fff; }
    .filter-tab.inactive { background: var(--surface); color: var(--ink-soft); border: 1px solid var(--line); }
    @media (max-width: 640px) {
        .header-actions { flex-direction: column; align-items: flex-start; }
        .header-actions a { width: 100%; justify-content: center; text-align: center; }
    }
</style>

<div class="header-actions">
    <div>
        <h1 style="font-size:1.5rem; font-weight:800; color:var(--ink);">Box Shipment</h1>
        <p style="font-size:0.875rem; color:var(--ink-soft); margin-top:0.2rem;">Kelola pengelompokan resi dan pengiriman ke Indonesia.</p>
    </div>
    <a href="{{ route('admin.shipments.create') }}" style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--ink); color:#fff; font-size:0.875rem; font-weight:600; padding:0.5rem 1.1rem; border-radius:10px; text-decoration:none;">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Buat Box Baru
    </a>
</div>

{{-- Filter Tabs --}}
<div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; flex-wrap:wrap;">
    <a href="{{ route('admin.shipments.index') }}" class="filter-tab {{ $typeFilter === 'all' ? 'active' : 'inactive' }}">
        Semua ({{ $counts['all'] ?? 0 }})
    </a>
    <a href="{{ route('admin.shipments.index', ['type' => 'SEA']) }}" class="filter-tab {{ $typeFilter === 'SEA' ? 'active' : 'inactive' }}">
        🚢 Sea Cargo ({{ $counts['SEA'] ?? 0 }})
    </a>
    <a href="{{ route('admin.shipments.index', ['type' => 'AIR']) }}" class="filter-tab {{ $typeFilter === 'AIR' ? 'active' : 'inactive' }}">
        ✈️ Air Cargo ({{ $counts['AIR'] ?? 0 }})
    </a>
    <a href="{{ route('admin.shipments.index', ['type' => 'HANDCARRY']) }}" class="filter-tab {{ $typeFilter === 'HANDCARRY' ? 'active' : 'inactive' }}">
        👜 Handcarry ({{ $counts['HANDCARRY'] ?? 0 }})
    </a>
</div>

<div style="background:var(--surface); border:1px solid var(--line); border-radius:14px; overflow:hidden;">
    <div style="overflow-x:auto; width:100%;">
        <table style="width:100%; border-collapse:collapse; min-width:650px;">
            <thead>
                <tr style="background:rgba(0,0,0,0.02); border-bottom:1px solid var(--line);">
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Box</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Tipe</th>
                    <th style="padding:0.75rem 1rem; text-align:center; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Jumlah Resi</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Status</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Berangkat</th>
                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Tiba</th>
                    <th style="padding:0.75rem 1rem; text-align:right; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shipments as $shipment)
                    <tr style="border-bottom:1px dashed var(--line); transition:background 0.15s;" onmouseover="this.style.background='rgba(0,0,0,0.02)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            <div style="font-weight:700; font-size:0.95rem; color:var(--ink);">
                                {{ $shipment->name ?: 'Box #' . $shipment->id }}
                            </div>
                            <div style="font-size:0.72rem; color:var(--ink-soft); font-family:'Space Mono',monospace; margin-top:0.15rem;">{{ $shipment->code }}</div>
                        </td>
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            @if($shipment->shipment_type === 'AIR')
                                <span style="font-size:0.78rem; font-weight:700; background:#e0e7ff; color:#4f46e5; padding:0.25rem 0.65rem; border-radius:20px; white-space:nowrap;">✈️ AIR</span>
                            @elseif($shipment->shipment_type === 'HANDCARRY')
                                <span style="font-size:0.78rem; font-weight:700; background:#fce7f3; color:#be185d; padding:0.25rem 0.65rem; border-radius:20px; white-space:nowrap;">👜 HANDCARRY</span>
                            @else
                                <span style="font-size:0.78rem; font-weight:700; background:#dcfce7; color:#059669; padding:0.25rem 0.65rem; border-radius:20px; white-space:nowrap;">🚢 SEA</span>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1rem; text-align:center; white-space:nowrap;">
                            <span style="font-weight:800; font-size:1.1rem; color:var(--ink);">{{ $shipment->resis_count }}</span>
                            <span style="font-size:0.75rem; color:var(--ink-soft);"> resi</span>
                        </td>
                        <td style="padding:0.875rem 1rem; white-space:nowrap;">
                            @if($shipment->status === 'packing')
                                <span style="font-size:0.78rem; font-weight:700; background:#fef3c7; color:#d97706; padding:0.25rem 0.65rem; border-radius:6px; white-space:nowrap;">📦 Packing</span>
                            @elseif($shipment->status === 'in_transit')
                                <span style="font-size:0.78rem; font-weight:700; background:#eff6ff; color:#2563eb; padding:0.25rem 0.65rem; border-radius:6px; white-space:nowrap;">🚚 OTW Indonesia</span>
                            @elseif($shipment->status === 'arrived_indonesia')
                                <span style="font-size:0.78rem; font-weight:700; background:#f0fdf4; color:#16a34a; padding:0.25rem 0.65rem; border-radius:6px; white-space:nowrap;">✅ Tiba Indonesia</span>
                            @else
                                <span style="font-size:0.78rem; font-weight:700; background:#f8fafc; color:#64748b; padding:0.25rem 0.65rem; border-radius:6px; white-space:nowrap;">{{ $shipment->status }}</span>
                            @endif
                        </td>
                        <td style="padding:0.875rem 1rem; font-size:0.875rem; color:var(--ink-soft); white-space:nowrap;">
                            {{ $shipment->departed_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td style="padding:0.875rem 1rem; font-size:0.875rem; color:var(--ink-soft); white-space:nowrap;">
                            {{ $shipment->arrived_at?->format('d M Y') ?? '—' }}
                        </td>
                        <td style="padding:0.875rem 1rem; text-align:right; white-space:nowrap;">
                            <a href="{{ route('admin.shipments.show', $shipment) }}" style="font-size:0.78rem; padding:0.35rem 0.85rem; background:var(--bg); border:1px solid var(--line); border-radius:7px; text-decoration:none; color:var(--ink); font-weight:600;">
                                Kelola →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:3rem 1rem; color:var(--ink-soft);">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 0.75rem; opacity:0.3; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Belum ada box shipment. <a href="{{ route('admin.shipments.create') }}" style="color:#4f46e5;">Buat sekarang →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($shipments->hasPages())
        <div style="padding:1rem 1.25rem; border-top:1px dashed var(--line);">
            {{ $shipments->links() }}
        </div>
    @endif
</div>
@endsection
