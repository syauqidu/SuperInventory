<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwoFactorCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // cek apakah kode sudah expired
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    // cek apakah kode masih valid (belum dipake dan belum expired)
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }
}
