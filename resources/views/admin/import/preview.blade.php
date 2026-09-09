@extends('layouts.admin')
@section('title', 'Preview Import Excel')

@section('content')
@php
    $readyRows   = collect($allParsed)->where('status', 'ready');
    $errorRows   = collect($allParsed)->where('status', 'error');
    $readyCount  = $readyRows->count();
    $errorCount  = $errorRows->count();
    $matched     = $readyCount; // alias for button condition
@endphp

<div class="page-header" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
    <div>
        <h1 class="page-title">Preview Import</h1>
        <p class="page-subtitle">Cek hasil parsing sebelum dikonfirmasi. Semua baris hijau akan dimasukkan ke database.</p>
    </div>
</div>

<div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem;">
    <div class="card" style="padding:1rem 1.5rem; border-radius:14px; display:flex; align-items:center; gap:1rem;">
        <span style="font-size:1.75rem; font-weight:700; color:#16a34a;">{{ $readyCount }}</span>
        <div>
            <p style="font-size:0.75rem; font-weight:600; color:var(--ink-soft);">SIAP DIMASUKKAN</p>
            <p style="font-size:0.8rem; color:#16a34a; font-weight:600;">Ready ✓</p>
        </div>
    </div>
    <div class="card" style="padding:1rem 1.5rem; border-radius:14px; display:flex; align-items:center; gap:1rem;">
        <span style="font-size:1.75rem; font-weight:700; color:var(--red);">{{ $errorCount }}</span>
        <div>
            <p style="font-size:0.75rem; font-weight:600; color:var(--ink-soft);">DILEWATI</p>
            <p style="font-size:0.8rem; color:var(--red); font-weight:600;">Error ✗</p>
        </div>
    </div>
</div>

<div class="card" style="border-radius:14px; margin-bottom:1.5rem; overflow:auto;">
    <table style="width:100%; border-collapse:collapse; min-width:1200px;">
        <thead>
            <tr style="background:var(--surface); font-size:0.72rem; color:var(--ink-soft); text-transform:uppercase; letter-spacing:0.05em; border-bottom:2px solid var(--line);">
                <th style="padding:0.75rem 1rem; text-align:left; white-space:nowrap;">Name</th>
                <th style="padding:0.75rem 1rem; text-align:left;">Item</th>
                <th style="padding:0.75rem 1rem; text-align:left; white-space:nowrap;">Tracking Number</th>
                <th style="padding:0.75rem 1rem; text-align:right;">Weight</th>
                <th style="padding:0.75rem 1rem; text-align:right;">Rate</th>
                <th style="padding:0.75rem 1rem; text-align:right; white-space:nowrap;">Cargo Tax</th>
                <th style="padding:0.75rem 1rem; text-align:right; white-space:nowrap;">Fee WH</th>
                <th style="padding:0.75rem 1rem; text-align:right;">Packing</th>
                <th style="padding:0.75rem 1rem; text-align:right;">Total</th>
                <th style="padding:0.75rem 1rem; text-align:right;">Paid</th>
                <th style="padding:0.75rem 1rem; text-align:center;">Shipped</th>
                <th style="padding:0.75rem 1rem; text-align:center;">Box</th>
                <th style="padding:0.75rem 1rem; text-align:center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parsed as $row)
                @if($row['status'] === 'ready')
                    <tr style="border-bottom:1px dashed var(--line); background:rgba(220,252,231,0.4);">
                @else
                    <tr style="border-bottom:1px dashed var(--line); background:rgba(254,226,226,0.4);">
                @endif
                    <td style="padding:0.75rem 1rem; font-weight:700; white-space:nowrap;">{{ $row['customer_name'] ?: '—' }}</td>
                    <td style="padding:0.75rem 1rem;">{{ $row['item_name'] ?? '—' }}</td>
                    <td style="padding:0.75rem 1rem; font-family:'Space Mono',monospace; font-weight:700; font-size:0.82rem; white-space:nowrap;">{{ $row['resi_number'] ?: '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:right;">{{ $row['weight'] ?: '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:right;">{{ $row['rate'] ?: '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:right;">{{ $row['cargo_tax'] ? number_format((float)$row['cargo_tax'], 2) : '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:right;">{{ $row['fee_wh'] ? number_format((float)$row['fee_wh'], 2) : '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:right;">{{ $row['packing'] ? number_format((float)$row['packing'], 2) : '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:right; font-weight:600;">{{ $row['total'] ? number_format((float)$row['total'], 2) : '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:right;">{{ $row['paid'] ? number_format((float)$row['paid'], 2) : '—' }}</td>
                    <td style="padding:0.75rem 1rem; text-align:center;">
                        @if(!empty($row['shipped']))
                            <span style="color:#16a34a; font-weight:bold; font-size:1rem;">✓</span>
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td style="padding:0.75rem 1rem; text-align:center;">
                        @if(($row['shipment_type'] ?? '') === 'AIR')
                            <span style="font-size:0.72rem; font-weight:700; background:#e0e7ff; color:#4f46e5; padding:0.2rem 0.6rem; border-radius:20px;">AIR</span>
                        @else
                            <span style="font-size:0.72rem; font-weight:700; background:#ccfbf1; color:#0d9488; padding:0.2rem 0.6rem; border-radius:20px;">SEA</span>
                        @endif
                    </td>
                    <td style="padding:0.75rem 1rem; text-align:center;">
                        @if($row['status'] === 'ready')
                            <span style="font-size:0.72rem; font-weight:700; background:#dcfce7; color:#16a34a; padding:0.2rem 0.6rem; border-radius:6px;">READY</span>
                        @else
                            <span style="font-size:0.72rem; font-weight:700; background:#fee2e2; color:#dc2626; padding:0.2rem 0.6rem; border-radius:6px;">ERROR</span>
                            @if(!empty($row['reason']))
                                <br><small style="color:#dc2626; font-size:0.65rem;">{{ $row['reason'] }}</small>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div style="margin-bottom:1.5rem;">
    {{ $parsed->links() }}
</div>

<div style="display:flex; gap:1rem; justify-content:flex-end;">
    <a href="{{ route('admin.import.index') }}" class="btn btn-secondary">
        ← Upload Ulang
    </a>
    @if($readyCount > 0)
        <form method="POST" action="{{ route('admin.import.confirm') }}">
            @csrf
            <button type="submit" class="btn btn-primary" onclick="return confirm('Masukkan semua {{ $readyCount }} resi ke database?')">
                ✓ Import Semua ({{ $readyCount }} resi)
            </button>
        </form>
    @endif
</div>
@endsection
