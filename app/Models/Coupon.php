<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
    ];

    // Kuponun aktiv olub-olmadığını yoxlayan metod
    public function isValid()
    {
        if (!$this->status) return false;

        $now = now();

        // Tarix yoxlanışı
        if ($this->start_date && $this->start_date > $now) return false;
        if ($this->end_date && $this->end_date < $now) return false;

        // Limit yoxlanışı
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;

        return true;
    }
}
