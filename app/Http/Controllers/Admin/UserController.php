<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        // Özündən başqa hamısını göstər (və ya hamısını)
        $users = User::with('roles')->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Rol təyin et
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'İstifadəçi yaradıldı.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'role' => 'required|exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Şifrə yalnız dolu göndərilibsə dəyiş
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Rolu yenilə
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'İstifadəçi məlumatları yeniləndi.');
    }

    public function destroy($id)
    {
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Siz öz hesabınızı silə bilməzsiniz!');
        }

        $user = User::findOrFail($id);

        // Həkimi varsa əlaqəni kəs (Həkim silinmir, sadəcə user_id null olur)
        if ($user->doctor) {
            $user->doctor->update(['user_id' => null]);
        }

        $user->delete();

        return redirect()->back()->with('success', 'İstifadəçi silindi.');
    }
}
