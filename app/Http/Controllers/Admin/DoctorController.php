<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\Language;
use Illuminate\Support\Facades\File;

class DoctorController extends Controller
{
    public function index()
    {
        // Clinic və Specialty əlaqələrini də yükləyirik (Eager Loading)
        $doctors = Doctor::with(['clinic', 'specialty'])->latest()->paginate(10);
        return view('admin.medical.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $clinics = Clinic::where('status', true)->get();
        $specialties = Specialty::where('status', true)->get();
        $languages = Language::where('status', true)->get();

        return view('admin.medical.doctors.create', compact('clinics', 'specialties', 'languages'));
    }

    public function store(Request $request)
    {
        // Sadə validasiya
        $request->validate([
            'email' => 'nullable|email',
            'image' => 'nullable|image|max:2048',
            'clinic_id' => 'required|exists:clinics,id',
            'specialty_id' => 'required|exists:specialties,id',
        ]);

        $data = $request->except(['image', '_token']);

        // Şəkil yükləmə
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/doctors'), $filename);
            $data['image'] = 'uploads/doctors/' . $filename;
        }

        // Checkbox statusu (əgər gəlməyibsə 0 olsun)
        $data['status'] = $request->has('status');

        Doctor::create($data);

        return redirect()->route('admin.doctors.index')->with('success', 'Həkim əlavə edildi.');
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        $clinics = Clinic::where('status', true)->get();
        $specialties = Specialty::where('status', true)->get();
        $languages = Language::where('status', true)->get();

        return view('admin.medical.doctors.edit', compact('doctor', 'clinics', 'specialties', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $data = $request->except(['image', '_token', '_method']);

        if ($request->hasFile('image')) {
            if ($doctor->image && File::exists(public_path($doctor->image))) {
                File::delete(public_path($doctor->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/doctors'), $filename);
            $data['image'] = 'uploads/doctors/' . $filename;
        }

        $data['status'] = $request->has('status');

        $doctor->update($data);

        return redirect()->route('admin.doctors.index')->with('success', 'Həkim məlumatları yeniləndi.');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        if ($doctor->image && File::exists(public_path($doctor->image))) {
            File::delete(public_path($doctor->image));
        }
        $doctor->delete();
        return redirect()->back()->with('success', 'Həkim silindi.');
    }
}
