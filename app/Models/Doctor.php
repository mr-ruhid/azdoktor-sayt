<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Doctor extends Model
{
    use HasTranslations;

    protected $fillable = [
        'clinic_id',
        'specialty_id',
        'first_name',
        'last_name',
        'bio',
        'email',
        'phone',
        'image',
        'price_range',
        'work_hours',
        'queue_type',
        'social_links',
        'latitude',
        'longitude',
        'status',
        'rating_avg',
        'review_count'
    ];

    public $translatable = ['first_name', 'last_name', 'bio'];

    protected $casts = [
        'work_hours' => 'array',
        'social_links' => 'array',
        'status' => 'boolean'
    ];

    // Əlaqələr
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // Tam adı qaytaran köməkçi (Cari dildə)
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
