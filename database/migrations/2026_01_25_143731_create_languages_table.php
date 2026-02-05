<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Dilin adı (Azərbaycan, English)
            $table->string('code')->unique(); // Kodu (az, en, ru)
            $table->string('flag')->nullable(); // Bayraq ikonu (opsional)
            $table->enum('direction', ['ltr', 'rtl'])->default('ltr'); // Yazı istiqaməti (Ərəb dili üçün vacibdir)
            $table->boolean('is_default')->default(false); // Varsayılan dil
            $table->boolean('status')->default(true); // Aktiv/Deaktiv
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('languages');
    }
};
