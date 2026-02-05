<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Support\Str;

class DoctorMigrationSeeder extends Seeder
{
    public function run()
    {
        // 1. Köhnə məlumatları çəkirik
        $oldDoctors = DB::table('portal_doctors')->get();
        $count = 0;

        foreach ($oldDoctors as $old) {

            // --- İXTİSAS MƏNTİQİ ---
            $specialtyId = null;
            if (!empty($old->specialty)) {
                $specNames = $this->maybeDecode($old->specialty);

                if (is_array($specNames)) {
                    $mainName = $specNames['az'] ?? reset($specNames);
                } else {
                    $mainName = $specNames;
                    $specNames = ['az' => $mainName, 'en' => $mainName];
                }

                if ($mainName) {
                    $slug = Str::slug($mainName);

                    $specialty = Specialty::where('slug', $slug)->first();

                    if (!$specialty) {
                        $specialty = Specialty::create([
                            'name' => $specNames,
                            'slug' => $slug,
                            'status' => true
                        ]);
                    }
                    $specialtyId = $specialty->id;
                }
            }

            // --- HƏKİM DATA HAZIRLIĞI ---
            $doctorData = [
                'clinic_id' => $old->clinic_id,
                'specialty_id' => $specialtyId,

                // Adlar və Bio
                'first_name' => $this->maybeDecode($old->first_name),
                'last_name' => $this->maybeDecode($old->last_name),
                'bio' => $this->maybeDecode($old->bio_short),

                // Əlaqə
                'email' => $old->email, // Email-i bura əlavə edirik
                'phone' => $old->phone,
                'image' => $old->image,

                // İş Məlumatları
                'price_range' => $old->price_range,
                'work_days' => $old->work_days,
                'work_hour_start' => $old->work_hour_start,
                'work_hour_end' => $old->work_hour_end,
                'queue_type' => $old->queue_type ?? 1,
                'appointment_type' => $old->appointment_type ?? 1,

                // Sosial Media
                'social_instagram' => $old->social_instagram,
                'social_facebook' => $old->social_facebook,
                'social_youtube' => $old->social_youtube,
                'social_tiktok' => $old->social_tiktok,
                'social_linkedin' => $old->social_linkedin,
                'social_website' => $old->social_website,
                'bio_external_link' => $old->bio_external_link,

                // Digər
                'latitude' => $old->lat,
                'longitude' => $old->lng,
                'status' => $old->status ?? 1,
                'rating_avg' => $old->rating_avg ?? 0,
                'review_count' => $old->review_count ?? 0,
                'created_at' => $old->created_at,
                'updated_at' => $old->updated_at,
            ];

            // --- QEYDİYYAT MƏNTİQİ (Fix) ---
            // Yalnız email-ə güvənmirik, çünki null ola bilər.

            $existingDoctor = null;

            // 1. Email varsa yoxla
            if (!empty($old->email)) {
                $existingDoctor = Doctor::where('email', $old->email)->first();
            }

            // 2. Əgər email ilə tapılmadısa və telefon varsa, telefonla yoxla
            if (!$existingDoctor && !empty($old->phone)) {
                $existingDoctor = Doctor::where('phone', $old->phone)->first();
            }

            if ($existingDoctor) {
                $existingDoctor->update($doctorData);
            } else {
                Doctor::create($doctorData);
                $count++;
            }
        }

        $this->command->info($count . ' yeni həkim uğurla miqrasiya edildi! (Mövcud olanlar yeniləndi)');
    }

    private function maybeDecode($value) {
        if (empty($value)) return null;

        json_decode($value);
        if (json_last_error() === JSON_ERROR_NONE) {
            return json_decode($value, true);
        }

        return ['az' => $value, 'en' => $value];
    }
}
