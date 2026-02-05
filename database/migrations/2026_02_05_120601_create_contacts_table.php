<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');      // Ad
            $table->string('surname')->nullable();   // Soyad
            $table->string('email');     // E-poçt
            $table->string('phone')->nullable(); // Nömrə
            $table->string('subject')->nullable(); // Mövzu
            $table->text('message');     // Detallar (Mesaj)

            // Statuslar
            $table->boolean('is_read')->default(false); // Oxu
            $table->boolean('is_replied')->default(false); // Cavab

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
