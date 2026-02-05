<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiCredential extends Model
{
    protected $fillable = ['name', 'slug', 'category', 'logo', 'credentials', 'status'];

    protected $casts = [
        'credentials' => 'array', // JSON-u avtomatik array-ə çevirir
        'status' => 'boolean',
    ];
}
