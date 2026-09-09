<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessResiImport;
use App\Models\ImportLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function index(): View
    {
        return view('admin.import.index');
    }

    public function showPreview(): \Illuminate\Http\RedirectResponse|View
    {
        $parsed = session('import_preview');

        if (! $parsed) {
            return redirect()->route('admin.import.index')->with('error', 'Tidak ada data preview. Silakan upload file terlebih dahulu.');
        }

        $perPage  = 25;
        $page     = (int) request()->input('page', 1);
        $slice    = array_slice($parsed, ($page - 1) * $perPage, $perPage);
        $paginated = new LengthAwarePaginator(
            $slice,
            count($parsed),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('admin.import.preview', [
            'parsed'    => $paginated,
            'allParsed' => $parsed,
        ]);
    }

    public function logs(): View
    {
        $logs = ImportLog::with('uploader')->latest()->paginate(20);

        return view('admin.import.logs', compact('logs'));
    }

    public function template(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return Excel::download(new \App\Exports\ResiTemplateExport, 'template-import-resi.xlsx');
    }

    public function preview(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,csv', 'max:5120'],
        ]);

        $collection = Excel::toCollection(new \App\Imports\ResiImportPreview, $request->file('file'));
        $rows = $collection->first() ?? collect();

        // Helper: convert float/scientific-notation to proper string for tracking numbers
        $toTrackingStr = function ($val): string {
            if ($val === null || $val === '') return '';
            // PhpSpreadsheet reads long numbers as float in scientific notation
            // e.g. 7.72069E+14 => needs to be "772069000000000" (14 digits)
            if (is_float($val)) {
                return rtrim(number_format($val, 0, '.', ''), '.');
            }
            return strtoupper(trim((string) $val));
        };

        $cleanNum = fn($val) => (is_numeric($val) ? (float) $val : (float) preg_replace('/[^0-9.]/', '', (string) $val));

        $parsed = [];
        $seenResiNumbers = [];
        $currentShipmentType = 'SEA';
        $currentCustomerName = '';

        // Column index map - set to -1 until detected
        $cols = [
            'name' => -1, 'item' => -1, 'tracking' => -1,
            'weight' => -1, 'rate' => -1, 'cargo_tax' => -1,
            'fee_wh' => -1, 'packing' => -1, 'total' => -1,
            'paid' => -1, 'shipped' => -1,
        ];

        foreach ($rows as $index => $row) {
            $rowArr = $row->toArray();

            // Build a searchable string from only string cells (skip numeric cells)
            $rowStr = strtoupper(implode(' ', array_filter($rowArr, fn($v) => is_string($v))));

            // ── Detect BOX header row ─────────────────────────────────────
            if (str_contains($rowStr, 'BOX') || (str_contains($rowStr, 'SEA') && !str_contains($rowStr, 'TRACKING')) || (str_contains($rowStr, 'AIR') && !str_contains($rowStr, 'TRACKING'))) {
                if (str_contains($rowStr, 'AIR')) $currentShipmentType = 'AIR';
                elseif (str_contains($rowStr, 'SEA')) $currentShipmentType = 'SEA';
                continue;
            }

            // ── Detect column headers ─────────────────────────────────────
            if (str_contains($rowStr, 'TRACKING') || str_contains($rowStr, 'NAME') || str_contains($rowStr, 'WEIGHT')) {
                foreach ($rowArr as $i => $cellVal) {
                    $v = strtolower(trim((string) $cellVal));
                    if (in_array($v, ['name', 'line user', 'customer'])) $cols['name'] = $i;
                    if (in_array($v, ['item', 'item name'])) $cols['item'] = $i;
                    if (str_contains($v, 'tracking') || str_contains($v, 'resi')) $cols['tracking'] = $i;
                    if ($v === 'weight') $cols['weight'] = $i;
                    if ($v === 'rate') $cols['rate'] = $i;
                    if (str_contains($v, 'cargo')) $cols['cargo_tax'] = $i;
                    if (str_contains($v, 'fee wh') || str_contains($v, 'fee_wh')) $cols['fee_wh'] = $i;
                    if ($v === 'packing') $cols['packing'] = $i;
                    if ($v === 'total') $cols['total'] = $i;
                    if ($v === 'paid') $cols['paid'] = $i;
                    if ($v === 'shipped') $cols['shipped'] = $i;
                }
                continue;
            }

            // ── Data row ──────────────────────────────────────────────────
            $nIdx = $cols['name']     !== -1 ? $cols['name']     : 1;
            $iIdx = $cols['item']     !== -1 ? $cols['item']     : 2;
            $tIdx = $cols['tracking'] !== -1 ? $cols['tracking'] : 3;

            $nameCell = trim((string) ($rowArr[$nIdx] ?? ''));
            if (!empty($nameCell)) $currentCustomerName = $nameCell;

            $itemName   = trim((string) ($rowArr[$iIdx] ?? ''));
            $resiRaw    = $rowArr[$tIdx] ?? '';
            $resiNumber = $toTrackingStr($resiRaw);

            // Skip fully empty rows
            if (empty($itemName) && empty($resiNumber)) continue;
            // Skip rows without resi number
            if (empty($resiNumber)) continue;

            $result = [
                'row'           => $index + 1,
                'resi_number'   => $resiNumber,
                'customer_name' => $currentCustomerName,
                'item_name'     => $itemName,
                'quantity'      => 1,
                'weight'        => $cols['weight']    !== -1 ? $cleanNum($rowArr[$cols['weight']]    ?? 0) : 0,
                'rate'          => $cols['rate']      !== -1 ? $cleanNum($rowArr[$cols['rate']]      ?? 0) : 0,
                'cargo_tax'     => $cols['cargo_tax'] !== -1 ? $cleanNum($rowArr[$cols['cargo_tax']] ?? 0) : 0,
                'fee_wh'        => $cols['fee_wh']    !== -1 ? $cleanNum($rowArr[$cols['fee_wh']]    ?? 0) : 0,
                'packing'       => $cols['packing']   !== -1 ? $cleanNum($rowArr[$cols['packing']]   ?? 0) : 0,
                'total'         => $cols['total']     !== -1 ? $cleanNum($rowArr[$cols['total']]     ?? 0) : 0,
                'paid'          => $cols['paid']      !== -1 ? $cleanNum($rowArr[$cols['paid']]      ?? 0) : 0,
                'shipped'       => $cols['shipped']   !== -1 && !empty($rowArr[$cols['shipped']]),
                'notes'         => '',
                'shipment_type' => $currentShipmentType,
                'arrived_wh'    => true,
                'status'        => 'ready',
                'reason'        => null,
            ];

            if (in_array($resiNumber, $seenResiNumbers)) {
                $result['status'] = 'error';
                $result['reason'] = 'Duplikat resi dalam file';
            } else {
                $seenResiNumbers[] = $resiNumber;
            }

            $parsed[] = $result;
        }

        // Store FULL result in session for confirm step
        session(['import_preview' => $parsed]);

        // PRG pattern: redirect to GET to allow page refresh without re-posting
        return redirect()->route('admin.import.preview.show');

    }

    public function confirm(Request $request): RedirectResponse
    {
        $parsed = session('import_preview');

        if (! $parsed) {
            return redirect()->route('admin.import.index')->with('error', 'Data preview tidak ditemukan. Silakan upload ulang.');
        }

        /** @var \App\Models\User $user */
        $user = $request->user();

        $log = ImportLog::create([
            'uploaded_by' => $user->id,
            'file_name' => 'import-' . now()->format('Ymd-His') . '.xlsx',
            'total_rows' => count($parsed),
            'matched_count' => count(array_filter($parsed, fn ($r) => $r['status'] === 'ready')),
            'unmatched_count' => 0,
            'error_count' => count(array_filter($parsed, fn ($r) => $r['status'] === 'error')),
            'raw_result' => $parsed,
        ]);

        dispatch(new ProcessResiImport($parsed, $user->id, $log->id));

        session()->forget('import_preview');

        return redirect()->route('admin.import.logs')->with('success', 'Import sedang diproses di background.');
    }
}
