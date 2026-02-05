<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DoctorAccountController extends Controller
{
    public function index()
    {
        // User əlaqəsi ilə birlikdə həkimləri gətiririk
        $doctors = Doctor::with('user')->latest()->paginate(10);
        return view('admin.medical.accounts.index', compact('doctors'));
    }

    // Hesab yaratmaq və ya Şifrə dəyişmək
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'email' => 'required|email', // User cədvəlində unique yoxlanışını aşağıda edəcəyik
            'password' => 'nullable|min:6',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        // 1. Əgər həkimin artıq hesabı varsa -> Güncəllə
        if ($doctor->user_id) {
            $user = User::findOrFail($doctor->user_id);

            // Email dəyişdirilirsə, başqasında olub-olmadığını yoxla
            if ($user->email !== $request->email) {
                $request->validate(['email' => 'unique:users,email']);
                $user->email = $request->email;
            }

            // Şifrə daxil edilibsə yenilə
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();
            $message = 'Həkimin giriş məlumatları yeniləndi.';
        }
        // 2. Hesabı yoxdursa -> Yeni Yarat
        else {
            $request->validate([
                'email' => 'unique:users,email',
                'password' => 'required|min:6'
            ]);

            $user = User::create([
                'name' => $doctor->full_name, // Həkimin adını user adına yazırıq
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // "Doctor" rolunu veririk (RolePermissionSeeder-də yaratmışdıq)
            $user->assignRole('Doctor');

            // Həkimi bu user-ə bağlayırıq
            $doctor->update(['user_id' => $user->id]);

            $message = 'Həkim üçün yeni hesab yaradıldı və "Doctor" rolu verildi.';
        }

        return redirect()->back()->with('success', $message);
    }

    // Hesabı silmək (Həkimi silmir, sadəcə giriş imkanını ləğv edir)
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        if ($doctor->user_id) {
            $user = User::findOrFail($doctor->user_id);
            // Useri silirik
            $user->delete();

            // Həkimdə user_id-ni null edirik
            $doctor->update(['user_id' => null]);

            return redirect()->back()->with('success', 'Həkimin hesabı silindi. Artıq giriş edə bilməz.');
        }

        return redirect()->back()->with('error', 'Bu həkimin hesabı yoxdur.');
    }
}
