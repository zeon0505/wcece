<?php

namespace App\Jobs;

use App\Mail\ResiStatusUpdatedMail;
use App\Models\ImportLog;
use App\Models\Resi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ProcessResiImport implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function __construct(
        public readonly array $rows,
        public readonly int $adminId,
        public readonly int $importLogId,
    ) {}

    public function handle(): void
    {
        $matchedCount = 0;

        foreach ($this->rows as $row) {
            if ($row['status'] !== 'ready') {
                continue;
            }

            $status = $row['arrived_wh'] ? 'arrived_wh_china' : 'waiting_arrival';

            $resi = Resi::updateOrCreate(
                ['resi_number' => $row['resi_number']],
                [
                    'item_name' => $row['item_name'],
                    'customer_name_snapshot' => $row['customer_name'],
                    'quantity' => $row['quantity'] ?: 1,
                    'status' => $status,
                    'notes' => $row['notes'],
                    'weight' => $row['weight'] ?? 0,
                    'rate' => $row['rate'] ?? 0,
                    'cargo_tax' => $row['cargo_tax'] ?? 0,
                    'fee_wh' => $row['fee_wh'] ?? 0,
                    'packing' => $row['packing'] ?? 0,
                    'total' => $row['total'] ?? 0,
                    'paid' => $row['paid'] ?? 0,
                    'shipped' => $row['shipped'] ?? false,
                ]
            );

            if ($row['arrived_wh']) {
                $resi->statusHistories()->firstOrCreate([
                    'to_status' => 'arrived_wh_china',
                ], [
                    'changed_by' => $this->adminId,
                ]);
            }

            $matchedCount++;
        }

        ImportLog::where('id', $this->importLogId)->update([
            'matched_count' => $matchedCount,
        ]);
    }
}
