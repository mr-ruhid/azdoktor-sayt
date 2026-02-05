<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Şərhlərin Siyahısı
    public function index(Request $request)
    {
        $type = $request->route()->getName(); // admin.comments.doctors, admin.comments.blogs...

        $query = Comment::with('commentable')->whereNull('parent_id')->latest(); // Yalnız əsas şərhləri gətir

        // Route adına görə filtrləmə
        if (str_contains($type, 'doctors')) {
            $query->where('commentable_type', 'App\Models\Doctor');
            $pageTitle = 'Həkim Şərhləri';
            $filter = 'doctor';
        } elseif (str_contains($type, 'products')) {
            $query->where('commentable_type', 'App\Models\Product');
            $pageTitle = 'Məhsul Şərhləri';
            $filter = 'product';
        } elseif (str_contains($type, 'blogs')) {
            $query->where('commentable_type', 'App\Models\Post');
            $pageTitle = 'Bloq Şərhləri';
            $filter = 'blog';
        } else {
            $pageTitle = 'Bütün Şərhlər';
            $filter = 'all';
        }

        $comments = $query->paginate(15);

        return view('admin.comments.index', compact('comments', 'pageTitle', 'filter'));
    }

    // Status Dəyişmək (Təsdiq/Ləğv)
    public function updateStatus(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->update(['is_approved' => $request->status]);

        return redirect()->back()->with('success', 'Şərh statusu yeniləndi.');
    }

    // Cavab Yazmaq
    public function reply(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:comments,id',
            'content' => 'required|string'
        ]);

        $parent = Comment::findOrFail($request->parent_id);

        Comment::create([
            'user_id' => Auth::id(), // Admin
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'commentable_id' => $parent->commentable_id,
            'commentable_type' => $parent->commentable_type,
            'content' => $request->content,
            'parent_id' => $parent->id,
            'is_approved' => true, // Admin cavabı avtomatik təsdiqlənir
        ]);

        return redirect()->back()->with('success', 'Cavabınız dərc edildi.');
    }

    // Silmək
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Şərh silindi.');
    }
}
