<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Promokod (məs: SALE2024)
            $table->string('type')->default('percent'); // percent (faiz) və ya fixed (sabit məbləğ)
            $table->decimal('value', 10, 2); // Endirim dəyəri (məs: 10% və ya 5 AZN)
            $table->decimal('min_spend', 10, 2)->nullable(); // Minimum alış məbləği
            $table->integer('usage_limit')->nullable(); // Ümumi istifadə limiti
            $table->integer('used_count')->default(0); // Neçə dəfə istifadə olunub
            $table->date('start_date')->nullable(); // Başlama tarixi
            $table->date('end_date')->nullable(); // Bitmə tarixi
            $table->boolean('status')->default(true); // Aktiv/Deaktiv
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
