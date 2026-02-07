<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sidebar;
use Illuminate\Support\Facades\File;

class SidebarController extends Controller
{
    public function index()
    {
        $sidebars = Sidebar::all();
        return view('admin.sidebars.index', compact('sidebars'));
    }

    public function edit($id)
    {
        $sidebar = Sidebar::findOrFail($id);
        return view('admin.sidebars.edit', compact('sidebar'));
    }

    public function update(Request $request, $id)
    {
        $sidebar = Sidebar::findOrFail($id);

        $request->validate([
            'logo' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['status']);

        // Settings JSON yeniləmə
        $settings = $sidebar->settings ?? [];
        if ($request->has('settings')) {
            foreach ($request->settings as $key => $value) {
                $settings[$key] = $value;
            }
        }
        // Checkboxlar göndərilməyibsə false edirik (yalnız boolean olanları)
        if($sidebar->type == 'mobile_navbar') {
            $settings['show_search'] = $request->has('settings.show_search');
            $settings['sticky'] = $request->has('settings.sticky');
        }
        if($sidebar->type == 'pc_sidebar') {
            $settings['show_language_switcher'] = $request->has('settings.show_language_switcher');
        }

        $data['settings'] = $settings;
        $data['status'] = $request->has('status');

        // Logo yükləmə
        if ($request->hasFile('logo')) {
            // Köhnəni sil
            if ($sidebar->logo && File::exists(public_path($sidebar->logo))) {
                File::delete(public_path($sidebar->logo));
            }

            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/sidebars'), $filename);
            $data['logo'] = 'uploads/sidebars/' . $filename;
        }

        $sidebar->update($data);

        return redirect()->route('admin.sidebars.index')->with('success', 'Panel tənzimləmələri yeniləndi.');
    }
}
