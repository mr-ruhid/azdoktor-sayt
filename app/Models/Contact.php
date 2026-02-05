<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'is_replied'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_replied' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }
}
