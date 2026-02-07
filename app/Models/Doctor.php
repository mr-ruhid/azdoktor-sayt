<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Doctor extends Model
{
    use HasTranslations;

    protected $fillable = [
        'user_id',
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
        'review_count',
        'accepts_reservations'
    ];

    public $translatable = ['first_name', 'last_name', 'bio'];

    protected $casts = [
        'work_hours' => 'array',
        'social_links' => 'array',
        'status' => 'boolean',
        'accepts_reservations' => 'boolean'
    ];

    // İstifadəçi (Hesab) ilə əlaqə
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // Full Name Accessor (Ad + Soyad)
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * View tərəfində $doctor->name çağırılanda işləməsi üçün.
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Spatie Media Library olmadıqda View xətasını önləmək üçün metod.
     * 'image' sütunundakı şəkli qaytarır.
     */
    public function getFirstMediaUrl($collectionName = 'default')
    {
        if (!empty($this->image)) {
            // Əgər tam URL-dirsə (http ilə başlayırsa)
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            // Əgər lokal fayldırsa (public qovluğuna əsasən)
            return asset($this->image);
        }

        // Şəkil yoxdursa standart placeholder
        return 'https://cdn-icons-png.flaticon.com/512/3774/3774299.png';
    }
}
