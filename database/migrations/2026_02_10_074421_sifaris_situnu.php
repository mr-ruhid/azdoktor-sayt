<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sifarişlər Cədvəli
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Qeydiyyatlıdırsa ID, yoxsa NULL

            // Müştəri Məlumatları
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->text('note')->nullable();

            // Maliyyə
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method')->default('cash'); // cash, card
            $table->string('payment_status')->default('pending'); // pending, paid, failed

            // Sifariş Statusu (pending, processing, completed, cancelled)
            $table->string('status')->default('pending');

            $table->timestamps();
        });

        // 2. Sifariş Detalları (Məhsullar/Xidmətlər)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Polimorfik Əlaqə (Məhsul və ya Xidmət ola bilər)
            $table->unsignedBigInteger('itemable_id');
            $table->string('itemable_type');

            $table->string('name'); // Məhsulun adı (silinsə belə qalsın)
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Vahid qiyməti
            $table->decimal('total', 10, 2); // Sətir cəmi (price * quantity)

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
