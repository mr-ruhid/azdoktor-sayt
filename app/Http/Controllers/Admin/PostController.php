<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::where('type', 'post')->where('status', true)->get();
        $tags = Tag::where('type', 'post')->where('status', true)->get();
        $languages = Language::where('status', true)->get();

        return view('admin.posts.create', compact('categories', 'tags', 'languages'));
    }

    public function store(Request $request)
    {
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "title.$defaultLang" => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'tags', '_token']);

        // Slug
        $data['slug'] = Str::slug($request->input("title.$defaultLang"));
        if(Post::where('slug', $data['slug'])->exists()){
            $data['slug'] .= '-' . time();
        }

        $data['status'] = $request->has('status');
        $data['is_featured'] = $request->has('is_featured');

        // Şəkil
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('uploads/posts');
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }
            $file->move($path, $filename);
            $data['image'] = 'uploads/posts/' . $filename;
        }

        // SEO sahələri avtomatik $data içində var (array olduğu üçün)

        $post = Post::create($data);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Paylaşım uğurla yaradıldı.');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $categories = Category::where('type', 'post')->where('status', true)->get();
        $tags = Tag::where('type', 'post')->where('status', true)->get();
        $languages = Language::where('status', true)->get();

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'languages'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $defaultLang = Language::where('is_default', true)->first()->code;

        $request->validate([
            "title.$defaultLang" => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['image', 'tags', '_token', '_method']);

        $newSlug = Str::slug($request->input("title.$defaultLang"));
        if($post->slug !== $newSlug){
             if(Post::where('slug', $newSlug)->where('id', '!=', $id)->exists()){
                $newSlug .= '-' . time();
            }
            $data['slug'] = $newSlug;
        }

        $data['status'] = $request->has('status');
        $data['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            if ($post->image && File::exists(public_path($post->image))) {
                File::delete(public_path($post->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/posts'), $filename);
            $data['image'] = 'uploads/posts/' . $filename;
        }

        $post->update($data);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Paylaşım yeniləndi.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->image && File::exists(public_path($post->image))) {
            File::delete(public_path($post->image));
        }
        $post->tags()->detach();
        $post->delete();

        return redirect()->back()->with('success', 'Paylaşım silindi.');
    }
}
