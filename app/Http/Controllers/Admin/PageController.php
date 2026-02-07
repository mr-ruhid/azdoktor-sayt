<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(10);
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $languages = Language::where('status', true)->get();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request)
    {
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "title.$defaultLang" => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', '_token']);

        $data['slug'] = Str::slug($request->input("title.$defaultLang"));
        if(Page::where('slug', $data['slug'])->exists()){
            $data['slug'] .= '-' . time();
        }

        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pages'), $filename);
            $data['image'] = 'uploads/pages/' . $filename;
        }

        Page::create($data);

        return redirect()->route('admin.pages.index')->with('success', 'Səhifə yaradıldı.');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        $languages = Language::where('status', true)->get();
        return view('admin.pages.edit', compact('page', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "title.$defaultLang" => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', '_token', '_method']);

        // Slug yenilənməsi
        $newSlug = Str::slug($request->input("title.$defaultLang"));
        if($page->slug !== $newSlug){
             if(Page::where('slug', $newSlug)->where('id', '!=', $id)->exists()){
                $newSlug .= '-' . time();
            }
            $data['slug'] = $newSlug;
        }

        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            if ($page->image && File::exists(public_path($page->image))) {
                File::delete(public_path($page->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/pages'), $filename);
            $data['image'] = 'uploads/pages/' . $filename;
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')->with('success', 'Səhifə yeniləndi.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        if ($page->image && File::exists(public_path($page->image))) {
            File::delete(public_path($page->image));
        }
        $page->delete();

        return redirect()->back()->with('success', 'Səhifə silindi.');
    }
}
