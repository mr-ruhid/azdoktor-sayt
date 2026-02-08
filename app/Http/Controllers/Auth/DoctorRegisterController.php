<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorRequest;
use App\Models\Specialty;
use App\Models\Clinic;
use App\Models\Page;

class DoctorRegisterController extends Controller
{
    /**
     * Həkim qeydiyyatı formasını göstər (Əgər birbaşa linklə girilərsə)
     * Qeyd: Biz əsasən vahid qeydiyyat səhifəsini istifadə edirik, amma bu metod da lazımdır.
     */
    public function showRegistrationForm()
    {
        $page = new Page();
        $page->setTranslation('title', app()->getLocale(), 'Həkim Qeydiyyatı');

        $specialties = Specialty::all();
        $clinics = Clinic::where('status', true)->get();

        // Əgər ayrı səhifə istəyirsinizsə 'auth.register_doctor',
        // yoxsa vahid səhifənin 'doctor' tabını açmaq üçün məntiq qura bilərik.
        // Hələlik user_register səhifəsinə yönləndirək (vahid səhifə).
        return view('auth.user_register', compact('specialties', 'clinics', 'page'));
    }

    /**
     * Həkim Müraciətini Qəbul Et (POST)
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:doctor_requests,email',
            'phone' => 'required|string|max:20',
            'specialty_id' => 'required|exists:specialties,id',
            'title' => 'required|string',
            'cv_file' => 'required|mimes:pdf,doc,docx|max:5120', // 5MB max
            'clinic_id' => 'nullable|exists:clinics,id',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $file = $request->file('cv_file');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/cvs'), $filename);
            $cvPath = 'uploads/cvs/' . $filename;
        }

        DoctorRequest::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'title' => $request->title,
            'specialty_id' => $request->specialty_id,
            'clinic_id' => $request->clinic_id,
            'cv_file' => $cvPath,
            'status' => 'new' // Status: Yeni
        ]);

        return redirect()->route('home')->with('success', 'Müraciətiniz qeydə alındı! Admin təsdiqindən sonra sizinlə əlaqə saxlanılacaq.');
    }
}
