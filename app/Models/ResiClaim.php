<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResiClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'resi_id',
        'user_id',
        'pickup_code',
        'line_name',
        'item_name',
        'quantity',
        'shipment_type',
        'proof_photo',
        'status',
    ];

    public function resi()
    {
        return $this->belongsTo(Resi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
