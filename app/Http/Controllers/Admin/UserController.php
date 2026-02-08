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
    /**
     * İstifadəçilərin Siyahısı
     * Həkimlər (role_type = 2) burada görünmür.
     */
    public function index()
    {
        // Yalnız User (0) və Admin (1) olanları gətir
        $users = User::whereIn('role_type', [0, 1])
                     ->with('roles')
                     ->latest()
                     ->paginate(15);

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
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'password' => 'required|string|min:8',
            'role_type' => 'required|integer|in:0,1', // 0=User, 1=Admin
            // 'role' => 'exists:roles,name', // Əgər Spatie rolu da seçilirsə
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_date,
            'role_type' => $request->role_type,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), // Admin yaradırsa təsdiqlənmiş sayılır
        ]);

        // Spatie Rolu (Opsional: Əgər formdan gəlirsə)
        if ($request->has('role')) {
            $user->assignRole($request->role);
        }

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
            'surname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'role_type' => 'required|integer|in:0,1',
        ]);

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->birth_date = $request->birth_date;
        $user->role_type = $request->role_type;

        // Şifrə yalnız dolu göndərilibsə dəyiş
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Rolu yenilə (Spatie)
        if ($request->has('role')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('admin.users.index')->with('success', 'İstifadəçi məlumatları yeniləndi.');
    }

    public function destroy($id)
    {
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Siz öz hesabınızı silə bilməzsiniz!');
        }

        $user = User::findOrFail($id);

        // Həkimləri buradan silmək olmaz (təhlükəsizlik üçün)
        if ($user->role_type == 2) {
             return redirect()->back()->with('error', 'Həkimləri yalnız Həkimlər bölməsindən silə bilərsiniz.');
        }

        // Əlaqəli məlumatları təmizləmək (opsional)
        if ($user->doctor) {
            $user->doctor->update(['user_id' => null]);
        }

        $user->delete();

        return redirect()->back()->with('success', 'İstifadəçi silindi.');
    }
}
