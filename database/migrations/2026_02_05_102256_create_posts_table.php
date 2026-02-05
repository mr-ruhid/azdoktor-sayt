<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // Kateqoriya
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // Əsas Məzmun (Tərcümə olunanlar)
            $table->json('title');
            $table->json('content')->nullable();

            // SEO (Tərcümə olunanlar) - YENİ
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            // Standart məlumatlar
            $table->string('slug')->unique();
            $table->string('image')->nullable(); // Qapaq və SEO şəkli
            $table->integer('view_count')->default(0);
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('posts');
    }
};
