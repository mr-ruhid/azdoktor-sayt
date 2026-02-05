<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Kim yazıb? (Qeydiyyatsız da ola bilər)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable();

            // Nəyə yazılıb? (Polimorfik: Doctor, Product, Post)
            $table->morphs('commentable'); // commentable_id, commentable_type yaradır

            // Məzmun
            $table->text('content');
            $table->integer('rating')->nullable(); // 1-5 ulduz (Bloq üçün boş ola bilər)

            // Status və Cavab
            $table->boolean('is_approved')->default(false); // Təsdiq
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // Cavab üçün

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
