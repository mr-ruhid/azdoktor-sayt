<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('file_name'); // Orijinal ad
            $table->string('file_path'); // Yaddaşdakı yol
            $table->string('file_type'); // jpg, png, pdf
            $table->integer('file_size'); // KB
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
};
