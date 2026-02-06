<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\Service;
use App\Models\Reservation;

class MedicalApiController extends Controller
{
    // Bütün Həkimlər (Axtarış və Filtr ilə)
    public function doctors(Request $request)
    {
        $query = Doctor::with(['clinic', 'specialty'])->where('status', true);

        // Axtarış (Ad və ya Soyad)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // İxtisas Filtri
        if ($request->has('specialty_id')) {
            $query->where('specialty_id', $request->specialty_id);
        }

        // Klinika Filtri
        if ($request->has('clinic_id')) {
            $query->where('clinic_id', $request->clinic_id);
        }

        $doctors = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }

    // Həkim Detalı
    public function doctorDetail($id)
    {
        $doctor = Doctor::with(['clinic', 'specialty'])->find($id);

        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Həkim tapılmadı'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $doctor
        ]);
    }

    // Klinikalar
    public function clinics()
    {
        $clinics = Clinic::where('status', true)->get();
        return response()->json(['success' => true, 'data' => $clinics]);
    }

    // İxtisaslar
    public function specialties()
    {
        $specialties = Specialty::where('status', true)->get();
        return response()->json(['success' => true, 'data' => $specialties]);
    }

    // Xidmətlər
    public function services()
    {
        $services = Service::where('status', true)->get();
        return response()->json(['success' => true, 'data' => $services]);
    }

    // Rezervasiya Etmək (Login olmuş istifadəçi)
    public function makeReservation(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
        ]);

        $user = $request->user(); // Token-dən gələn istifadəçi

        $reservation = Reservation::create([
            'doctor_id' => $request->doctor_id,
            'user_id' => $user->id,
            'name' => $user->name,
            'phone' => $request->phone ?? $user->email, // Nömrə yoxdursa email yaz
            'email' => $user->email,
            'reservation_date' => $request->date,
            'reservation_time' => $request->time,
            'note' => $request->note,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rezervasiya sorğunuz qəbul edildi.',
            'data' => $reservation
        ], 201);
    }
}
