<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            // Menu tipi: 'pc_sidebar', 'mobile_navbar', 'footer_col_1' və s.
            $table->string('type')->index();

            // Kimlər görə bilər? ('all', 'guest', 'auth_user', 'doctor')
            $table->string('role')->default('all');

            // Başlıq (Çoxdilli - JSON formatında)
            $table->json('title');

            // Link (URL və ya Route adı)
            $table->string('url')->nullable();

            // İkon (FontAwesome klassı, məs: 'fas fa-home')
            $table->string('icon')->nullable();

            // Sıralama (Drag & Drop üçün)
            $table->integer('order')->default(0);

            // Alt menyular üçün (Dropdown məntiqi)
            $table->unsignedBigInteger('parent_id')->nullable();

            // Aktivlik statusu
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Xarici açar (Parent silinərsə, alt menyular da silinsin)
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
