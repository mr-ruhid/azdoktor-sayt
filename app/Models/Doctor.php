<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Carbon\Carbon;

class Doctor extends Model
{
    use HasTranslations;

    // Bazadakı struktura tam uyğunlaşdırılmış fillable massivi
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

        // İş məlumatları
        'work_days',        // Varchar
        'work_hour_start',  // Varchar
        'work_hour_end',    // Varchar
        'queue_type',       // Tinyint (1: Saatlı, 2: Canlı)
        'appointment_type', // Tinyint (1: Saytdan, 2: Əlaqə)

        // Sosial Media (Ayrı-ayrı sütunlar)
        'social_instagram',
        'social_facebook',
        'social_youtube',
        'social_tiktok',
        'social_linkedin',
        'social_website',
        'bio_external_link',

        // Lokasiya
        'latitude',
        'longitude',

        // Statuslar
        'status',
        'rating_avg',
        'review_count',
        'accepts_reservations'
    ];

    public $translatable = ['first_name', 'last_name', 'bio'];

    protected $casts = [
        'status' => 'boolean',
        'accepts_reservations' => 'boolean',
        // Digər sahələr (social_links, work_hours) artıq array deyil, ayrı sütunlardır
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function specialty() { return $this->belongsTo(Specialty::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }

    // Şərhlər
    public function comments() { return $this->morphMany(Comment::class, 'commentable'); }

    public function getFullNameAttribute() { return $this->first_name . ' ' . $this->last_name; }
    public function getNameAttribute() { return $this->first_name . ' ' . $this->last_name; }

    /**
     * View tərəfində köhnə kodların ($doctor->social_links) işləməsi üçün
     * ayrı sütunları bir massivə yığan Accessor.
     */
    public function getSocialLinksAttribute()
    {
        return array_filter([
            'instagram' => $this->social_instagram,
            'facebook'  => $this->social_facebook,
            'youtube'   => $this->social_youtube,
            'tiktok'    => $this->social_tiktok,
            'linkedin'  => $this->social_linkedin,
            'website'   => $this->social_website,
        ]);
    }

    public function getFirstMediaUrl($collectionName = 'default')
    {
        if (!empty($this->image)) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) return $this->image;
            return asset($this->image);
        }
        return 'https://cdn-icons-png.flaticon.com/512/3774/3774299.png';
    }

    /**
     * Slotları Hesablayan Funksiya (Bazaya uyğunlaşdırıldı)
     */
    public function getAvailableSlots($dateString)
    {
        $date = Carbon::parse($dateString);
        $slots = [];

        // 1. İş saatlarını birbaşa sütunlardan götürürük
        $startStr = $this->work_hour_start ?? '09:00';
        $endStr = $this->work_hour_end ?? '18:00';

        $startTime = Carbon::parse($dateString . ' ' . $startStr);
        $endTime = Carbon::parse($dateString . ' ' . $endStr);
        $interval = 30;

        // 2. Nahar Fasiləsi
        if ($startTime->hour < 10) {
            $lunchStart = Carbon::parse($dateString . ' 13:00');
            $lunchEnd = Carbon::parse($dateString . ' 14:00');
        } else {
            $lunchStart = Carbon::parse($dateString . ' 13:30');
            $lunchEnd = Carbon::parse($dateString . ' 14:30');
        }

        // 3. Bazadakı Rezervasiyaları Yoxla
        $bookedTimes = $this->reservations()
            ->whereDate('reservation_date', $dateString)
            ->where('status', '!=', 'cancelled')
            ->pluck('reservation_time')
            ->toArray();

        // 4. Slotları Generasiya Et
        $current = $startTime->copy();

        while ($current->lt($endTime)) {
            $timeString = $current->format('H:i');
            $isLunch = ($current->greaterThanOrEqualTo($lunchStart) && $current->lessThan($lunchEnd));

            $isBooked = false;
            foreach ($bookedTimes as $bookedTime) {
                // Saat formatı 09:30:00 ola bilər
                if (substr($bookedTime, 0, 5) == $timeString) {
                    $isBooked = true;
                    break;
                }
            }

            $isPast = Carbon::now()->gt($current);

            if (!$isLunch) {
                $slots[] = [
                    'time' => $timeString,
                    'booked' => $isBooked,
                    'past' => $isPast,
                    'available' => !$isBooked && !$isPast
                ];
            }
            $current->addMinutes($interval);
        }

        return $slots;
    }
}
