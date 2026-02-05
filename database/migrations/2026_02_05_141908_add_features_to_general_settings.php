<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // Təmir Rejimi
            $table->boolean('maintenance_mode')->default(false);
            $table->json('maintenance_text')->nullable(); // Ekrana çıxan yazı (Çoxdilli)

            // Auth Ayarları
            $table->boolean('enable_registration')->default(true); // Qeydiyyat açıq/bağlı
            $table->boolean('enable_email_verification')->default(false); // E-poçt təsdiqi məcburidirmi?
            $table->boolean('enable_social_login')->default(true); // Sosial giriş master switch

            // 2FA (İki Faktorlu Təsdiqləmə)
            $table->boolean('auth_2fa_admin')->default(false); // Adminlər üçün 2FA
            $table->boolean('auth_2fa_user')->default(false); // İstifadəçilər üçün 2FA
        });
    }

    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'maintenance_mode', 'maintenance_text',
                'enable_registration', 'enable_email_verification', 'enable_social_login',
                'auth_2fa_admin', 'auth_2fa_user'
            ]);
        });
    }
};
