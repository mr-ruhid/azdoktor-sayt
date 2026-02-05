<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Language;
use Illuminate\Support\Str;

class ProductTagController extends Controller
{
    public function index()
    {
        // Yalnız 'product' (Məhsul) tipli teqlər
        $tags = Tag::where('type', 'product')->latest()->paginate(10);
        $languages = Language::where('status', true)->get();

        return view('admin.ecommerce.tags.index', compact('tags', 'languages'));
    }

    public function store(Request $request)
    {
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
        ]);

        $data = $request->only(['name', 'status']);
        $data['type'] = 'product'; // MƏCBURİ: Tip 'product' olmalıdır

        // Slug yaratmaq
        $data['slug'] = Str::slug($request->input("name.$defaultLang"));

        if(Tag::where('slug', $data['slug'])->exists()){
            $data['slug'] .= '-' . time();
        }

        Tag::create($data);

        return redirect()->back()->with('success', 'Məhsul teqi yaradıldı.');
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
        ]);

        $data = $request->only(['name', 'status']);

        $newSlug = Str::slug($request->input("name.$defaultLang"));
        if($tag->slug !== $newSlug){
             if(Tag::where('slug', $newSlug)->where('id', '!=', $id)->exists()){
                $newSlug .= '-' . time();
            }
            $data['slug'] = $newSlug;
        }

        $tag->update($data);

        return redirect()->back()->with('success', 'Teq yeniləndi.');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->back()->with('success', 'Teq silindi.');
    }
}
