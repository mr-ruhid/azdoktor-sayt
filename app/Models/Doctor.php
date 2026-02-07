<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon; // Tarix hesablamaları üçün

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
        'work_hours', // JSON: ['start' => '09:00', 'end' => '18:00', 'days' => [1,2,3,4,5]]
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

    // Rezervasiyalarla əlaqə
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
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

    /**
     * YENİ: Seçilən tarix üçün boş saatları hesablayan funksiya
     */
    public function getAvailableSlots($dateString)
    {
        $date = Carbon::parse($dateString);
        $slots = [];

        // 1. İş saatlarını təyin et (Default: 09:00 - 18:00)
        // Məlumatlar work_hours array-indən və ya birbaşa sütunlardan gələ bilər
        $startStr = $this->work_hours['start'] ?? $this->work_hour_start ?? '09:00';
        $endStr = $this->work_hours['end'] ?? $this->work_hour_end ?? '18:00';

        $startTime = Carbon::parse($dateString . ' ' . $startStr);
        $endTime = Carbon::parse($dateString . ' ' . $endStr);

        // Interval (məs: 30 dəqiqə)
        $interval = 30;

        // 2. Nahar Fasiləsini Təyin Et (Sizin məntiqə əsasən)
        // Əgər saat 10:00-dan tez başlayırsa -> Nahar 13:00-14:00
        // Əks halda (10:00 və ya daha gec) -> Nahar 13:30-14:30
        if ($startTime->hour < 10) {
            $lunchStart = Carbon::parse($dateString . ' 13:00');
            $lunchEnd = Carbon::parse($dateString . ' 14:00');
        } else {
            $lunchStart = Carbon::parse($dateString . ' 13:30');
            $lunchEnd = Carbon::parse($dateString . ' 14:30');
        }

        // 3. Bazadakı Rezervasiyaları Yoxla
        // Statusu ləğv edilmiş (cancelled) olmayan hər şeyi "dolu" sayırıq
        $bookedTimes = $this->reservations()
            ->whereDate('reservation_date', $dateString)
            ->where('status', '!=', 'cancelled')
            ->pluck('time') // 'H:i' formatında (məs: "09:30")
            ->toArray();

        // 4. Slotları Generasiya Et
        $current = $startTime->copy();

        while ($current->lt($endTime)) {
            $timeString = $current->format('H:i');
            $currentSlotEnd = $current->copy()->addMinutes($interval);

            // Nahar vaxtına düşür? (Kəsişməni yoxlayırıq)
            // Əgər cari slot nahar fasiləsinin içindədirsə və ya kəsişirsə
            $isLunch = ($current->greaterThanOrEqualTo($lunchStart) && $current->lessThan($lunchEnd));

            // Doludurmu?
            // Bazada "09:30:00" kimi ola bilər, ona görə `substr` ilə ilk 5 simvolu yoxlayırıq
            $isBooked = false;
            foreach ($bookedTimes as $bookedTime) {
                if (substr($bookedTime, 0, 5) == $timeString) {
                    $isBooked = true;
                    break;
                }
            }

            // Keçmiş zamandırmı? (Əgər bu gündürsə və saat keçibsə)
            $isPast = Carbon::now()->gt($current);

            // Yalnız nahar olmayan vaxtları siyahıya salırıq
            if (!$isLunch) {
                $slots[] = [
                    'time' => $timeString,
                    'booked' => $isBooked, // Frontend-də boz rəngdə göstərmək üçün
                    'past' => $isPast,     // Keçmiş saatları gizlətmək və ya deaktiv etmək üçün
                    'available' => !$isBooked && !$isPast
                ];
            }

            $current->addMinutes($interval);
        }

        return $slots;
    }
}
