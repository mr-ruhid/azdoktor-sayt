<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();

            // Tərcümə olunanlar (JSON)
            $table->json('name'); // Xidmətin adı
            $table->json('description')->nullable(); // Əsas məlumat (Detallı)
            $table->json('short_description')->nullable(); // Qısa məlumat (Blok üçün)
            $table->json('features')->nullable(); // "Xidmətə daxil olanlar"

            // SEO (Tərcümə olunanlar)
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            // Standart məlumatlar
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->nullable(); // Qiymət
            $table->string('image')->nullable(); // Qapaq şəkli
            $table->string('icon')->nullable(); // İkon (opsional)
            $table->boolean('status')->default(true); // Aktiv/Deaktiv

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
