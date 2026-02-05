<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.ecommerce.services.index', compact('services'));
    }

    public function create()
    {
        $languages = Language::where('status', true)->get();
        return view('admin.ecommerce.services.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', '_token']);

        // Slug yaratmaq
        $data['slug'] = Str::slug($request->input("name.$defaultLang"));
        if(Service::where('slug', $data['slug'])->exists()){
            $data['slug'] .= '-' . time();
        }

        $data['status'] = $request->has('status');

        // Şəkil yükləmə
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/services');
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
            $file->move($path, $filename);
            $data['image'] = 'uploads/services/' . $filename;
        }

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'Xidmət uğurla yaradıldı.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        $languages = Language::where('status', true)->get();
        return view('admin.ecommerce.services.edit', compact('service', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', '_token', '_method']);

        // Slug yenilənməsi
        $newSlug = Str::slug($request->input("name.$defaultLang"));
        if($service->slug !== $newSlug){
             if(Service::where('slug', $newSlug)->where('id', '!=', $id)->exists()){
                $newSlug .= '-' . time();
            }
            $data['slug'] = $newSlug;
        }

        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            if ($service->image && File::exists(public_path($service->image))) {
                File::delete(public_path($service->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/services'), $filename);
            $data['image'] = 'uploads/services/' . $filename;
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'Xidmət yeniləndi.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        if ($service->image && File::exists(public_path($service->image))) {
            File::delete(public_path($service->image));
        }
        $service->delete();

        return redirect()->back()->with('success', 'Xidmət silindi.');
    }
}
