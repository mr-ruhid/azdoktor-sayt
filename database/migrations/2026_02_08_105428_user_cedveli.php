<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users cədvəlinə əlavələr (Pasiyentlər üçün)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'surname')) {
                $table->string('surname')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('phone');
            }
            // İstifadəçi tipi: 0=User, 1=Admin, 2=Doctor
            if (!Schema::hasColumn('users', 'role_type')) {
                $table->tinyInteger('role_type')->default(0)->after('password');
            }
        });

        // 2. Həkim İstəkləri Cədvəli
        if (!Schema::hasTable('doctor_requests')) {
            Schema::create('doctor_requests', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('phone');
                $table->string('title')->nullable(); // Dr., Uzm. Dr. və s.

                // Əlaqələr
                $table->unsignedBigInteger('specialty_id');
                $table->unsignedBigInteger('clinic_id')->nullable();

                $table->string('cv_file'); // CV faylının yolu
                $table->text('notes')->nullable(); // Əlavə qeydlər

                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['surname', 'phone', 'birth_date', 'role_type']);
        });
        Schema::dropIfExists('doctor_requests');
    }
};
