<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorRequest extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'position',
        'specialty',
        'clinic',
        'email',
        'phone',
        'contact_method',
        'cv_file',
        'status'
    ];

    // Tam ad
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Statusun rəngi (Badge üçün)
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'new' => 'bg-primary',      // Mavi (Yeni)
            'viewed' => 'bg-info',      // Açıq mavi (Baxıldı)
            'contacted' => 'bg-success',// Yaşıl (Əlaqə saxlanıldı)
            'rejected' => 'bg-danger',  // Qırmızı (İmtina)
            default => 'bg-secondary'
        };
    }

    // Statusun mətni
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'new' => 'Yeni',
            'viewed' => 'Baxıldı',
            'contacted' => 'Əlaqə Saxlanıldı',
            'rejected' => 'İmtina',
            default => 'Naməlum'
        };
    }
}
