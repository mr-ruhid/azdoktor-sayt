<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Əgər səhifə standartdırsa (Home, About, Contact) silinə bilməz
            $table->boolean('is_standard')->default(false);

            // SEO, Banner və digər ayarlar üçün JSON sütunu
            $table->json('meta')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['is_standard', 'meta']);
        });
    }
};
