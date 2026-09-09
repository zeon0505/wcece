<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterShipment extends Model
{
    /** @use HasFactory<\Database\Factories\MasterShipmentFactory> */
    use HasFactory, HasUuids;

    protected $fillable = [
        'code',
        'name',
        'shipment_type',
        'status',
        'notes',
        'photo_box',
        'photo_box_uploaded_at',
        'rate_per_gram',
        'handling_fee',
        'packing_fee',
        'departed_at',
        'arrived_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'rate_per_gram' => 'decimal:2',
            'handling_fee' => 'decimal:2',
            'departed_at' => 'datetime',
            'arrived_at' => 'datetime',
            'photo_box_uploaded_at' => 'datetime',
        ];
    }

    /**
     * Human-readable status labels.
     *
     * @var array<string, string>
     */
    public static array $statusLabels = [
        'in_transit' => 'Dalam Perjalanan',
        'arrived_indonesia' => 'Tiba Indonesia',
        'completed' => 'Selesai',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Generate the next shipment code (e.g. MS-001).
     */
    public static function generateCode(): string
    {
        $count = self::count() + 1;

        return 'MS-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<Resi, $this> */
    public function resis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Resi::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<Invoice, $this> */
    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this> */
    public function creator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
