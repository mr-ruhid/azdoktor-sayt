<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // Kim qəbula yazılır?
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade'); // Hansı həkimə
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Əgər qeydiyyatlıdırsa

            // Xəstə Məlumatları (Qeydiyyatsız da ola bilər)
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();

            // Zaman
            $table->date('reservation_date');
            $table->time('reservation_time');

            // Status: pending (gözləyir), confirmed (təsdiq), cancelled (ləğv), completed (bitdi)
            $table->string('status')->default('pending');
            $table->text('note')->nullable(); // Şikayət və ya qeyd

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
