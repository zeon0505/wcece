@extends('layouts.app')
@section('title', 'Dashboard Tracking')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:2rem;">
    <div>
        <h1 style="font-size:1.85rem; font-weight:800; background:linear-gradient(90deg, var(--ink), #4f46e5); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">Halo, {{ auth()->user()->name }} 👋</h1>
        <p style="margin-top:0.4rem; font-size:0.9rem; color:var(--ink-soft);">Pantau semua paket kiriman Anda dari China ke Indonesia.</p>
    </div>
</div>

{{-- 4 Stat Cards --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem; margin-bottom:2.5rem;">
    @php $totalResiAktif = $counts->except('completed')->sum(); @endphp
    <div style="background:var(--white); padding:1.25rem 1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); transition:transform 0.2s, box-shadow 0.2s; position:relative; overflow:hidden;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:radial-gradient(circle, rgba(79,70,229,0.08) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
            <div style="width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg, #e0e7ff, #ede9fe); color:#4f46e5; display:flex; align-items:center; justify-content:center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <span style="font-size:0.65rem; font-weight:800; color:#4f46e5; background:rgba(79,70,229,0.1); padding:0.25rem 0.6rem; border-radius:20px;">TOTAL</span>
        </div>
        <p style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Paket Aktif</p>
        <p style="font-size:2.25rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.1; margin-top:0.15rem;">{{ $totalResiAktif }}</p>
    </div>

    @php $waitingCount = $counts->get('waiting_arrival', 0) + $counts->get('arrived_wh_china', 0); @endphp
    <div style="background:var(--white); padding:1.25rem 1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); transition:transform 0.2s, box-shadow 0.2s; position:relative; overflow:hidden;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:radial-gradient(circle, rgba(217,119,6,0.08) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
            <div style="width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg, #fef3c7, #ffedd5); color:#d97706; display:flex; align-items:center; justify-content:center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span style="font-size:0.65rem; font-weight:800; color:#d97706; background:rgba(217,119,6,0.1); padding:0.25rem 0.6rem; border-radius:20px;">CHINA</span>
        </div>
        <p style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Di Gudang China</p>
        <p style="font-size:2.25rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.1; margin-top:0.15rem;">{{ $waitingCount }}</p>
    </div>

    @php $transitCount = $counts->get('in_transit', 0); @endphp
    <div style="background:var(--white); padding:1.25rem 1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); transition:transform 0.2s, box-shadow 0.2s; position:relative; overflow:hidden;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
            <div style="width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg, #d1fae5, #a7f3d0); color:#059669; display:flex; align-items:center; justify-content:center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span style="font-size:0.65rem; font-weight:800; color:#059669; background:rgba(16,185,129,0.1); padding:0.25rem 0.6rem; border-radius:20px;">PROSES</span>
        </div>
        <p style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Dalam Pengiriman</p>
        <p style="font-size:2.25rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.1; margin-top:0.15rem;">{{ $transitCount }}</p>
    </div>

    <div style="background:var(--white); padding:1.25rem 1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); transition:transform 0.2s, box-shadow 0.2s; position:relative; overflow:hidden;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:80px; height:80px; background:radial-gradient(circle, rgba(220,38,38,0.08) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
            <div style="width:42px; height:42px; border-radius:12px; background:linear-gradient(135deg, #fee2e2, #fecaca); color:#dc2626; display:flex; align-items:center; justify-content:center;">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span style="font-size:0.65rem; font-weight:800; color:#dc2626; background:rgba(220,38,38,0.1); padding:0.25rem 0.6rem; border-radius:20px;">TAGIHAN</span>
        </div>
        <p style="font-size:0.7rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Belum Dibayar</p>
        <p style="font-size:2.25rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.1; margin-top:0.15rem;">{{ $unpaidInvoicesCount }}</p>
    </div>
</div>

{{-- Status Filter Tabs --}}
<div style="display:flex; overflow-x:auto; gap:0.5rem; background:var(--white); padding:0.5rem; border-radius:14px; border:1px solid var(--line); margin-bottom:1.5rem; box-shadow:0 4px 15px rgba(0,0,0,0.02);">
    @php
        $isAllActive = $statusFilter === 'all';
        $allStyle = $isAllActive ? 'background:var(--ink); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);' : 'background:transparent; color:var(--ink-soft);';
    @endphp
    <a href="{{ route('dashboard') }}" style="padding:0.6rem 1.2rem; border-radius:10px; font-size:0.8rem; font-weight:600; text-decoration:none; white-space:nowrap; transition:all 0.2s; {{ $allStyle }}">
        Semua ({{ $counts->sum() }})
    </a>
    @foreach(\App\Models\Resi::$statusLabels as $key => $label)
        @php
            $isActive = $statusFilter === $key;
            $tabStyle = $isActive ? 'background:var(--ink); color:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.1);' : 'background:transparent; color:var(--ink-soft);';
        @endphp
        <a href="{{ route('dashboard', ['status' => $key]) }}" style="padding:0.6rem 1.2rem; border-radius:10px; font-size:0.8rem; font-weight:600; text-decoration:none; white-space:nowrap; transition:all 0.2s; {{ $tabStyle }}">
            {{ $label }}
            @if($counts->get($key)) ({{ $counts->get($key) }}) @endif
        </a>
    @endforeach
</div>

{{-- Tracking Table --}}
<div style="background:var(--white); border-radius:24px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); overflow:hidden;">
    @if($resis->count())
    <div style="overflow-x:auto; width:100%;">
        <table style="width:100%; border-collapse:collapse; font-size:0.875rem; min-width:680px;">
            <thead>
                <tr style="background:#fafafa; border-bottom:2px solid var(--line);">
                    <th style="padding:1rem 1.25rem; text-align:left; font-size:0.7rem; font-weight:800; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">Nama Barang</th>
                    <th style="padding:1rem 1.25rem; text-align:left; font-size:0.7rem; font-weight:800; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">Tracking Number</th>
                    <th style="padding:1rem 1.25rem; text-align:center; font-size:0.7rem; font-weight:800; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">Arrived China</th>
                    <th style="padding:1rem 1.25rem; text-align:center; font-size:0.7rem; font-weight:800; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">Status</th>
                    <th style="padding:1rem 1.25rem; text-align:center; font-size:0.7rem; font-weight:800; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">Arrived Indo</th>
                    <th style="padding:1rem 1.25rem; text-align:right; font-size:0.7rem; font-weight:800; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">Tagihan</th>
                    <th style="padding:1rem 1.25rem; text-align:center; font-size:0.7rem; font-weight:800; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.06em; white-space:nowrap;">Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resis as $resi)
                @php
                    // Determine arrived china status
                    $arrivedChina = in_array($resi->status, ['arrived_wh_china','in_transit','arrived_indonesia','awaiting_payment','ready_to_ship','completed']) || !empty($resi->photo_wh_china) || !empty($resi->master_shipment_id);
                    $arrivedIndo = in_array($resi->status, ['arrived_indonesia','awaiting_payment','ready_to_ship','completed']);
                    
                    // Get invoice for this resi's shipment
                    $invoice = $resi->master_shipment_id ? ($invoicesByShipment[$resi->master_shipment_id] ?? null) : null;
                    $isPaid = $invoice && $invoice->status === 'paid';
                    $isUnpaid = $invoice && in_array($invoice->status, ['unpaid', 'pending_verification']);
                @endphp
                <tr style="border-bottom:1px solid var(--line); transition:background 0.15s; cursor:pointer;" onclick="window.location='{{ route('resi.show', $resi) }}'" onmouseover="this.style.background='rgba(248,250,252,0.8)'" onmouseout="this.style.background='transparent'">
                    {{-- Nama Barang --}}
                    <td style="padding:1rem 1.25rem;">
                        <div style="font-weight:700; color:var(--ink); max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $resi->item_name ?: '—' }}</div>
                        <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.2rem;">{{ $resi->quantity }} pcs · {{ $resi->shipment_type ?? 'SEA' }}</div>
                    </td>

                    {{-- Tracking Number --}}
                    <td style="padding:1rem 1.25rem;">
                        <span style="font-family:'Space Mono',monospace; font-weight:800; font-size:0.85rem; color:var(--ink);">{{ $resi->resi_number }}</span>
                    </td>

                    {{-- Arrived China --}}
                    <td style="padding:1rem 1.25rem; text-align:center;">
                        @if($arrivedChina)
                            <div style="display:inline-flex; align-items:center; gap:0.4rem;">
                                <div style="width:28px; height:28px; background:#d1fae5; color:#059669; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            @if($resi->photo_wh_china)
                                <a href="{{ Storage::url($resi->photo_wh_china) }}" target="_blank" style="display:block; margin-top:0.4rem; font-size:0.7rem; color:#4f46e5; text-decoration:none; font-weight:600;" onclick="event.stopPropagation();">📷 Foto</a>
                            @endif
                        @else
                            <div style="width:28px; height:28px; background:#f1f5f9; color:#94a3b8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div style="font-size:0.65rem; color:var(--ink-soft); margin-top:0.3rem;">Menunggu</div>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td style="padding:1rem 1.25rem; text-align:center;">
                        <x-status-badge :status="$resi->status" />
                    </td>

                    {{-- Arrived Indo --}}
                    <td style="padding:1rem 1.25rem; text-align:center;">
                        @if($arrivedIndo)
                            <div style="display:inline-flex; align-items:center; gap:0.4rem;">
                                <div style="width:28px; height:28px; background:#d1fae5; color:#059669; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            @if($resi->photo_arrived_id)
                                <a href="{{ Storage::url($resi->photo_arrived_id) }}" target="_blank" style="display:block; margin-top:0.4rem; font-size:0.7rem; color:#4f46e5; text-decoration:none; font-weight:600;" onclick="event.stopPropagation();">📷 Foto Timbangan</a>
                            @endif
                            @if($resi->final_weight_gram)
                                <div style="font-size:0.7rem; color:var(--ink-soft); margin-top:0.2rem; font-weight:600;">⚖️ {{ number_format($resi->final_weight_gram, 0, ',', '.') }} gram</div>
                            @endif
                        @else
                            <div style="width:28px; height:28px; background:#f1f5f9; color:#94a3b8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/></svg>
                            </div>
                            <div style="font-size:0.65rem; color:var(--ink-soft); margin-top:0.3rem;">—</div>
                        @endif
                    </td>

                    {{-- Tagihan --}}
                    <td style="padding:1rem 1.25rem; text-align:right;">
                        @if($invoice)
                            <span style="font-weight:700; color:var(--ink); font-size:0.85rem;">{{ $invoice->formatted_total }}</span>
                        @else
                            <span style="color:var(--ink-soft); font-size:0.8rem;">—</span>
                        @endif
                    </td>

                    {{-- Payment Status --}}
                    <td style="padding:1rem 1.25rem; text-align:center;">
                        @if($isPaid)
                            <span style="display:inline-flex; align-items:center; gap:0.3rem; font-size:0.75rem; font-weight:800; color:#059669; background:#d1fae5; padding:0.35rem 0.75rem; border-radius:20px;">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                PAID
                            </span>
                        @elseif($isUnpaid)
                            <a href="{{ route('invoices.show', $invoice) }}" style="display:inline-flex; align-items:center; gap:0.3rem; font-size:0.75rem; font-weight:800; color:#dc2626; background:#fee2e2; padding:0.35rem 0.75rem; border-radius:20px; text-decoration:none; transition:all 0.2s; border:1px solid #fecaca;" onclick="event.stopPropagation();" onmouseover="this.style.background='#dc2626'; this.style.color='#fff';" onmouseout="this.style.background='#fee2e2'; this.style.color='#dc2626';">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $invoice->status === 'pending_verification' ? 'VERIFYING' : 'UNPAID' }}
                            </a>
                        @else
                            <span style="font-size:0.75rem; color:var(--ink-soft);">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div style="text-align:center; padding:5rem 2rem;">
            <div style="width:64px; height:64px; background:var(--surface); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
                <svg width="32" height="32" fill="none" stroke="var(--line-dash)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <p style="font-weight:700; font-size:1.1rem; color:var(--ink);">Belum ada resi</p>
            <p style="font-size:0.875rem; color:var(--ink-soft); margin-top:0.5rem; max-width:300px; margin-left:auto; margin-right:auto;">
                @if($statusFilter !== 'all')
                    Tidak ada paket dengan status ini.
                @else
                    Input nomor resi pertama Anda dari seller China untuk memulai pelacakan pengiriman.
                @endif
            </p>
            @if($statusFilter === 'all')
                <a href="{{ route('resi.create') }}" style="display:inline-flex; align-items:center; gap:0.5rem; margin-top:1.5rem; background:var(--ink); color:#fff; font-size:0.875rem; font-weight:700; padding:0.7rem 1.5rem; border-radius:12px; text-decoration:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Input Resi Baru
                </a>
            @endif
        </div>
    @endif
</div>

{{-- Pagination --}}
@if($resis->hasPages())
    <div style="margin-top:1.5rem;">
        {!! $resis->links('vendor.pagination.simple') !!}
    </div>
@endif

{{-- Quick Link Alamat WH --}}
<div style="margin-top:2.5rem; background:linear-gradient(135deg, #eff6ff, #fdf2f8); border:1px solid #bfdbfe; border-radius:20px; padding:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div style="display:flex; align-items:center; gap:1rem;">
        <div style="width:48px; height:48px; background:var(--white); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; box-shadow:0 4px 12px rgba(0,0,0,0.05);">
            📍
        </div>
        <div>
            <h4 style="font-size:1rem; font-weight:800; color:var(--ink); margin:0;">Alamat Gudang WH China (Nanjing)</h4>
            <p style="font-size:0.85rem; color:var(--ink-soft); margin-top:0.2rem;">Lihat dan salin alamat pengiriman lengkap AIR &amp; SEA Cargo di menu khusus.</p>
        </div>
    </div>
    <a href="{{ route('address.index') }}" style="display:inline-flex; align-items:center; gap:0.4rem; background:var(--ink); color:white; padding:0.65rem 1.25rem; border-radius:12px; font-size:0.85rem; font-weight:700; text-decoration:none; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        Buka Alamat WH &rarr;
    </a>
</div>
@endsection
