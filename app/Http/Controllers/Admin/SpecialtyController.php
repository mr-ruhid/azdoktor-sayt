<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::latest()->paginate(10);
        $languages = Language::where('status', true)->get();
        return view('admin.medical.specialties.index', compact('specialties', 'languages'));
    }

    public function store(Request $request)
    {
        // Validation: Ən azı bir dildə ad olmalıdır (Default dil)
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->only(['name', 'status']);

        // Slug yaratmaq (Default dildəki addan)
        $data['slug'] = Str::slug($request->input("name.$defaultLang"));

        // İkon yükləmə
        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/specialties'), $filename);
            $data['icon'] = 'uploads/specialties/' . $filename;
        }

        Specialty::create($data);

        return redirect()->back()->with('success', 'İxtisas uğurla yaradıldı.');
    }

    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->only(['name', 'status']);

        // Slug yenilə
        $data['slug'] = Str::slug($request->input("name.$defaultLang"));

        if ($request->hasFile('icon')) {
            // Köhnəni sil
            if($specialty->icon && File::exists(public_path($specialty->icon))) {
                File::delete(public_path($specialty->icon));
            }

            $file = $request->file('icon');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/specialties'), $filename);
            $data['icon'] = 'uploads/specialties/' . $filename;
        }

        $specialty->update($data);

        return redirect()->back()->with('success', 'İxtisas yeniləndi.');
    }

    public function destroy($id)
    {
        $specialty = Specialty::findOrFail($id);

        if($specialty->icon && File::exists(public_path($specialty->icon))) {
            File::delete(public_path($specialty->icon));
        }

        $specialty->delete();
        return redirect()->back()->with('success', 'İxtisas silindi.');
    }
}
