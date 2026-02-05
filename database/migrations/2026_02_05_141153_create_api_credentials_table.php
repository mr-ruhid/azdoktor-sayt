<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('api_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Məs: Google Maps
            $table->string('slug')->unique(); // google_maps
            $table->string('category'); // payment, map, auth, security
            $table->string('logo')->nullable(); // API loqosu (FontAwesome class)

            // Konfiqurasiya dəyərləri (JSON)
            // Məs: {"client_id": "...", "client_secret": "..."}
            $table->json('credentials')->nullable();

            $table->boolean('status')->default(false); // Aktiv/Deaktiv
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_credentials');
    }
};
