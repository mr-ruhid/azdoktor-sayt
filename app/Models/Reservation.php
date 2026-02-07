<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'user_id',
        'name',
        'phone',
        'email',
        'reservation_date',
        'time',   // DİQQƏT: 'reservation_time' əvəzinə 'time' istifadə edirik (Doctor modeli ilə uyğunluq üçün)
        'status', // pending, confirmed, completed, cancelled
        'note'
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    // Statusun rəngi (Admin Panel üçün)
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

    // Statusun mətni (Admin Panel üçün)
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
