@extends('layouts.admin')
@section('title', 'Buat Box Shipment')

@section('content')
<style>
    .create-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }
    @media (max-width: 900px) {
        .create-grid { grid-template-columns: 1fr; }
    }
</style>

<div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
    <a href="{{ route('admin.shipments.index') }}" style="display:flex; align-items:center; justify-content:center; width:34px; height:34px; background:var(--surface); border:1px solid var(--line); border-radius:8px; color:var(--ink); text-decoration:none; flex-shrink:0;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h1 style="font-size:1.4rem; font-weight:800; color:var(--ink);">Buat Box Baru</h1>
        <p style="font-size:0.875rem; color:var(--ink-soft);">Buat box pengiriman dan isi dengan resi barang.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.shipments.store') }}">
    @csrf
    <div class="create-grid">

        {{-- Pilih Resi --}}
        <div style="background:var(--surface); border:1px solid var(--line); border-radius:14px; overflow:hidden;">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--line); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
                <div>
                    <div style="font-weight:700; font-size:0.95rem;">Pilih Resi</div>
                    <div style="font-size:0.78rem; color:var(--ink-soft); margin-top:0.15rem;">{{ $availableResis->count() }} resi tersedia</div>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="button" onclick="selectAll()" style="font-size:0.75rem; padding:0.3rem 0.75rem; background:var(--bg); border:1px solid var(--line); border-radius:6px; cursor:pointer; color:var(--ink);">Pilih Semua</button>
                    <button type="button" onclick="deselectAll()" style="font-size:0.75rem; padding:0.3rem 0.75rem; background:var(--bg); border:1px solid var(--line); border-radius:6px; cursor:pointer; color:var(--ink);">Batal Semua</button>
                </div>
            </div>
            {{-- Search filter --}}
            <div style="padding:0.75rem 1.25rem; border-bottom:1px dashed var(--line);">
                <input type="text" id="resiSearch" oninput="filterResis()" placeholder="Filter nama atau resi..." style="width:100%; padding:0.5rem 0.75rem; border:1px solid var(--line); border-radius:7px; font-size:0.85rem; background:var(--bg); color:var(--ink); outline:none;">
            </div>
            <div id="resiList" style="max-height:500px; overflow-y:auto;">
                @if($availableResis->count() > 0)
                    <table style="width:100%; border-collapse:collapse; min-width:300px;">
                        <thead style="background:#f1f5f9; position:sticky; top:0; z-index:10;">
                            <tr>
                                <th style="padding:0.75rem 1.25rem; text-align:left; width:40px;"></th>
                                <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Resi & Barang</th>
                                <th style="padding:0.75rem 1.25rem; text-align:left; font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($availableResis as $resi)
                                <tr class="resi-item" data-type="{{ $resi->shipment_type }}" style="border-bottom:1px solid var(--line); transition:background 0.1s;" onmouseover="this.style.background='rgba(0,0,0,0.025)'" onmouseout="this.style.background='transparent'">
                                    <td style="padding:0.875rem 1.25rem;">
                                        <input type="checkbox" name="resi_ids[]" value="{{ $resi->id }}" style="width:16px; height:16px; accent-color:#4f46e5; cursor:pointer;" class="resi-checkbox">
                                    </td>
                                    <td style="padding:0.875rem 1.25rem; cursor:pointer;" onclick="const cb=this.parentElement.querySelector('.resi-checkbox'); cb.checked=!cb.checked; updateCount();">
                                        <div style="display:flex; align-items:center; gap:0.5rem;">
                                            <span style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.85rem; color:var(--ink);">{{ $resi->resi_number }}</span>
                                            <span style="font-size:0.6rem; font-weight:700; padding:2px 6px; border-radius:4px; background:{{ $resi->shipment_type === 'AIR' ? '#e0e7ff' : ($resi->shipment_type === 'HANDCARRY' ? '#fce7f3' : '#dcfce7') }}; color:{{ $resi->shipment_type === 'AIR' ? '#4338ca' : ($resi->shipment_type === 'HANDCARRY' ? '#be185d' : '#15803d') }};">{{ $resi->shipment_type }}</span>
                                        </div>
                                        <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.2rem;">
                                            <span style="font-weight:600; color:var(--ink);">{{ $resi->customer_name_snapshot ?: ($resi->user?->name ?? '—') }}</span>
                                            @if($resi->item_name)
                                                <span>&bull; {{ Str::limit($resi->item_name, 40) }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding:0.875rem 1.25rem;">
                                        @if($resi->status === 'arrived_wh_china')
                                            <span style="font-size:0.68rem; background:#ecfdf5; color:#059669; padding:0.25rem 0.5rem; border-radius:4px; font-weight:700; white-space:nowrap;">Tiba WH CN</span>
                                        @elseif($resi->status === 'waiting_arrival')
                                            <span style="font-size:0.68rem; background:#fef3c7; color:#d97706; padding:0.25rem 0.5rem; border-radius:4px; font-weight:700; white-space:nowrap;">Menunggu</span>
                                        @else
                                            <span style="font-size:0.68rem; background:#f1f5f9; color:#64748b; padding:0.25rem 0.5rem; border-radius:4px; font-weight:700;">{{ $resi->status_label }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding:3rem 1.25rem; text-align:center; color:var(--ink-soft);">
                        <p>Tidak ada resi yang belum masuk box.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Detail Box --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">
            <div style="background:var(--surface); border:1px solid var(--line); border-radius:14px; padding:1.25rem;">
                <div style="font-weight:700; font-size:0.95rem; margin-bottom:1rem;">Detail Box</div>

                @error('resi_ids')
                    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:0.75rem; font-size:0.85rem; color:#dc2626; margin-bottom:1rem;">Pilih minimal 1 resi.</div>
                @enderror

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; color:var(--ink);">Nama Box</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Box 1, Box 2 SEA..." style="width:100%; padding:0.5rem 0.75rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem; background:var(--bg); color:var(--ink); outline:none; box-sizing:border-box;">
                    @error('name')<p style="color:#dc2626; font-size:0.78rem; margin-top:0.25rem;">{{ $message }}</p>@enderror
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; color:var(--ink);">Tipe Pengiriman *</label>
                    <select name="shipment_type" id="shipmentType" onchange="filterResis()" style="width:100%; padding:0.5rem 0.75rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem; background:var(--bg); color:var(--ink); outline:none;">
                        <option value="SEA" {{ old('shipment_type') === 'SEA' ? 'selected' : '' }}>🚢 SEA (Laut)</option>
                        <option value="AIR" {{ old('shipment_type') === 'AIR' ? 'selected' : '' }}>✈️ AIR (Udara)</option>
                        <option value="HANDCARRY" {{ old('shipment_type') === 'HANDCARRY' ? 'selected' : '' }}>👜 HANDCARRY</option>
                    </select>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; color:var(--ink);">Status Awal</label>
                    <select name="status" style="width:100%; padding:0.5rem 0.75rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem; background:var(--bg); color:var(--ink); outline:none;">
                        <option value="packing">📦 Packing (Belum OTW)</option>
                        <option value="in_transit" selected>🚚 OTW Indonesia</option>
                    </select>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; color:var(--ink);">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Opsional..." style="width:100%; padding:0.5rem 0.75rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem; background:var(--bg); color:var(--ink); outline:none; resize:none; box-sizing:border-box;">{{ old('notes') }}</textarea>
                </div>

                <button type="submit" style="width:100%; padding:0.65rem; background:var(--ink); color:#fff; font-size:0.9rem; font-weight:700; border:none; border-radius:10px; cursor:pointer;">
                    Buat Box Shipment
                </button>
            </div>

            {{-- Selected count --}}
            <div style="background:var(--surface); border:1px solid var(--line); border-radius:12px; padding:1rem 1.25rem; text-align:center;">
                <div id="selectedCount" style="font-size:2rem; font-weight:800; color:#4f46e5;">0</div>
                <div style="font-size:0.8rem; color:var(--ink-soft);">Resi dipilih</div>
            </div>
        </div>

    </div>
</form>

<script>
function filterResis() {
    const q = document.getElementById('resiSearch').value.toLowerCase();
    const type = document.getElementById('shipmentType').value;
    document.querySelectorAll('.resi-item').forEach(item => {
        const matchesText = item.textContent.toLowerCase().includes(q);
        const matchesType = item.dataset.type === type;
        if (matchesText && matchesType) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
            // Uncheck if hidden by filter
            const cb = item.querySelector('.resi-checkbox');
            if (cb && cb.checked) {
                cb.checked = false;
            }
        }
    });
    updateCount();
}
function selectAll() {
    document.querySelectorAll('.resi-item').forEach(item => {
        if (item.style.display !== 'none') {
            item.querySelector('.resi-checkbox').checked = true;
        }
    });
    updateCount();
}
function deselectAll() {
    document.querySelectorAll('input[name="resi_ids[]"]').forEach(cb => cb.checked = false);
    updateCount();
}
function updateCount() {
    const n = document.querySelectorAll('input[name="resi_ids[]"]:checked').length;
    document.getElementById('selectedCount').textContent = n;
}
document.querySelectorAll('input[name="resi_ids[]"]').forEach(cb => cb.addEventListener('change', updateCount));

// Initial filter to respect default select value
document.addEventListener('DOMContentLoaded', filterResis);
</script>
@endsection
