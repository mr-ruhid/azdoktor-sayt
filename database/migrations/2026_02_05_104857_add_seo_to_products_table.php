<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // SEO sahələri (Çoxdilli JSON olacaq)
            $table->json('seo_title')->nullable()->after('short_description');
            $table->json('seo_description')->nullable()->after('seo_title');
            $table->json('seo_keywords')->nullable()->after('seo_description');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords']);
        });
    }
};
