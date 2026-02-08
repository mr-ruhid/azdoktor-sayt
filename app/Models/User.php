<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'surname',      // Pasiyent üçün
        'email',
        'phone',        // Pasiyent/Həkim üçün
        'birth_date',   // Pasiyent üçün
        'role_type',    // 0: User, 1: Admin, 2: Doctor
        'password',

        // 2FA Sütunları
        'two_factor_code',
        'two_factor_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'two_factor_expires_at' => 'datetime',
        ];
    }

    // --- Helper Metodlar ---

    // Tam ad (Ad + Soyad)
    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    // --- 2FA Metodları ---

    public function generateTwoFactorCode()
    {
        $this->timestamps = false; // updated_at dəyişməsin
        $this->two_factor_code = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps = false;
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    // --- Əlaqələr ---

    // Əgər istifadəçi həkimdirsə, həkim profili ilə əlaqə
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // Pasiyentin rezervasiyaları
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
