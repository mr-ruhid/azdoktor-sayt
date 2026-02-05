<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();

            // Əlaqələr
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->onDelete('set null');
            $table->foreignId('specialty_id')->nullable()->constrained('specialties')->onDelete('set null'); // Yeganə struktur dəyişikliyi

            // Tərcümə olunan şəxsi məlumatlar (JSON)
            $table->json('first_name')->nullable();
            $table->json('last_name')->nullable();
            $table->json('bio')->nullable(); // bio_short əvəzinə

            // Əlaqə
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();

            // İş məlumatları (Köhnə struktura uyğun)
            $table->string('price_range')->nullable();
            $table->string('work_days')->nullable();
            $table->string('work_hour_start')->nullable();
            $table->string('work_hour_end')->nullable();

            // Tiplər
            $table->tinyInteger('queue_type')->default(1)->comment('1:Saatli, 2:Canli');
            $table->tinyInteger('appointment_type')->default(1)->comment('1:Saytdan, 2:Elaqe');

            // Sosial Media (Ayrı-ayrı sütunlar)
            $table->string('social_instagram')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_tiktok')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_website')->nullable();
            $table->string('bio_external_link')->nullable();

            // Xəritə (Köhnə datadan gələn adlarla)
            $table->string('latitude')->nullable(); // Köhnə lat
            $table->string('longitude')->nullable(); // Köhnə lng

            // Status və Rezervasiya
            $table->boolean('status')->default(true);
            $table->boolean('accepts_reservations')->default(true); // YENİ: Rezervasiya qəbul edir?

            // Reytinq
            $table->decimal('rating_avg', 3, 2)->default(0);
            $table->integer('review_count')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctors');
    }
};
