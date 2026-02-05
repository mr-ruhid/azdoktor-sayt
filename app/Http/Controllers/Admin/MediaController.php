<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function index()
    {
        // Ən son yüklənənlər birinci gəlsin
        $files = Media::latest()->paginate(24);
        return view('admin.media.index', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Maks 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $file->getClientMimeType();

            // Benzersiz ad yarat
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Public qovluğuna yüklə
            $path = public_path('uploads/media');
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $file->move($path, $filename);

            // Bazaya yaz
            Media::create([
                'file_name' => $originalName,
                'file_path' => 'uploads/media/' . $filename,
                'file_type' => $fileType,
                'file_size' => $fileSize,
            ]);

            return redirect()->back()->with('success', 'Fayl uğurla yükləndi.');
        }

        return redirect()->back()->with('error', 'Fayl seçilməyib.');
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);

        // Fiziki faylı sil
        if(File::exists(public_path($media->file_path))) {
            File::delete(public_path($media->file_path));
        }

        // Bazadan sil
        $media->delete();

        return redirect()->back()->with('success', 'Fayl silindi.');
    }
}
