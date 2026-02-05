<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.ecommerce.products.index', compact('products'));
    }

    public function create()
    {
        // Yalnız 'product' tipli kateqoriya və teqləri gətiririk
        $categories = Category::where('type', 'product')->where('status', true)->get();
        $tags = Tag::where('type', 'product')->where('status', true)->get();
        $languages = Language::where('status', true)->get();

        return view('admin.ecommerce.products.create', compact('categories', 'tags', 'languages'));
    }

    public function store(Request $request)
    {
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'tags', 'gallery', '_token']);

        // Slug yaratmaq
        $data['slug'] = Str::slug($request->input("name.$defaultLang"));
        if(Product::where('slug', $data['slug'])->exists()){
            $data['slug'] .= '-' . time();
        }

        $data['status'] = $request->has('status');
        $data['is_featured'] = $request->has('is_featured');
        $data['stock_status'] = $request->stock_quantity > 0 ? 'instock' : 'outofstock';

        // Əsas Şəkil yükləmə
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/products');
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
            $file->move($path, $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $product = Product::create($data);

        // Teqləri əlaqələndirmək
        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }

        return redirect()->route('admin.products.index')->with('success', 'Məhsul uğurla yaradıldı.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('type', 'product')->where('status', true)->get();
        $tags = Tag::where('type', 'product')->where('status', true)->get();
        $languages = Language::where('status', true)->get();

        return view('admin.ecommerce.products.edit', compact('product', 'categories', 'tags', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "name.$defaultLang" => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'tags', 'gallery', '_token', '_method']);

        // Slug yenilənməsi
        $newSlug = Str::slug($request->input("name.$defaultLang"));
        if($product->slug !== $newSlug){
             if(Product::where('slug', $newSlug)->where('id', '!=', $id)->exists()){
                $newSlug .= '-' . time();
            }
            $data['slug'] = $newSlug;
        }

        $data['status'] = $request->has('status');
        $data['is_featured'] = $request->has('is_featured');
        $data['stock_status'] = $request->stock_quantity > 0 ? 'instock' : 'outofstock';

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $filename);
            $data['image'] = 'uploads/products/' . $filename;
        }

        $product->update($data);

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        } else {
            $product->tags()->detach();
        }

        return redirect()->route('admin.products.index')->with('success', 'Məhsul yeniləndi.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }
        $product->tags()->detach();
        $product->delete();

        return redirect()->back()->with('success', 'Məhsul silindi.');
    }
}
