@extends('layouts.admin')
@section('title', 'Detail Box: ' . ($shipment->name ?: $shipment->code))

@section('content')
<style>
    .shipment-detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start; }
    .header-box { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
    
    @media (max-width: 850px) {
        .shipment-detail-grid { grid-template-columns: 1fr !important; }
    }
</style>

<div class="header-box">
    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
        <a href="{{ route('admin.shipments.index') }}" style="display:flex; align-items:center; justify-content:center; width:34px; height:34px; background:var(--surface); border:1px solid var(--line); border-radius:8px; color:var(--ink); text-decoration:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 style="font-size:1.4rem; font-weight:800; color:var(--ink); margin:0;">{{ $shipment->name ?: 'Box #' . $shipment->id }} <span style="font-size:0.9rem; font-weight:600; font-family:'Space Mono',monospace; color:var(--ink-soft); margin-left:0.5rem;">{{ $shipment->code }}</span></h1>
            <div style="display:flex; align-items:center; gap:0.75rem; margin-top:0.25rem; flex-wrap:wrap;">
                @if($shipment->shipment_type === 'AIR')
                    <span style="font-size:0.7rem; font-weight:700; background:#e0e7ff; color:#4f46e5; padding:0.15rem 0.5rem; border-radius:20px;">✈️ AIR</span>
                @else
                    <span style="font-size:0.7rem; font-weight:700; background:#ccfbf1; color:#0d9488; padding:0.15rem 0.5rem; border-radius:20px;">🚢 SEA</span>
                @endif
                <span style="font-size:0.8rem; color:var(--ink-soft);">
                    Dibuat oleh {{ $shipment->creator->name }} pada {{ $shipment->created_at->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>
    <div>
        @if($shipment->status === 'packing')
            <span style="font-size:0.875rem; font-weight:700; background:#fef3c7; color:#d97706; padding:0.4rem 1rem; border-radius:8px;">📦 Sedang Packing</span>
        @elseif($shipment->status === 'in_transit')
            <span style="font-size:0.875rem; font-weight:700; background:#eff6ff; color:#2563eb; padding:0.4rem 1rem; border-radius:8px;">🚚 Sedang OTW (In Transit)</span>
        @elseif($shipment->status === 'arrived_indonesia')
            <span style="font-size:0.875rem; font-weight:700; background:#f0fdf4; color:#16a34a; padding:0.4rem 1rem; border-radius:8px;">✅ Sudah Tiba di Indonesia</span>
        @else
            <span style="font-size:0.875rem; font-weight:700; background:#f1f5f9; color:#64748b; padding:0.4rem 1rem; border-radius:8px;">{{ $shipment->status }}</span>
        @endif
    </div>
</div>

<div class="shipment-detail-grid">
    {{-- Left: Resi List --}}
    <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); overflow:hidden;">
        <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid var(--line); flex-wrap:wrap; gap:0.5rem;">
            <div>
                <h3 style="font-size:1.1rem; font-weight:800; color:var(--ink); margin:0;">Isi Box ({{ $shipment->resis->count() }} Resi)</h3>
            </div>
            @if($shipment->status === 'packing')
            <button onclick="document.getElementById('add-resi-panel').style.display = document.getElementById('add-resi-panel').style.display === 'none' ? 'block' : 'none'" style="font-size:0.8rem; font-weight:600; padding:0.4rem 0.8rem; background:var(--ink); color:#fff; border:none; border-radius:8px; cursor:pointer; transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">+ Tambah Barang</button>
            @endif
        </div>
        {{-- Add Resi Panel --}}
        <div id="add-resi-panel" style="display:none; padding:1.5rem; background:#f8fafc; border-bottom:1px solid var(--line);">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <h4 style="font-size:0.95rem; font-weight:800; color:var(--ink); margin:0;">Pilih Resi untuk Ditambahkan</h4>
                <button type="button" onclick="document.getElementById('add-resi-panel').style.display='none'" style="background:none; border:none; cursor:pointer; color:var(--ink-soft);">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            @if($availableResis->count() > 0)
                <div style="margin-bottom: 1rem;">
                    <input type="text" id="search-resi" placeholder="🔍 Cari nomor resi, barang, atau nama customer..." style="width:100%; padding:0.65rem 1rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem; transition:border-color 0.2s;" onfocus="this.style.borderColor='var(--ink)'" onblur="this.style.borderColor='var(--line)'" onkeyup="filterResi()">
                </div>
                <form method="POST" action="{{ route('admin.shipments.addResis', $shipment) }}">
                    @csrf
                    <div style="max-height:300px; overflow-y:auto; border:1px solid var(--line); border-radius:12px; background:var(--white); margin-bottom:1rem; overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse; min-width:500px;" id="resi-table">
                            <thead style="background:#f1f5f9; position:sticky; top:0; z-index:10;">
                                <tr>
                                    <th style="padding:0.75rem 1rem; text-align:left; width:40px;"></th>
                                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Resi & Barang</th>
                                    <th style="padding:0.75rem 1rem; text-align:left; font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase;">Customer</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availableResis as $resi)
                                    <tr class="resi-row" style="border-bottom:1px solid var(--line);">
                                        <td style="padding:0.75rem 1rem;">
                                            <input type="checkbox" name="resi_ids[]" value="{{ $resi->id }}" style="width:16px; height:16px; cursor:pointer;">
                                        </td>
                                        <td style="padding:0.75rem 1rem;" class="resi-info">
                                            <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.875rem;">{{ $resi->resi_number }}</div>
                                            <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.15rem;">{{ $resi->item_name ?: 'Tanpa Nama' }}</div>
                                        </td>
                                        <td style="padding:0.75rem 1rem;" class="resi-customer">
                                            <div style="font-weight:600; font-size:0.875rem;">{{ $resi->user->name ?? $resi->customer_name_snapshot ?? '-' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align:right;">
                        <button type="submit" style="padding:0.6rem 1.25rem; background:#10b981; color:#fff; font-weight:700; font-size:0.875rem; border:none; border-radius:8px; cursor:pointer;">+ Masukkan ke Box</button>
                    </div>
                </form>
                <script>
                    function filterResi() {
                        let input = document.getElementById("search-resi");
                        let filter = input.value.toLowerCase();
                        let table = document.getElementById("resi-table");
                        let tr = table.getElementsByClassName("resi-row");

                        for (let i = 0; i < tr.length; i++) {
                            let tdInfo = tr[i].getElementsByClassName("resi-info")[0];
                            let tdCustomer = tr[i].getElementsByClassName("resi-customer")[0];
                            if (tdInfo || tdCustomer) {
                                let txtValue = (tdInfo.textContent || tdInfo.innerText) + " " + (tdCustomer.textContent || tdCustomer.innerText);
                                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                            }       
                        }
                    }
                </script>
            @else
                <div style="text-align:center; padding:2rem; background:var(--white); border-radius:12px; border:1px dashed var(--line);">
                    <p style="color:var(--ink-soft); font-size:0.875rem; margin:0;">Tidak ada resi menganggur (unassigned) yang tersedia.</p>
                </div>
            @endif
        </div>

        <div style="overflow-x:auto; width:100%;">
            <table style="width:100%; border-collapse:collapse; min-width:550px;">
                <thead>
                    <tr style="background:rgba(248,250,252,0.5); border-bottom:1px solid var(--line);">
                        <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; white-space:nowrap;">Nomor Resi</th>
                        <th style="padding:0.75rem 1rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); letter-spacing:0.05em; text-transform:uppercase; white-space:nowrap;">User / Item</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.72rem; font-weight:700; color:var(--ink-soft); letter-spacing:0.05em; text-transform:uppercase; white-space:nowrap;">Berat Final</th>
                        <th style="padding:0.75rem 1rem; text-align:right; font-size:0.72rem; font-weight:700; color:var(--ink-soft); letter-spacing:0.05em; text-transform:uppercase; white-space:nowrap;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipment->resis as $resi)
                        <tr style="border-bottom:1px dashed var(--line);">
                            <td style="padding:0.875rem 1rem; white-space:nowrap;">
                                <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.875rem; color:var(--ink);">{{ $resi->resi_number }}</div>
                                <div style="font-size:0.7rem; color:var(--ink-soft); margin-top:0.2rem;"><x-status-badge :status="$resi->status" /></div>
                            </td>
                            <td style="padding:0.875rem 1rem;">
                                <div style="font-weight:700; font-size:0.875rem; color:var(--ink); white-space:nowrap;">{{ $resi->user->name ?? $resi->customer_name_snapshot ?? 'Unmatched' }}</div>
                                <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.1rem; white-space:nowrap;">{{ $resi->item_name ?: '-' }} &bull; {{ $resi->quantity }} pcs</div>
                            </td>
                            <td style="padding:0.875rem 1rem; text-align:right; font-weight:700; color:var(--ink); white-space:nowrap;">
                                {{ $resi->final_weight_gram ? $resi->final_weight_gram . ' gram' : '-' }}
                            </td>
                            <td style="padding:0.875rem 1rem; text-align:right; white-space:nowrap;">
                                @if(in_array($shipment->status, ['pending', 'packing']))
                                    <button type="button" onclick="openMoveModal('{{ $resi->id }}')" style="background:#f1f5f9; color:#475569; border:none; border-radius:8px; padding:6px 12px; font-size:0.75rem; font-weight:700; cursor:pointer;">
                                        Pindah
                                    </button>
                                @else
                                    <span style="font-size:0.72rem; color:#94a3b8; font-style:italic;">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right: Status & Actions --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">
        
        {{-- Status Box --}}
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); padding:1.25rem;">
            <h3 style="font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:1rem;">Update Status Perjalanan</h3>
            
            <div style="display:flex; flex-direction:column; gap:1rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:1rem; border-bottom:1px dashed var(--line); flex-wrap:wrap; gap:0.5rem;">
                    <div>
                        <div style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.2rem;">Berangkat dari China</div>
                        <div style="font-size:0.875rem; font-weight:600; color:var(--ink);">{{ $shipment->departed_at ? $shipment->departed_at->format('d M Y') : 'Belum Berangkat' }}</div>
                    </div>
                    @if($shipment->status === 'packing')
                        <form method="POST" action="{{ route('admin.shipments.update_status', $shipment) }}" onsubmit="return confirm('Tandai box ini sudah berangkat (OTW)?')">
                            @csrf
                            <input type="hidden" name="status" value="in_transit">
                            <button class="btn" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; font-size:0.75rem; padding:0.4rem 0.8rem; border-radius:6px; font-weight:600; cursor:pointer;">Set Berangkat</button>
                        </form>
                    @endif
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem;">
                    <div>
                        <div style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.2rem;">Tiba di Indonesia</div>
                        <div style="font-size:0.875rem; font-weight:600; color:var(--ink);">{{ $shipment->arrived_at ? $shipment->arrived_at->format('d M Y') : 'Belum Tiba' }}</div>
                    </div>
                </div>
                
                @if($shipment->status === 'in_transit')
                    <div style="margin-top:0.5rem; padding-top:1rem; border-top:1px dashed var(--line);">
                        <form method="POST" action="{{ route('admin.shipments.markArrived', $shipment) }}" enctype="multipart/form-data">
                            @csrf
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem;">Tanggal Tiba *</label>
                                <input type="date" name="arrived_at" value="{{ date('Y-m-d') }}" required style="width:100%; padding:0.5rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem;">
                            </div>
                            <div style="margin-bottom:1rem;">
                                <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem;">Foto Bukti Tiba (Opsional)</label>
                                <input type="file" name="photo" accept="image/*" style="width:100%; padding:0.4rem; border:1px solid var(--line); border-radius:8px; font-size:0.8rem;">
                            </div>
                            <button type="submit" style="width:100%; padding:0.65rem; background:#16a34a; color:#fff; font-size:0.875rem; font-weight:700; border:none; border-radius:10px; cursor:pointer;">
                                Tandai Tiba di Indonesia
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Foto Box Upload Card --}}
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); overflow:hidden;">
            <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--line); background:#fafafa; display:flex; align-items:center; gap:0.5rem;">
                <svg width="16" height="16" fill="none" stroke="#4f46e5" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span style="font-size:0.9rem; font-weight:800; color:var(--ink);">Foto Box Pengiriman</span>
            </div>
            <div style="padding:1.1rem 1.25rem;">
                @if($shipment->photo_box)
                    <div style="margin-bottom:1rem;">
                        <img src="{{ Storage::url($shipment->photo_box) }}" alt="Foto Box"
                            style="width:100%; border-radius:10px; border:1.5px solid var(--line); object-fit:cover; aspect-ratio:4/3; cursor:pointer;"
                            onclick="window.open(this.src, '_blank')">
                        <div style="font-size:0.72rem; color:var(--ink-soft); margin-top:0.4rem; text-align:center;">
                            Diupload {{ $shipment->photo_box_uploaded_at?->format('d M Y, H:i') }}
                        </div>
                    </div>
                @else
                    <div style="background:#f8fafc; border:1.5px dashed var(--line); border-radius:10px; padding:1rem; text-align:center; margin-bottom:1rem;">
                        <svg width="32" height="32" fill="none" stroke="#94a3b8" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 0.5rem;display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p style="font-size:0.78rem; color:var(--ink-soft); margin:0;">Belum ada foto box.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.shipments.photo-box', $shipment) }}" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom:0.75rem;">
                        <label style="display:block; font-size:0.75rem; font-weight:700; color:var(--ink); margin-bottom:0.35rem;">
                            {{ $shipment->photo_box ? '🔄 Ganti Foto Box' : '📷 Upload Foto Box' }}
                        </label>
                        <input type="file" name="photo_box" accept="image/*" required
                            style="width:100%; font-size:0.8rem; padding:4px; border:1px solid var(--line); border-radius:8px; background:#f8fafc;">
                        @error('photo_box')
                            <p style="color:#dc2626; font-size:0.75rem; margin-top:0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        style="width:100%; padding:0.6rem; background:#4f46e5; color:#fff; font-size:0.85rem; font-weight:700; border:none; border-radius:10px; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Upload &amp; Kirim Notif Customer
                    </button>
                    <p style="font-size:0.7rem; color:var(--ink-soft); text-align:center; margin-top:0.5rem;">
                        @php
                            $uniqueCustomerCount = $shipment->resis->pluck('user_id')->filter()->unique()->count();
                            $uniqueEmails = $shipment->resis->pluck('user.email')->filter()->unique()->count();
                        @endphp
                        Foto akan dikirim via email ke {{ $uniqueEmails }} customer ({{ $shipment->resis->count() }} resi)
                    </p>
                </form>
            </div>
        </div>

        {{-- Tagihan Box --}}
        @if($shipment->status === 'arrived_indonesia' || count($shipment->invoices) > 0)
        <div style="background:var(--white); border-radius:16px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); padding:1.25rem;">
            <h3 style="font-size:1rem; font-weight:800; color:var(--ink); margin-bottom:1rem;">Tagihan & Invoice</h3>
            
            @if(count($shipment->invoices) > 0)
                <p style="font-size:0.85rem; color:var(--ink-soft); margin-bottom:1rem;">Invoice telah dibuat untuk shipment ini.</p>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @foreach($shipment->invoices as $inv)
                        <div style="display:flex; justify-content:space-between; padding:0.75rem; border:1px solid var(--line); border-radius:8px; flex-wrap:wrap; gap:0.4rem;">
                            <div>
                                <div style="font-family:'Space Mono',monospace; font-size:0.8rem; font-weight:700;">{{ $inv->invoice_number }}</div>
                                <div style="font-size:0.75rem; color:var(--ink-soft);">{{ $inv->user->name }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:0.8rem; font-weight:700;">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</div>
                                @php $color = $inv->status === 'paid' ? '#16a34a' : '#d97706'; @endphp
                                <div style="font-size:0.7rem; color:{{ $color }};">{{ strtoupper($inv->status) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <form method="POST" action="{{ route('admin.shipments.generate-invoices', $shipment) }}">
                    @csrf
                    <p style="font-size:0.8rem; color:var(--ink-soft); margin-bottom:1rem;">Masukkan Rate pengiriman dan pastikan setiap resi memiliki berat final untuk menghasilkan tagihan secara otomatis bagi customer.</p>
                    
                    @php
                        $defaultRate = $shipment->shipment_type === 'AIR' ? 245 : 90;
                    @endphp
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem;">Rate per Gram (Rp)</label>
                        <input type="number" name="rate_per_gram" value="{{ old('rate_per_gram', $defaultRate) }}" required style="width:100%; padding:0.5rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem;">
                    </div>
                    
                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem;">Handling Fee / Tambahan (Opsional)</label>
                        <input type="number" name="handling_fee" value="{{ old('handling_fee', 0) }}" style="width:100%; padding:0.5rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem;">
                    </div>

                    <div style="margin-bottom:1rem;">
                        <label style="display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem;">Biaya Packing / Resi (Opsional)</label>
                        <input type="number" name="packing_fee" value="{{ old('packing_fee', 0) }}" style="width:100%; padding:0.5rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem;">
                    </div>

                    <div style="margin-bottom:1.5rem;">
                        <label style="display:block; font-size:0.8rem; font-weight:700; margin-bottom:0.75rem; color:var(--ink); padding-bottom:0.5rem; border-bottom:1px solid var(--line);">Input Berat &amp; Handling Fee Per Resi</label>
                        @foreach($shipment->resis as $resi)
                            <div style="background:#f8fafc; border:1px solid var(--line); border-radius:10px; padding:0.75rem; margin-bottom:0.6rem;">
                                <div style="font-size:0.78rem; font-weight:700; margin-bottom:0.4rem; display:flex; justify-content:space-between; align-items:center;">
                                    <span style="font-family:'Space Mono',monospace;">{{ $resi->resi_number }}</span>
                                    <span style="color:var(--ink-soft); font-weight:500;">{{ $resi->user->name ?? $resi->customer_name_snapshot }}</span>
                                </div>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.5rem;">
                                    <div>
                                        <label style="display:block; font-size:0.68rem; font-weight:700; color:var(--ink-soft); margin-bottom:0.2rem;">Berat (Gram) *</label>
                                        <input type="number" step="1" name="weights[{{ $resi->id }}]" value="{{ old('weights.'.$resi->id, $resi->final_weight_gram) }}" required style="width:100%; padding:0.35rem 0.5rem; border:1px solid var(--line); border-radius:6px; font-size:0.8rem;" placeholder="Gram">
                                    </div>
                                    <div>
                                        <label style="display:block; font-size:0.68rem; font-weight:700; color:var(--ink-soft); margin-bottom:0.2rem;">Handling Fee (Rp)</label>
                                        <input type="number" step="1" name="handling_fees[{{ $resi->id }}]" value="{{ old('handling_fees.'.$resi->id, $resi->fee_wh ?: 0) }}" style="width:100%; padding:0.35rem 0.5rem; border:1px solid var(--line); border-radius:6px; font-size:0.8rem;" placeholder="Fee Rp">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" style="width:100%; padding:0.75rem; background:var(--ink); color:#fff; font-size:0.875rem; font-weight:700; border:none; border-radius:10px; cursor:pointer;">
                        Generate Invoice
                    </button>
                </form>
            @endif
        </div>
        @endif

    </div>
</div>

{{-- Move Resi Modal --}}
<div id="moveResiModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center; padding:1rem;">
    <div style="background:white; border-radius:16px; padding:1.5rem; width:100%; max-width:400px; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
        <h3 style="font-size:1.1rem; font-weight:800; margin-bottom:0.75rem; color:var(--ink);">Pindah Box</h3>
        <p style="font-size:0.85rem; color:var(--ink-soft); margin-bottom:1.25rem;">Pilih box tujuan untuk resi ini.</p>
        
        <form id="moveResiForm" method="POST" action="">
            @csrf
            <div style="margin-bottom:1.25rem;">
                <label style="display:block; font-size:0.8rem; font-weight:700; margin-bottom:0.4rem;">Pilih Box Tujuan</label>
                <select name="target_shipment_id" required style="width:100%; padding:0.65rem; border:1px solid var(--line); border-radius:8px; font-size:0.875rem;">
                    <option value="">-- Pilih Box --</option>
                    @foreach($activeShipments as $as)
                        <option value="{{ $as->id }}">{{ $as->name }} ({{ $as->code }}) - {{ $as->status }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex; gap:0.75rem;">
                <button type="button" onclick="closeMoveModal()" style="flex:1; padding:0.65rem; background:#f1f5f9; color:#475569; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Batal</button>
                <button type="submit" style="flex:1; padding:0.65rem; background:var(--ink); color:white; border:none; border-radius:8px; font-weight:700; cursor:pointer;">Pindahkan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openMoveModal(resiId) {
        document.getElementById('moveResiModal').style.display = 'flex';
        document.getElementById('moveResiForm').action = "{{ route('admin.shipments.show', $shipment) }}/resis/" + resiId + "/move";
    }
    function closeMoveModal() {
        document.getElementById('moveResiModal').style.display = 'none';
    }
</script>
@endsection
