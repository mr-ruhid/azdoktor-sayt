<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sidebar extends Model
{
    protected $fillable = ['type', 'name', 'logo', 'settings', 'status'];

    protected $casts = [
        'settings' => 'array',
        'status' => 'boolean',
    ];
}
