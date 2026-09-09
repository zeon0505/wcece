@extends('layouts.app')
@section('title', 'Tagihan')

@section('content')
<div class="page-header" style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="page-title">Tagihan Anda</h1>
        <p class="page-subtitle">Daftar semua tagihan pembayaran kargo Anda.</p>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="manifest">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Jatuh Tempo</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                    <tr>
                        <td>
                            <div style="font-family:'Space Mono',monospace; font-weight:700;">{{ $inv->invoice_number }}</div>
                            <div style="font-size:0.75rem; color:var(--ink-soft); margin-top:0.25rem;">Shipment: {{ $inv->masterShipment->code }}</div>
                        </td>
                        <td style="font-weight:600;">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @if($inv->status === 'unpaid')
                                <span class="badge badge-unpaid">Belum Dibayar</span>
                            @elseif($inv->status === 'pending_verification')
                                <span class="badge badge-pending-ver">Verifikasi</span>
                            @else
                                <span class="badge badge-paid">Lunas</span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size:0.875rem;">{{ $inv->due_date->format('d M Y') }}</div>
                            @if($inv->status === 'unpaid' && $inv->due_date->isPast())
                                <div style="font-size:0.7rem; color:var(--red); font-weight:600;">Terlewat {{ $inv->due_date->diffInDays() }} hari</div>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <a href="{{ route('invoices.show', $inv) }}" class="btn btn-secondary" style="font-size:0.75rem; padding:0.3rem 0.75rem;">Lihat Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:3rem 1rem;">
                            <p style="color:var(--ink-soft);">Tidak ada tagihan saat ini.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($invoices->hasPages())
    <div class="pagination" style="margin-top:1.5rem;">
        {!! $invoices->links('vendor.pagination.simple') !!}
    </div>
@endif
@endsection
