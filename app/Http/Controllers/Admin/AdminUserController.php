<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = Admin::orderBy('id')->paginate(10);

        return view('admin.users.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->symbols(),
            ],
            'role' => 'nullable|in:superadmin,admin',
        ], [
            'password' => 'Password minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan minimal satu simbol (mis. @#$%^&*).',
        ]);

        $created = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? 'admin',
            'password' => Hash::make($validated['password']),
        ]);
        ActivityLogService::log('admin_user_create', $created);

        return redirect()->route('admin.users.index')->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function edit(Admin $user)
    {
        return view('admin.users.edit', [
            'adminUser' => $user,
        ]);
    }

    public function update(Request $request, Admin $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:admins,email,' . $user->id,
            'password' => [
                'nullable',
                'string',
                'confirmed',
                Password::min(8)->mixedCase()->symbols(),
            ],
            'role' => 'nullable|in:superadmin,admin',
        ], [
            'password' => 'Password minimal 8 karakter, harus mengandung huruf besar, huruf kecil, dan minimal satu simbol (mis. @#$%^&*).',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (array_key_exists('role', $validated)) {
            $user->role = $validated['role'] ?? 'admin';
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        ActivityLogService::log('admin_user_update', $user);

        return redirect()->route('admin.users.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(Admin $user)
    {
        // Jangan izinkan menghapus akun sendiri
        if (auth('admin')->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        // Opsional: cegah menghapus admin terakhir
        if (Admin::count() <= 1) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        ActivityLogService::log('admin_user_delete', $user);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Admin berhasil dihapus.');
    }
}

