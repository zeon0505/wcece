<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasUuids;
    protected $fillable = [
        'resi_id',
        'from_status',
        'to_status',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function getToStatusLabelAttribute(): string
    {
        return Resi::$statusLabels[$this->to_status] ?? $this->to_status;
    }

    public function getFromStatusLabelAttribute(): string
    {
        return Resi::$statusLabels[$this->from_status ?? ''] ?? ($this->from_status ?? '-');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Resi, $this> */
    public function resi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Resi::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this> */
    public function changedByUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
