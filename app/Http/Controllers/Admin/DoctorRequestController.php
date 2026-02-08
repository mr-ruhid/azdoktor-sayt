<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorRequest;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DoctorRequestController extends Controller
{
    public function index()
    {
        // Ən yenilər birinci gəlsin
        $requests = DoctorRequest::latest()->paginate(10);
        return view('admin.medical.doctor_requests.index', compact('requests'));
    }

    // Statusu dəyişmək və Hesab Yaratmaq
    public function updateStatus(Request $request, $id)
    {
        $doctorRequest = DoctorRequest::findOrFail($id);
        $newStatus = $request->status;

        // Əgər status "approved" (Təsdiqlə) seçilibsə və əvvəl təsdiqlənməyibsə
        if ($newStatus == 'approved' && $doctorRequest->status != 'approved') {

            // Email yoxlanışı
            if (User::where('email', $doctorRequest->email)->exists()) {
                return redirect()->back()->with('error', 'Bu e-poçt ilə artıq istifadəçi mövcuddur.');
            }

            // Transaction istifadə edirik ki, hər şey qaydasında olmasa ləğv etsin
            DB::transaction(function () use ($doctorRequest) {
                // 1. User Hesabı Yarat
                $password = Str::random(10); // Müvəqqəti şifrə
                // QEYD: Real layihədə bu şifrəni həkimin emailinə göndərmək lazımdır.

                $user = User::create([
                    'name' => $doctorRequest->first_name,
                    'surname' => $doctorRequest->last_name,
                    'email' => $doctorRequest->email,
                    'phone' => $doctorRequest->phone,
                    'role_type' => 2, // 2 = Doctor
                    'password' => Hash::make($password),
                    'email_verified_at' => now(),
                ]);

                // 2. Doctor Profili Yarat
                Doctor::create([
                    'user_id' => $user->id,
                    'clinic_id' => $doctorRequest->clinic_id,
                    'specialty_id' => $doctorRequest->specialty_id,
                    'first_name' => $doctorRequest->first_name,
                    'last_name' => $doctorRequest->last_name,
                    'email' => $doctorRequest->email,
                    'phone' => $doctorRequest->phone,
                    'status' => true, // Aktiv
                    'accepts_reservations' => true,
                    // Digər sahələr default qalacaq
                ]);

                // 3. Sorğunun statusunu dəyiş
                $doctorRequest->update(['status' => 'approved']);
            });

            return redirect()->back()->with('success', 'Həkim hesabı uğurla yaradıldı və təsdiqləndi.');
        }

        // Digər statuslar (contacted, rejected) sadəcə yenilənir
        $doctorRequest->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Status yeniləndi.');
    }

    // Sorğuna silmək
    public function destroy($id)
    {
        $doctorRequest = DoctorRequest::findOrFail($id);

        // CV faylını da serverdən silək
        if ($doctorRequest->cv_file && File::exists(public_path($doctorRequest->cv_file))) {
            File::delete(public_path($doctorRequest->cv_file));
        }

        $doctorRequest->delete();

        return redirect()->back()->with('success', 'Sorğu silindi.');
    }
}
