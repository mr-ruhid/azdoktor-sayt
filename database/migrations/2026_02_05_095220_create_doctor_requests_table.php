<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_requests', function (Blueprint $table) {
            $table->id();

            // Şəxsi Məlumatlar
            $table->string('first_name'); // Adı
            $table->string('last_name'); // Soyadı
            $table->integer('age')->nullable(); // Yaşı

            // İş Məlumatları
            $table->string('position')->nullable(); // Vəzifəsi
            $table->string('specialty')->nullable(); // İxtisası (Mətn kimi, çünki bazada olmayan bir şey də ola bilər)
            $table->string('clinic')->nullable(); // Klinikası

            // Əlaqə
            $table->string('email');
            $table->string('phone');
            $table->string('contact_method')->nullable(); // Əlaqə vasitəsi (Zəng, Whatsapp və s.)

            // Fayl və Status
            $table->string('cv_file')->nullable(); // CV fayl yolu
            $table->string('status')->default('new'); // new (yeni), viewed (baxıldı), contacted (əlaqə saxlanıldı), rejected (imtina)

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('doctor_requests');
    }
};
