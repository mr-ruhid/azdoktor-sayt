<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'commentable_id',
        'commentable_type',
        'content',
        'rating',
        'is_approved',
        'parent_id'
    ];

    // Nəyə aiddir? (Həkim, Məhsul, Post)
    public function commentable()
    {
        return $this->morphTo();
    }

    // İstifadəçi (əgər varsa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cavablar (Replies)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Əsas şərh (Parent)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
