<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'note',
        'subtotal',
        'discount',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'type'
    ];

    // Statuslar üçün Badge (Rəng)
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning text-dark',
            'processing' => 'bg-info text-white',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    // Status Mətni
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Gözləyir',
            'processing' => 'Hazırlanır',
            'completed' => 'Tamamlandı',
            'cancelled' => 'Ləğv edildi',
            default => $this->status
        };
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
