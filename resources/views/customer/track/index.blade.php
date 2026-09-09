@extends('layouts.app')

@section('content')
<style>
    .track-search-form { display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .track-search-input { flex: 1; min-width: 220px; border: 2px solid rgba(96,165,250,0.2); border-radius: 12px; padding: 12px 16px; font-size: 0.95rem; outline: none; transition: all 0.2s; background: white; color: #111827; }
    .track-search-btn { background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(59,130,246,0.3); transition: transform 0.2s; white-space: nowrap; }
    
    @media (max-width: 640px) {
        .track-search-input { min-width: 100%; }
        .track-search-btn { width: 100%; justify-content: center; text-align: center; }
    }
</style>

<div style="max-width: 56rem; margin: 0 auto;">
    <div style="background: white; border-radius: 24px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(96,165,250,0.08);">
        <h2 style="font-size: 1.35rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem;">Lacak & Klaim Resi</h2>
        <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.5rem;">Masukkan Nomor Resi atau Username Anda untuk mencari paket yang sudah tiba di gudang kami.</p>

        <form action="{{ route('track.index') }}" method="GET" class="track-search-form">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Contoh: YT123456789 atau Username" required class="track-search-input">
            <button type="submit" class="track-search-btn">Cari Paket</button>
        </form>

        @if($search)
            <div style="border-top: 1px solid #f3f4f6; padding-top: 1.5rem;">
                <h3 style="font-size: 1.05rem; font-weight: 700; color: #374151; margin-bottom: 1rem;">Hasil Pencarian untuk: "{{ $search }}"</h3>

                @if($message)
                    <div style="background: #fef2f2; border: 1px solid #f87171; color: #b91c1c; padding: 1rem; border-radius: 12px; display: flex; align-items: center; gap: 0.75rem;">
                        <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span style="font-weight: 500;">{{ $message }}</span>
                    </div>
                @elseif($resis->isNotEmpty())
                    <p style="font-size:0.85rem; color:var(--ink-soft); margin-bottom:1rem;">Ditemukan <strong>{{ $resis->count() }} paket</strong>. Klik tombol untuk mengklaim paket milik Anda.</p>
                    <div style="display:flex; flex-direction:column; gap:1rem;">
                        @foreach($resis as $resi)
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1.25rem;">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                                    <div>
                                        <h4 style="font-size: 1rem; font-weight: 800; color: #1e293b; font-family: 'Space Mono', monospace; letter-spacing: 0.05em; word-break: break-all;">{{ $resi->resi_number }}</h4>
                                        <p style="color: #64748b; font-size: 0.85rem; margin-top: 0.25rem;">Atas Nama: <strong style="color: #334155;">{{ $resi->customer_name_snapshot ?: 'Tidak ada nama' }}</strong></p>
                                    </div>
                                    <span style="background: #e0f2fe; color: #0284c7; padding: 4px 12px; border-radius: 999px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.05em; white-space:nowrap;">{{ $resi->status_label }}</span>
                                </div>
                                
                                @if($resi->customer_name_snapshot === 'Unclaimed')
                                    @if($resi->item_name)
                                        <div style="display:flex; gap:1rem; margin-bottom:1rem; background:white; padding:0.75rem 1rem; border-radius:10px; border:1px solid #f1f5f9;">
                                            <div>
                                                <span style="display:block; font-size:0.68rem; font-weight:700; color:#94a3b8; text-transform:uppercase;">Nama Barang</span>
                                                <span style="font-weight:700; color:#334155; font-size:0.875rem;">{{ $resi->item_name }}</span>
                                            </div>
                                            <div>
                                                <span style="display:block; font-size:0.68rem; font-weight:700; color:#94a3b8; text-transform:uppercase;">Jumlah</span>
                                                <span style="font-weight:600; color:#334155; font-size:0.875rem;">{{ $resi->quantity }} pcs</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div style="margin-bottom: 1.5rem;">
                                        <span style="display: block; font-size: 0.7rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-bottom:0.5rem;">Foto Barang Unclaimed</span>
                                        @if($resi->photo_item)
                                            <img src="{{ Storage::url($resi->photo_item) }}" alt="Unclaimed Item" style="max-width: 100%; height: auto; border-radius: 12px; border: 1px solid #e2e8f0;">
                                        @else
                                            <div style="background:#f1f5f9; padding:1.5rem; text-align:center; border-radius:12px; color:#64748b; font-size:0.875rem;">
                                                Tidak ada foto
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 0.75rem; margin-bottom: 1.25rem; background: white; padding: 0.85rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                                        <div>
                                            <span style="display: block; font-size: 0.68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Barang</span>
                                            <span style="font-weight: 600; color: #334155; font-size: 0.85rem;">{{ $resi->item_name ?: 'Paket Gudang' }}</span>
                                        </div>
                                        <div>
                                            <span style="display: block; font-size: 0.68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Tipe</span>
                                            <span style="font-weight: 600; color: #334155; font-size: 0.85rem;">{{ $resi->shipment_type ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span style="display: block; font-size: 0.68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Jumlah</span>
                                            <span style="font-weight: 600; color: #334155; font-size: 0.85rem;">{{ $resi->quantity }} pcs</span>
                                        </div>
                                        <div>
                                            <span style="display: block; font-size: 0.68rem; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Berat Final</span>
                                            <span style="font-weight: 600; color: #334155; font-size: 0.85rem;">{{ $resi->final_weight_gram ? number_format($resi->final_weight_gram, 0, ',', '.') . ' gram' : '-' }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if(!$resi->user_id)
                                    @php
                                        $hasPendingClaim = \App\Models\ResiClaim::where('resi_id', $resi->id)
                                            ->where('user_id', auth()->id())
                                            ->where('status', 'pending')
                                            ->exists();
                                    @endphp

                                    @if($hasPendingClaim)
                                        <div style="text-align:center; padding:0.75rem; background:#fffbeb; border-radius:10px; color:#d97706; font-weight:700; font-size:0.875rem; border:1px solid #fde68a;">
                                            ⏳ Menunggu Persetujuan Admin
                                        </div>
                                    @elseif($resi->customer_name_snapshot === 'Unclaimed')
                                        <a href="{{ route('track.claim.form', $resi) }}" style="display:block; text-align:center; text-decoration:none; width: 100%; background: #f59e0b; color: white; border: none; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); transition: all 0.2s;">
                                            Klaim Barang Ini
                                        </a>
                                    @else
                                        <form action="{{ route('track.claim', $resi) }}" method="POST">
                                            @csrf
                                            <button type="submit" style="width: 100%; background: #10b981; color: white; border: none; border-radius: 12px; padding: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); transition: all 0.2s;">
                                                ✓ Ini Paket Saya, Tambahkan ke Dashboard
                                            </button>
                                        </form>
                                    @endif
                                @elseif($resi->user_id === auth()->id())
                                    <div style="text-align:center; padding:0.75rem; background:#f0fdf4; border-radius:10px; color:#16a34a; font-weight:700; font-size:0.875rem;">
                                        ✓ Sudah ada di Dashboard Anda
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
