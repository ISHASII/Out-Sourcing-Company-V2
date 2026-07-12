<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->role !== 'superadmin') {
                abort(403, 'Akses ditolak. Halaman ini hanya untuk Superadmin.');
            }
            return $next($request);
        });
    }

    // ----------------------------------------------------------------
    //  DASHBOARD — overview stats + admin list
    // ----------------------------------------------------------------
    public function dashboard()
    {
        $admins        = User::whereIn('role', ['hrd', 'superadmin'])
                             ->where('id', '!=', auth()->id())
                             ->latest()
                             ->get();
        $totalAdmin    = $admins->count();
        $activeAdmin   = $admins->where('is_active', true)->count();
        $inactiveAdmin = $admins->where('is_active', false)->count();

        return view('superadmin.dashboard', compact('admins', 'totalAdmin', 'activeAdmin', 'inactiveAdmin'));
    }

    // ----------------------------------------------------------------
    //  CREATE — show form
    // ----------------------------------------------------------------
    public function create()
    {
        return view('superadmin.create');
    }

    // ----------------------------------------------------------------
    //  STORE — save new admin account
    // ----------------------------------------------------------------
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:120'],
            'email'     => ['required', 'email', 'max:120', 'unique:users,email'],
            'role'      => ['required', 'in:hrd,superadmin'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'role.required'      => 'Role akun wajib dipilih.',
            'role.in'            => 'Role tidak valid.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role'      => $data['role'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Akun admin baru berhasil dibuat.');
    }

    // ----------------------------------------------------------------
    //  EDIT — show edit form
    // ----------------------------------------------------------------
    public function edit(User $admin)
    {
        if (!in_array($admin->role, ['hrd', 'superadmin']) || $admin->id === auth()->id()) {
            abort(403);
        }
        return view('superadmin.edit', compact('admin'));
    }

    // ----------------------------------------------------------------
    //  UPDATE — save changes
    // ----------------------------------------------------------------
    public function update(Request $request, User $admin)
    {
        if (!in_array($admin->role, ['hrd', 'superadmin']) || $admin->id === auth()->id()) {
            abort(403);
        }

        $rules = [
            'name'      => ['required', 'string', 'max:120'],
            'email'     => ['required', 'email', 'max:120', 'unique:users,email,' . $admin->id],
            'is_active' => ['nullable', 'boolean'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $data = $request->validate($rules, [
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah digunakan oleh akun lain.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $admin->name      = $data['name'];
        $admin->email     = $data['email'];
        $admin->is_active = $request->boolean('is_active', true);

        if ($request->filled('password')) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Data admin berhasil diperbarui.');
    }

    // ----------------------------------------------------------------
    //  DESTROY — delete admin account
    // ----------------------------------------------------------------
    public function destroy(User $admin)
    {
        if (!in_array($admin->role, ['hrd', 'superadmin']) || $admin->id === auth()->id()) {
            abort(403);
        }

        $admin->delete();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Akun admin berhasil dihapus.');
    }

    // ----------------------------------------------------------------
    //  TOGGLE STATUS — active ↔ inactive
    // ----------------------------------------------------------------
    public function toggleStatus(User $admin)
    {
        if (!in_array($admin->role, ['hrd', 'superadmin']) || $admin->id === auth()->id()) {
            abort(403);
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $status = $admin->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('superadmin.dashboard')
            ->with('success', "Akun admin berhasil {$status}.");
    }
}

