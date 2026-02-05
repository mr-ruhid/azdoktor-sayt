<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        // Tarixçəyə görə sıralayaq (Ən yeni rezervasiyalar yuxarıda)
        $reservations = Reservation::with('doctor')->latest()->paginate(15);
        return view('admin.medical.reservations.index', compact('reservations'));
    }

    // Status Dəyişmək (Ajax və ya Form ilə)
    public function updateStatus(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Rezervasiya statusu yeniləndi.');
    }

    // Silmək
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->back()->with('success', 'Rezervasiya silindi.');
    }
}
