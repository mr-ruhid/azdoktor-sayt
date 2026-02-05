<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    // Status Rəngləri
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'completed' => 'bg-success',
            'refunded' => 'bg-warning text-dark',
            'failed' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    // Status Mətni
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'completed' => 'Uğurlu',
            'refunded' => 'Geri Qaytarıldı',
            'failed' => 'Xəta',
            default => $this->status
        };
    }

    // Sifarişlə əlaqə
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
