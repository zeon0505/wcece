<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;

class ResiImportPreview implements ToCollection
{
    public function collection(\Illuminate\Support\Collection $collection): void
    {
        // No-op: we use Excel::toCollection() in the controller for preview
    }
}
