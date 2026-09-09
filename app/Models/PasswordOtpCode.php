<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordOtpCode extends Model
{
    protected $fillable = ['email', 'otp_code', 'expires_at', 'used'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used' => 'boolean',
        ];
    }

    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }
}
