<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResiTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Contoh data — bisa dikosongkan, ini hanya baris contoh
        return [
            ['cc',    'Chagee EXO Batch 2',                '',                 '',       false, 'AIR', false],
            ['ellya', 'Storage Box 4 pcs',                 '7734277403638​06',  '',       true,  'AIR', true],
            ['yuli',  'sticker scene 4 pcs',               'YT8880115792219',  '',       true,  'SEA', true],
            ['njip',  'Ingshot ryul 1 set (6 pcs)',         '773428263794318',  '',       true,  'AIR', true],
        ];
    }

    public function headings(): array
    {
        return [
            'Line User',        // Nama/inisial customer (wajib)
            'Item Name',        // Nama barang
            'Tracking Number',  // Nomor resi dari seller China (wajib)
            'NOTES',            // Catatan tambahan
            'Arrived WH CHN',   // Sudah tiba gudang China? (1/TRUE atau kosong)
            'Shipment',         // Jenis: AIR atau SEA
            'PL',               // Packing List tersedia? (1/TRUE atau kosong)
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 35,
            'C' => 25,
            'D' => 20,
            'E' => 18,
            'F' => 14,
            'G' => 10,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Header row style
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Freeze header row
        $sheet->freezePane('A2');

        return [];
    }
}
