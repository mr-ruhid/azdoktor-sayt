<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Sifariş Başlığı
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Unikal kod (məs: ORD-123456)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Müştəri Məlumatları (Qeydiyyatsız da ola bilər)
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('note')->nullable(); // Sifariş qeydi

            // Maliyyə
            $table->decimal('subtotal', 10, 2); // Endirimsiz cəm
            $table->decimal('discount', 10, 2)->default(0); // Endirim
            $table->decimal('total', 10, 2); // Yekun

            // Statuslar
            $table->string('status')->default('pending'); // pending (gözləyir), processing (hazırlanır), completed (bitdi), cancelled (ləğv)
            $table->string('payment_status')->default('unpaid'); // unpaid (ödənilməyib), paid (ödənilib)
            $table->string('payment_method')->default('cash_on_delivery'); // cash, card

            // Tip: product (Məhsul) və ya service (Xidmət)
            $table->string('type')->default('product');

            $table->timestamps();
        });

        // Sifariş Detalları (Məhsullar/Xidmətlər)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Polimorfik əlaqə (Product və ya Service ID-si)
            $table->string('orderable_type');
            $table->unsignedBigInteger('orderable_id');
            $table->index(['orderable_type', 'orderable_id']);

            // Məlumatların surəti (Qiymət dəyişsə köhnə sifarişdə dəyişməsin deyə)
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
