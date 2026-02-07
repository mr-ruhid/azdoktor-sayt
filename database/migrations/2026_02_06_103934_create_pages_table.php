<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // Məzmun (Tərcümə olunan)
            $table->json('title');
            $table->json('content')->nullable();

            // SEO (Tərcümə olunan)
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            $table->string('slug')->unique();
            $table->boolean('status')->default(true);
            $table->string('image')->nullable(); // Qapaq şəkli

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
