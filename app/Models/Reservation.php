<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'doctor_id',
        'user_id',
        'name',
        'phone',
        'email',
        'reservation_date',
        'reservation_time',
        'status',
        'note'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        // 'reservation_time' => 'datetime:H:i', // Formatlamaq istəsəniz
    ];

    // Statusun rəngi
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning text-dark', // Gözləyir (Sarı)
            'confirmed' => 'bg-primary',         // Təsdiqlənib (Mavi)
            'completed' => 'bg-success',         // Bitdi (Yaşıl)
            'cancelled' => 'bg-danger',          // Ləğv (Qırmızı)
            default => 'bg-secondary'
        };
    }

    // Statusun mətni (Tərcümə üçün sadə variant)
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Gözləyir',
            'confirmed' => 'Təsdiqləndi',
            'completed' => 'Tamamlandı',
            'cancelled' => 'Ləğv edildi',
            default => $this->status
        };
    }

    // Həkimlə əlaqə
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // İstifadəçi ilə əlaqə
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
