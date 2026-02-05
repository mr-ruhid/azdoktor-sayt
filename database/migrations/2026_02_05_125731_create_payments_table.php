<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Hansı sifarişə aiddir?
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Ödəniş detalları
            $table->string('transaction_id')->nullable(); // Bankdan gələn kod
            $table->string('payment_method')->default('card'); // card, cash
            $table->decimal('amount', 10, 2); // Məbləğ
            $table->string('currency')->default('AZN');

            // Status: completed (ödənildi), refunded (geri qaytarıldı), failed (uğursuz)
            $table->string('status')->default('completed');

            $table->timestamp('paid_at')->useCurrent(); // Ödəniş vaxtı
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
