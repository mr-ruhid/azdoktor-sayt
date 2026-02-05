<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            // Sütunların olub-olmadığını yoxlayırıq ki, xəta verməsin
            if (!Schema::hasColumn('general_settings', 'mail_mailer')) {
                $table->string('mail_mailer')->default('smtp')->after('seo_keywords');
                $table->string('mail_host')->nullable()->after('mail_mailer');
                $table->string('mail_port')->nullable()->after('mail_host');
                $table->string('mail_username')->nullable()->after('mail_port');
                $table->string('mail_password')->nullable()->after('mail_username');
                $table->string('mail_encryption')->nullable()->after('mail_password'); // tls, ssl
                $table->string('mail_from_address')->nullable()->after('mail_encryption');
                $table->string('mail_from_name')->nullable()->after('mail_from_address');
            }
        });
    }

    public function down()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn([
                'mail_mailer', 'mail_host', 'mail_port',
                'mail_username', 'mail_password', 'mail_encryption',
                'mail_from_address', 'mail_from_name'
            ]);
        });
    }
};
