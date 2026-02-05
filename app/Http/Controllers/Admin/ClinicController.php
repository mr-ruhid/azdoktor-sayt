<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Clinic;
use App\Models\Language;
use Illuminate\Support\Facades\File;

class ClinicController extends Controller
{
    // Klinika Siyahısı (Index)
    public function index()
    {
        $clinics = Clinic::latest()->paginate(10);
        $languages = Language::where('status', true)->get();

        // API Key (Əgər köhnə settings cədvəli varsa oradan, yoxdursa boş)
        // Gələcəkdə öz settings cədvəlimizdən çəkəcəyik
        $yandexApiKey = '';

        return view('admin.medical.clinics.index', compact('clinics', 'languages', 'yandexApiKey'));
    }

    // Yeni Klinika Yaratmaq (Store)
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['phone', 'email', 'status', 'latitude', 'longitude']);

        // Tərcümə olunan sahələr
        $data['name'] = $request->input('name');
        $data['address'] = $request->input('address');
        $data['description'] = $request->input('description');

        // Şəkil Yükləmə
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/clinics');

            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $file->move($path, $filename);
            $data['image'] = 'uploads/clinics/' . $filename;
        }

        Clinic::create($data);

        return redirect()->back()->with('success', 'Klinika uğurla əlavə edildi.');
    }

    // Yeniləmək (Update)
    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $request->validate([
            'phone' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['phone', 'email', 'status', 'latitude', 'longitude']);

        $data['name'] = $request->input('name');
        $data['address'] = $request->input('address');
        $data['description'] = $request->input('description');

        if ($request->hasFile('image')) {
            // Köhnə şəkli sil
            if ($clinic->image && File::exists(public_path($clinic->image))) {
                File::delete(public_path($clinic->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clinics'), $filename);
            $data['image'] = 'uploads/clinics/' . $filename;
        }

        $clinic->update($data);

        return redirect()->back()->with('success', 'Klinika məlumatları yeniləndi.');
    }

    // Silmək (Destroy)
    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);

        if ($clinic->image && File::exists(public_path($clinic->image))) {
            File::delete(public_path($clinic->image));
        }

        $clinic->delete();

        return redirect()->back()->with('success', 'Klinika silindi.');
    }
}
