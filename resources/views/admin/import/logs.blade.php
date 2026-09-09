@extends('layouts.admin')
@section('title', 'Riwayat Import Excel')

@section('content')
<div class="page-header" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 class="page-title">Riwayat Import</h1>
        <p class="page-subtitle">Log seluruh aktivitas import Excel yang pernah dilakukan.</p>
    </div>
    <a href="{{ route('admin.import.index') }}" class="btn btn-primary" style="display:flex;align-items:center;gap:0.5rem;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
        Import Baru
    </a>
</div>

<div class="card" style="border-radius:14px;">
    <div class="table-wrap">
        <table class="manifest">
            <thead>
                <tr>
                    <th>File</th>
                    <th>Di-upload oleh</th>
                    <th>Total Baris</th>
                    <th>Match</th>
                    <th>Unmatched</th>
                    <th>Error</th>
                    <th>Waktu</th>
                    <th style="text-align:right;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr x-data="{ open: false }">
                        <td>
                            <div style="font-family:'Space Mono',monospace; font-size:0.8rem; font-weight:600;">{{ $log->file_name }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ $log->uploader->name ?? '-' }}</td>
                        <td>
                            <span style="font-weight:700;">{{ $log->total_rows }}</span>
                        </td>
                        <td>
                            <span style="font-weight:700; color:#16a34a;">{{ $log->matched_count }}</span>
                        </td>
                        <td>
                            <span style="font-weight:700; color:#d97706;">{{ $log->unmatched_count }}</span>
                        </td>
                        <td>
                            <span style="font-weight:700; color:var(--red);">{{ $log->error_count }}</span>
                        </td>
                        <td style="font-size:0.8rem; color:var(--ink-soft);">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td style="text-align:right;">
                            <button onclick="toggleDetail('detail-{{ $log->id }}')" class="btn btn-secondary" style="font-size:0.75rem; padding:0.3rem 0.75rem;">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    {{-- Detail Row --}}
                    <tr id="detail-{{ $log->id }}" style="display:none;">
                        <td colspan="8" style="padding:0; background:rgba(255,255,255,0.3);">
                            <div style="padding:1rem 1.25rem;">
                                <p style="font-size:0.75rem; font-weight:700; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.75rem;">Detail per Baris</p>
                                <div style="overflow-x:auto;">
                                    <table style="width:100%; border-collapse:collapse; font-size:0.8rem;">
                                        <thead>
                                            <tr style="background:rgba(255,255,255,0.4);">
                                                <th style="padding:0.5rem 0.75rem; text-align:left; font-weight:600; color:var(--ink-soft);">Baris</th>
                                                <th style="padding:0.5rem 0.75rem; text-align:left; font-weight:600; color:var(--ink-soft);">Resi Number</th>
                                                <th style="padding:0.5rem 0.75rem; text-align:left; font-weight:600; color:var(--ink-soft);">Customer</th>
                                                <th style="padding:0.5rem 0.75rem; text-align:left; font-weight:600; color:var(--ink-soft);">Item</th>
                                                <th style="padding:0.5rem 0.75rem; text-align:left; font-weight:600; color:var(--ink-soft);">Status</th>
                                                <th style="padding:0.5rem 0.75rem; text-align:left; font-weight:600; color:var(--ink-soft);">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($log->raw_result as $row)
                                                <tr style="border-top:1px dashed rgba(208,215,224,0.6);">
                                                    <td style="padding:0.5rem 0.75rem; color:var(--ink-soft);">{{ $row['row'] }}</td>
                                                    <td style="padding:0.5rem 0.75rem; font-family:'Space Mono',monospace; font-weight:700;">{{ $row['resi_number'] ?: '-' }}</td>
                                                    <td style="padding:0.5rem 0.75rem;">{{ $row['customer_name'] ?: '-' }}</td>
                                                    <td style="padding:0.5rem 0.75rem;">{{ $row['item_name'] ?? '-' }}</td>
                                                    <td style="padding:0.5rem 0.75rem;">
                                                        @if($row['status'] === 'matched')
                                                            <span style="font-size:0.7rem; font-weight:700; background:#dcfce7; color:#16a34a; padding:0.2rem 0.5rem; border-radius:4px;">MATCH</span>
                                                        @elseif($row['status'] === 'unmatched')
                                                            <span style="font-size:0.7rem; font-weight:700; background:#fef3c7; color:#d97706; padding:0.2rem 0.5rem; border-radius:4px;">UNMATCHED</span>
                                                        @else
                                                            <span style="font-size:0.7rem; font-weight:700; background:#fee2e2; color:#dc2626; padding:0.2rem 0.5rem; border-radius:4px;">ERROR</span>
                                                        @endif
                                                    </td>
                                                    <td style="padding:0.5rem 0.75rem; color:var(--ink-soft);">{{ $row['reason'] ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:3rem 1rem; color:var(--ink-soft);">
                            Belum ada riwayat import.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div style="padding:1rem 1.25rem; border-top:1px dashed rgba(208,215,224,0.6);">
            {{ $logs->links() }}
        </div>
    @endif
</div>

<script>
function toggleDetail(id) {
    const el = document.getElementById(id);
    if (el) el.style.display = el.style.display === 'none' ? 'table-row' : 'none';
}
</script>
@endsection
