<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // Çoxdilli ad (JSON)
            $table->string('slug')->unique(); // URL üçün
            $table->string('icon')->nullable(); // Şəkil/İkon yolu
            $table->boolean('status')->default(true); // Aktiv/Deaktiv
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('specialties');
    }
};
