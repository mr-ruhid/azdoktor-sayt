<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Məhsullar Cədvəli
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Kateqoriya
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            // Tərcümə olunanlar (JSON)
            $table->json('name');
            $table->json('description')->nullable();
            $table->json('short_description')->nullable(); // Qısa izahat

            // Standart məlumatlar
            $table->string('slug')->unique();
            $table->string('sku')->nullable(); // Məhsul kodu

            // Qiymət və Stok
            $table->decimal('price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable(); // Endirimli qiymət
            $table->integer('stock_quantity')->default(0); // Stok sayı
            $table->string('stock_status')->default('instock'); // instock, outofstock

            // Media
            $table->string('image')->nullable(); // Əsas şəkil
            $table->json('gallery')->nullable(); // Digər şəkillər

            // Status
            $table->boolean('status')->default(true);
            $table->boolean('is_featured')->default(false); // Vitrin məhsulu

            $table->timestamps();
        });

        // Məhsul-Teq Əlaqəsi (Çoxun-Çoxa)
        Schema::create('product_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_tag');
        Schema::dropIfExists('products');
    }
};
