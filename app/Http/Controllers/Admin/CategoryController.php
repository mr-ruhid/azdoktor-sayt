<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        // Yalnız 'post' tipli kateqoriyalar
        $categories = Category::where('type', 'post')->latest()->paginate(10);
        $languages = Language::where('status', true)->get();

        return view('admin.posts.categories.index', compact('categories', 'languages'));
    }

    public function store(Request $request)
    {
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
        ]);

        $data = $request->only(['name', 'status']);
        $data['type'] = 'post'; // Sabit olaraq 'post'

        // Slug yaratmaq (Default dildəki addan)
        $data['slug'] = Str::slug($request->input("name.$defaultLang"));

        // Slug unikal olmalıdır
        if(Category::where('slug', $data['slug'])->exists()){
            $data['slug'] .= '-' . time();
        }

        Category::create($data);

        return redirect()->back()->with('success', 'Kateqoriya yaradıldı.');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
        ]);

        $data = $request->only(['name', 'status']);

        // Slug yenilənməsi (İstəyə bağlı, adətən SEO üçün dəyişilməməsi məsləhətdir, amma burada yeniləyirik)
        $newSlug = Str::slug($request->input("name.$defaultLang"));
        if($category->slug !== $newSlug){
             if(Category::where('slug', $newSlug)->where('id', '!=', $id)->exists()){
                $newSlug .= '-' . time();
            }
            $data['slug'] = $newSlug;
        }

        $category->update($data);

        return redirect()->back()->with('success', 'Kateqoriya yeniləndi.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kateqoriya silindi.');
    }
}
