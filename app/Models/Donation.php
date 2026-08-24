<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'transaction_id',
        'status',
        'qr_code_payload',
        'qr_code_image',
        'donor_name',
        'donor_email',
        'message',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Donation $donation) {
            if (empty($donation->transaction_id)) {
                $donation->transaction_id = 'PIX-' . Str::upper(Str::random(16));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getAmountFormattedAttribute(): string
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }
}