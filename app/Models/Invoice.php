<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'user_id',
        'master_shipment_id',
        'total_weight_gram',
        'rate_per_gram',
        'handling_fee',
        'packing_fee',
        'total_amount',
        'status',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'total_weight_gram' => 'decimal:2',
            'rate_per_gram' => 'decimal:2',
            'handling_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    /**
     * Human-readable status labels.
     *
     * @var array<string, string>
     */
    public static array $statusLabels = [
        'unpaid' => 'Belum Bayar',
        'pending_verification' => 'Menunggu Verifikasi',
        'paid' => 'Lunas',
        'failed' => 'Gagal',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_amount, 0, ',', '.');
    }

    /**
     * Generate invoice number like INV-MS001-002.
     */
    public static function generateNumber(MasterShipment $shipment): string
    {
        $count = self::where('master_shipment_id', $shipment->id)->count() + 1;

        return 'INV-' . $shipment->code . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
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

    /** @return \Illuminate\Database\Eloquent\Relations\HasOne<Payment, $this> */
    public function payment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Payment::class);
    }
}

