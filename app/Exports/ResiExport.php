<?php

namespace App\Exports;

use App\Models\Resi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ResiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Resi::with(['user', 'masterShipment'])->get();
    }

    public function map($resi): array
    {
        return [
            $resi->resi_number,
            $resi->user ? $resi->user->name : ($resi->customer_name_snapshot ?: 'Unmatched'),
            $resi->item_name,
            $resi->quantity,
            $resi->final_weight_kg ? $resi->final_weight_kg . ' kg' : '-',
            \App\Models\Resi::$statusLabels[$resi->status] ?? $resi->status,
            $resi->masterShipment ? $resi->masterShipment->code : '-',
            $resi->created_at->format('Y-m-d H:i:s'),
            $resi->notes,
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Resi',
            'Customer',
            'Nama Barang',
            'Qty',
            'Berat',
            'Status',
            'Master Shipment',
            'Tanggal Dibuat',
            'Catatan',
        ];
    }
}
