<?php

namespace App\Models;

use App\Jobs\SendStatusEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resi extends Model
{
    /** @use HasFactory<\Database\Factories\ResiFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'master_shipment_id',
        'resi_number',
        'item_name',
        'customer_name_snapshot',
        'quantity',
        'status',
        'shipment_type',
        'photo_item',
        'proof_co',
        'photo_wh_china',
        'photo_arrived_id',
        'photo_received',
        'received_at',
        'final_weight_gram',
        'weight',
        'rate',
        'cargo_tax',
        'fee_wh',
        'packing',
        'total',
        'paid',
        'shipped',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'final_weight_gram' => 'decimal:2',
            'weight' => 'decimal:2',
            'rate' => 'decimal:2',
            'cargo_tax' => 'decimal:2',
            'fee_wh' => 'decimal:2',
            'packing' => 'decimal:2',
            'total' => 'decimal:2',
            'paid' => 'decimal:2',
            'shipped' => 'boolean',
            'quantity' => 'integer',
            'received_at' => 'datetime',
        ];
    }

    /**
     * Valid status transitions.
     *
     * @var array<string, list<string>>
     */
    public static array $statusTransitions = [
        'waiting_arrival' => ['arrived_wh_china'],
        'arrived_wh_china' => ['in_transit'],
        'in_transit' => ['arrived_indonesia'],
        'arrived_indonesia' => ['awaiting_payment'],
        'awaiting_payment' => ['ready_to_ship'],
        'ready_to_ship' => ['completed'],
        'completed' => [],
    ];

    /**
     * Human-readable status labels.
     *
     * @var array<string, string>
     */
    public static array $statusLabels = [
        'waiting_arrival' => 'Menunggu Tiba',
        'arrived_wh_china' => 'Tiba Gudang China',
        'in_transit' => 'Dalam Pengiriman',
        'arrived_indonesia' => 'Tiba Indonesia',
        'awaiting_payment' => 'Menunggu Pembayaran',
        'ready_to_ship' => 'Siap Kirim',
        'completed' => 'Barang Diterima',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Transition this resi to a new status, recording history and dispatching email.
     */
    public function transitionTo(string $newStatus, ?string $changedById = null): void
    {
        $oldStatus = $this->status;

        $this->update(['status' => $newStatus]);

        $this->statusHistories()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'changed_by' => $changedById,
        ]);

        dispatch(new SendStatusEmailNotification($this->id));
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<User, $this> */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<MasterShipment, $this> */
    public function masterShipment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MasterShipment::class);
    }

    public function claims()
    {
        return $this->hasMany(ResiClaim::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<StatusHistory, $this> */
    public function statusHistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at');
    }
}

