@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="page-header" style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; border-bottom:none; margin-bottom:2rem;">
    <div>
        <h1 class="page-title" style="font-size:1.85rem; font-weight:800; background:linear-gradient(90deg, var(--ink), #4f46e5); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">Ringkasan Sistem</h1>
        <p class="page-subtitle" style="margin-top:0.4rem; font-size:0.9rem; color:var(--ink-soft);">Pantau pergerakan logistik dan statistik gudang secara real-time.</p>
    </div>
    <div style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
        <a href="{{ route('admin.resis.create') }}" style="display:inline-flex; align-items:center; gap:0.5rem; background:var(--ink); color:#fff; font-size:0.875rem; font-weight:600; padding:0.6rem 1.25rem; border-radius:12px; text-decoration:none; box-shadow:0 4px 12px rgba(0,0,0,0.1); transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Input Manual
        </a>
        <div style="background:var(--white); padding:0.6rem 1.25rem; border-radius:12px; border:1px solid rgba(255,255,255,0.5); box-shadow:0 4px 20px rgba(0,0,0,0.04); text-align:center; min-width:160px; backdrop-filter:blur(10px);">
            <p style="font-size:0.68rem; font-weight:800; color:#4f46e5; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.1rem;" id="live-day-name">HARI</p>
            <p style="font-weight:800; font-size:0.95rem; color:var(--ink); font-family:'Space Mono',monospace; margin:0;" id="live-date-time">-- --- ---- &bull; --:--:-- WIB</p>
        </div>
    </div>
</div>

<script>
function updateIndoClock() {
    const now = new Date();
    const dayStr = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long' }).format(now).toUpperCase();
    const dateStr = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', day: '2-digit', month: 'short', year: 'numeric' }).format(now);
    const timeStr = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).format(now).replace(/\./g, ':');
    
    const dayElem = document.getElementById('live-day-name');
    const timeElem = document.getElementById('live-date-time');
    if (dayElem) dayElem.textContent = dayStr;
    if (timeElem) timeElem.innerHTML = dateStr + ' &bull; <span style="color:#4f46e5;">' + timeStr + '</span> WIB';
}
setInterval(updateIndoClock, 1000);
document.addEventListener('DOMContentLoaded', updateIndoClock);
updateIndoClock();
</script>

{{-- 4 Stat Cards --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:1.5rem; margin-bottom:2.5rem;">
    {{-- Card 1: Total Resi Aktif --}}
    @php $totalResiAktif = $counts->except('completed')->sum(); @endphp
    <div style="background:var(--white); padding:1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); position:relative; overflow:hidden; transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:radial-gradient(circle, rgba(79,70,229,0.1) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, #e0e7ff 0%, #ede9fe 100%); color:#4f46e5; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(79,70,229,0.15);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <span style="font-size:0.7rem; font-weight:800; color:#4f46e5; background:rgba(79,70,229,0.1); padding:0.3rem 0.75rem; border-radius:20px; text-transform:uppercase; letter-spacing:0.05em;">AKTIF</span>
        </div>
        <p style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Total Resi Berjalan</p>
        <p style="font-size:2.5rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.2; margin-top:0.25rem;">{{ $totalResiAktif }}</p>
    </div>

    {{-- Card 2: Shipment Berjalan --}}
    <div style="background:var(--white); padding:1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); position:relative; overflow:hidden; transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:radial-gradient(circle, rgba(13,148,136,0.1) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, #ccfbf1 0%, #d1fae5 100%); color:#0d9488; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(13,148,136,0.15);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span style="font-size:0.7rem; font-weight:800; color:#0d9488; background:rgba(13,148,136,0.1); padding:0.3rem 0.75rem; border-radius:20px; text-transform:uppercase; letter-spacing:0.05em;">TERCATAT</span>
        </div>
        <p style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Box Shipment OTW</p>
        <p style="font-size:2.5rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.2; margin-top:0.25rem;">{{ $activeShipments }}</p>
    </div>

    {{-- Card 3: Menunggu di China --}}
    @php $chinaWhCount = $counts->get('arrived_wh_china', 0); @endphp
    <div style="background:var(--white); padding:1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); position:relative; overflow:hidden; transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:radial-gradient(circle, rgba(217,119,6,0.1) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, #fef3c7 0%, #ffedd5 100%); color:#d97706; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(217,119,6,0.15);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <span style="font-size:0.7rem; font-weight:800; color:#d97706; background:rgba(217,119,6,0.1); padding:0.3rem 0.75rem; border-radius:20px; text-transform:uppercase; letter-spacing:0.05em;">SAAT INI</span>
        </div>
        <p style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Menunggu di China</p>
        <p style="font-size:2.5rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.2; margin-top:0.25rem;">{{ $chinaWhCount }}</p>
    </div>

    {{-- Card 4: Verifikasi Tagihan --}}
    <div style="background:var(--white); padding:1.5rem; border-radius:20px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); position:relative; overflow:hidden; transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.06)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.02)';">
        <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:radial-gradient(circle, rgba(220,38,38,0.1) 0%, transparent 70%); border-radius:50%;"></div>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem;">
            <div style="width:48px; height:48px; border-radius:14px; background:linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color:#dc2626; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(220,38,38,0.15);">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span style="font-size:0.7rem; font-weight:800; color:#dc2626; background:rgba(220,38,38,0.1); padding:0.3rem 0.75rem; border-radius:20px; text-transform:uppercase; letter-spacing:0.05em;">PROSES</span>
        </div>
        <p style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Verifikasi Pembayaran</p>
        <p style="font-size:2.5rem; font-weight:800; color:var(--ink); font-family:'Space Grotesk',sans-serif; line-height:1.2; margin-top:0.25rem;">{{ $pendingPayments }}</p>
    </div>
</div>

<div style="background:var(--white); border-radius:24px; border:1px solid var(--line); box-shadow:0 4px 20px rgba(0,0,0,0.02); overflow:hidden;">
    <div style="display:flex; align-items:center; justify-content:space-between; padding:1.5rem 1.5rem 1.25rem; border-bottom:1px solid rgba(0,0,0,0.05); background:rgba(248,250,252,0.5);">
        <h3 style="font-size:1.1rem; font-weight:800; display:flex; align-items:center; gap:0.6rem; color:var(--ink);">
            <div style="width:8px; height:18px; background:linear-gradient(180deg, #4f46e5, #818cf8); border-radius:4px;"></div>
            Aktivitas Resi Terkini
        </h3>
        <a href="{{ route('admin.resis.index') }}" style="font-size:0.78rem; font-weight:600; padding:0.4rem 0.8rem; border-radius:8px; background:var(--white); border:1px solid var(--line); color:var(--ink); text-decoration:none; transition:all 0.2s;" onmouseover="this.style.background='var(--surface)';">Lihat Semua</a>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:rgba(248,250,252,0.5); border-bottom:1px solid var(--line);">
                    <th style="padding:0.875rem 1.5rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Resi & Item</th>
                    <th style="padding:0.875rem 1.5rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Customer</th>
                    <th style="padding:0.875rem 1.5rem; text-align:left; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Status</th>
                    <th style="padding:0.875rem 1.5rem; text-align:right; font-size:0.72rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentResis as $resi)
                    <tr style="border-bottom:1px dashed var(--line); transition:background 0.2s;" onmouseover="this.style.background='rgba(248,250,252,0.8)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:1rem 1.5rem;">
                            <div style="font-family:'Space Mono',monospace; font-weight:700; font-size:0.9rem; color:var(--ink);">{{ $resi->resi_number }}</div>
                            <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.25rem;">
                                <span style="display:inline-block; max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; vertical-align:bottom;">{{ $resi->item_name ?: '-' }}</span> &bull; <span style="font-weight:600;">{{ $resi->quantity }} pcs</span>
                            </div>
                        </td>
                        <td style="padding:1rem 1.5rem;">
                            @if($resi->user)
                                <div style="font-weight:700; font-size:0.875rem; color:var(--ink);">{{ $resi->user->name }}</div>
                            @elseif($resi->customer_name_snapshot)
                                <div style="font-weight:700; font-size:0.875rem; color:var(--ink);">{{ $resi->customer_name_snapshot }}</div>
                                <div style="font-size:0.7rem; color:#d97706; background:#fef3c7; display:inline-block; padding:0.1rem 0.4rem; border-radius:4px; margin-top:0.2rem; font-weight:600;">EXCEL DATA</div>
                            @else
                                <div style="font-style:italic; color:var(--ink-soft); font-size:0.875rem;">—</div>
                            @endif
                        </td>
                        <td style="padding:1rem 1.5rem;">
                            <x-status-badge :status="$resi->status" />
                        </td>
                        <td style="padding:1rem 1.5rem; text-align:right;">
                            <div style="font-size:0.875rem; font-weight:600; color:var(--ink);">{{ $resi->updated_at->format('H:i') }}</div>
                            <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.1rem;">{{ $resi->updated_at->format('d M') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:4rem 1rem;">
                            <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="margin:0 auto 1rem; color:var(--ink-soft); opacity:0.5; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <p style="color:var(--ink-soft); font-weight:500;">Belum ada aktivitas resi di sistem.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
