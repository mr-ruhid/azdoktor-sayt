<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorRequest;
use Illuminate\Support\Facades\File;

class DoctorRequestController extends Controller
{
    public function index()
    {
        // Ən yenilər birinci gəlsin
        $requests = DoctorRequest::latest()->paginate(10);
        return view('admin.medical.doctor_requests.index', compact('requests'));
    }

    // Statusu dəyişmək (Məs: Yeni -> Əlaqə Saxlanıldı)
    public function updateStatus(Request $request, $id)
    {
        $doctorRequest = DoctorRequest::findOrFail($id);
        $doctorRequest->update(['status' => $request->status]);

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
