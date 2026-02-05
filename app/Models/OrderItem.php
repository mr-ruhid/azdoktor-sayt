<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'orderable_type',
        'orderable_id',
        'name',
        'price',
        'quantity',
        'total'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Polimorfik əlaqə (Məhsul və ya Xidmətə bağlanır)
    public function orderable()
    {
        return $this->morphTo();
    }
}
