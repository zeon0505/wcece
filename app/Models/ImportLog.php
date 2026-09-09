<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
        protected $fillable = [
        'uploaded_by',
        'file_name',
        'total_rows',
        'matched_count',
        'unmatched_count',
        'error_count',
        'raw_result',
    ];

    protected function casts(): array
    {
        return [
            'raw_result' => 'array',
            'total_rows' => 'integer',
            'matched_count' => 'integer',
            'unmatched_count' => 'integer',
            'error_count' => 'integer',
        ];
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this> */
    public function uploader(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

