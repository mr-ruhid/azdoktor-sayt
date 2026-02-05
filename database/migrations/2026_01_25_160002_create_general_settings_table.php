<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->nullable(); // Saytın adı
            $table->string('logo')->nullable(); // Əsas Logo
            $table->string('logo_dark')->nullable(); // Gecə rejimi üçün Logo
            $table->string('favicon')->nullable(); // Favicon

            // Tərcümə olunan SEO sütunları (JSON formatında)
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('general_settings');
    }
};
