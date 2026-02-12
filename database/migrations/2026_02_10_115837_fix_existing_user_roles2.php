<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Doctor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Mövcud Həkimləri tapırıq (User ID-si olanlar)
        $doctorUserIds = Doctor::whereNotNull('user_id')->pluck('user_id');

        // 2. Həmin istifadəçilərin rolunu '2' (Həkim) edirik
        if ($doctorUserIds->count() > 0) {
            User::whereIn('id', $doctorUserIds)->update(['role_type' => 2]);
        }

        // 3. Admini tapıb rolunu '1' edirik (Məsələn, ID=1 olan və ya Spatie 'Super Admin' rolu olan)
        // Adətən ilk istifadəçi Admin olur
        $admin = User::find(1);
        if ($admin) {
            $admin->update(['role_type' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri qaytarmaq lazım deyil, çünki bu məlumat düzəlişidir
    }
};
