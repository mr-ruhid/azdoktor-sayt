<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Language;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        // Yalnız 'post' tipli teqlər
        $tags = Tag::where('type', 'post')->latest()->paginate(10);
        $languages = Language::where('status', true)->get();

        return view('admin.posts.tags.index', compact('tags', 'languages'));
    }

    public function store(Request $request)
    {
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
        ]);

        $data = $request->only(['name', 'status']);
        $data['type'] = 'post';

        $data['slug'] = Str::slug($request->input("name.$defaultLang"));

        if(Tag::where('slug', $data['slug'])->exists()){
            $data['slug'] .= '-' . time();
        }

        Tag::create($data);

        return redirect()->back()->with('success', 'Teq yaradıldı.');
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
