<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // Əgər sütunlar yoxdursa əlavə etsin
            if (!Schema::hasColumn('general_settings', 'phone')) {
                $table->string('phone')->nullable()->after('site_name');
            }
            if (!Schema::hasColumn('general_settings', 'email')) {
                $table->string('email')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('general_settings', 'address')) {
                $table->json('address')->nullable()->after('email'); // Tərcümə olunan ünvan
            }
            if (!Schema::hasColumn('general_settings', 'map_iframe')) {
                $table->text('map_iframe')->nullable()->after('address'); // Xəritə kodu
            }
            if (!Schema::hasColumn('general_settings', 'social_links')) {
                $table->json('social_links')->nullable()->after('map_iframe'); // Sosial media linkləri
            }
        });
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['phone', 'email', 'address', 'map_iframe', 'social_links']);
        });
    }
};
