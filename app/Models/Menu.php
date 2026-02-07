<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; // Bu sətir paketi çağırır

class Menu extends Model
{
    use HasFactory;
    use HasTranslations; // Bu trait modelə tərcümə özəlliyini əlavə edir

    protected $table = 'menus';

    protected $fillable = [
        'type',      // pc_sidebar, mobile_navbar, footer
        'role',      // all, guest, auth, doctor
        'title',     // Tərcümə olunan sahə
        'url',       // Link
        'icon',      // FontAwesome ikon
        'order',     // Sıralama
        'parent_id', // Alt menyu üçün
        'status'     // Aktiv/Deaktiv
    ];

    // Bu hissə sistemə deyir ki, 'title' sütunu JSON-dur və tərcümə olunmalıdır
    public $translatable = ['title'];

    // Alt menyuları gətirmək üçün (Sıralama ilə)
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order', 'asc');
    }

    // Valideyn menyunu gətirmək üçün
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    // Sadəcə aktiv menyuları gətirmək üçün köməkçi funksiya
    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('order', 'asc');
    }
}
