<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Fayl sistemi üçün vacibdir

class MenuController extends Controller
{
    /**
     * Menyuların siyahısı
     */
    public function index(Request $request)
    {
        $currentType = $request->get('type', 'pc_sidebar');

        $menus = Menu::where('type', $currentType)
                     ->whereNull('parent_id')
                     ->orderBy('order')
                     ->with('children')
                     ->get();

        return view('admin.menus.index', compact('menus', 'currentType'));
    }

    /**
     * Yeni menyu yaratma formu
     */
    public function create(Request $request)
    {
        $currentType = $request->get('type', 'pc_sidebar');
        $languages = Language::where('status', true)->get();

        $parents = Menu::where('type', $currentType)
                       ->whereNull('parent_id')
                       ->orderBy('order')
                       ->get();

        // Qovluqlardakı səhifələri gətiririk
        $routes = $this->getRouteList();

        return view('admin.menus.create', compact('languages', 'parents', 'currentType', 'routes'));
    }

    /**
     * Bazaya yazmaq
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'role' => 'required|string',
            'title' => 'required|array',
        ]);

        $menu = new Menu();
        $menu->type = $request->type;
        $menu->role = $request->role;
        $menu->url = $request->url;
        $menu->icon = $request->icon;
        $menu->parent_id = $request->parent_id;
        $menu->status = $request->has('status') ? true : false;

        $maxOrder = Menu::where('type', $request->type)
                        ->where('parent_id', $request->parent_id)
                        ->max('order');
        $menu->order = $maxOrder + 1;

        foreach ($request->title as $langCode => $value) {
            $menu->setTranslation('title', $langCode, $value);
        }

        $menu->save();

        return redirect()->route('admin.menus.index', ['type' => $request->type])
                         ->with('success', 'Menyu uğurla yaradıldı.');
    }

    /**
     * Redaktə formu
     */
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $languages = Language::where('status', true)->get();

        $parents = Menu::where('type', $menu->type)
                       ->whereNull('parent_id')
                       ->where('id', '!=', $id)
                       ->orderBy('order')
                       ->get();

        // Qovluqlardakı səhifələri gətiririk
        $routes = $this->getRouteList();

        return view('admin.menus.edit', compact('menu', 'languages', 'parents', 'routes'));
    }

    /**
     * Yeniləmək
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $request->validate([
            'role' => 'required|string',
            'title' => 'required|array',
        ]);

        $menu->type = $request->type;
        $menu->role = $request->role;
        $menu->url = $request->url;
        $menu->icon = $request->icon;
        $menu->parent_id = $request->parent_id;
        $menu->status = $request->has('status') ? true : false;

        foreach ($request->title as $langCode => $value) {
            $menu->setTranslation('title', $langCode, $value);
        }

        $menu->save();

        return redirect()->route('admin.menus.index', ['type' => $menu->type])
                         ->with('success', 'Menyu yeniləndi.');
    }

    /**
     * Silmək
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $type = $menu->type;
        $menu->delete();

        return redirect()->route('admin.menus.index', ['type' => $type])
                         ->with('success', 'Menyu silindi.');
    }

    /**
     * AJAX ilə sıralama
     */
    public function sort(Request $request)
    {
        $menus = Menu::all();
        foreach ($menus as $menu) {
            foreach ($request->order as $order) {
                if ($order['id'] == $menu->id) {
                    $menu->update(['order' => $order['position']]);
                }
            }
        }
        return response('Update Successfully.', 200);
    }

    /**
     * KÖMƏKÇİ FUNKSİYA: View qovluqlarını skan edir
     */
    private function getRouteList()
    {
        $files = [];

        // 1. Standart Səhifələr (Standart)
        $standartPath = resource_path('views/public/standart');
        if (File::exists($standartPath)) {
            foreach (File::files($standartPath) as $file) {
                $name = $file->getFilenameWithoutExtension();
                // Home səhifəsini '/' olaraq işarələyək, digərlərini olduğu kimi
                $url = ($name == 'home') ? '/' : $name;
                $files[$url] = ucfirst($name) . " (Standart)";
            }
        }

        // 2. Əlavə Səhifələr (Addpage)
        $addPagePath = resource_path('views/public/addpage');
        if (File::exists($addPagePath)) {
            foreach (File::files($addPagePath) as $file) {
                $name = $file->getFilenameWithoutExtension();
                // Bura birbaşa fayl adı düşür
                $files[$name] = ucfirst($name) . " (Xüsusi)";
            }
        }

        return $files;
    }
}
