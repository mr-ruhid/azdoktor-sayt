<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sidebars', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique(); // 'pc_sidebar' və 'mobile_navbar'
            $table->string('name'); // Admin paneldə görünən ad
            $table->string('logo')->nullable(); // Logo

            // Konfiqurasiya (JSON)
            // Məs: {"show_search": true, "background_color": "#ffffff", "text_color": "#000000"}
            $table->json('settings')->nullable();

            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sidebars');
    }
};
