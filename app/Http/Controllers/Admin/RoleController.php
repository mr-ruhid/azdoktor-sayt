<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Rollar Siyahısı
    public function index()
    {
        // Super Admin rolunu siyahıda göstərməyə bilərik ki, kimsə səhvən silməsin
        $roles = Role::where('name', '!=', 'Super Admin')->get();
        return view('admin.users.roles.index', compact('roles'));
    }

    // Yeni Rol Yaratma Səhifəsi
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($data) {
            // İcazə adını '_' işarəsinə görə bölüb qrup yaradırıq (məs: doctor_view -> doctor qrupu)
            return explode('_', $data->name)[0];
        });

        return view('admin.users.roles.create', compact('permissions'));
    }

    // Rolu Yadda Saxla
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);

        if($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol yaradıldı və icazələr verildi.');
    }

    // Rolu Redaktə Et
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        if($role->name == 'Super Admin') {
            return redirect()->back()->with('error', 'Super Admin rolu dəyişdirilə bilməz.');
        }

        $permissions = Permission::all()->groupBy(function($data) {
            return explode('_', $data->name)[0];
        });

        return view('admin.users.roles.edit', compact('role', 'permissions'));
    }

    // Rolu Yenilə
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        if($role->name == 'Super Admin') {
            return abort(403);
        }

        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'permissions' => 'array'
        ]);

        $role->update(['name' => $request->name]);

        if($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Rol və icazələr yeniləndi.');
    }

    // Rolu Sil
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if($role->name == 'Super Admin') {
            return redirect()->back()->with('error', 'Super Admin silinə bilməz!');
        }

        $role->delete();
        return redirect()->back()->with('success', 'Rol silindi.');
    }
}
