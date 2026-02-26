<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminPasswordController extends Controller
{
    public function edit()
    {
        return view('admin.password.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password_current' => 'required|string',
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->symbols(),
            ],
        ], [
            'password_current.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password' => 'Password baru minimal 8 karakter, harus mengandung huruf besar, 
            huruf kecil, dan minimal satu simbol (mis. @#$%^&*).',
        ]);

        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->password_current, $user->password)) {
            return back()->withErrors(['password_current' => 'Password saat ini salah.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLogService::log('admin_password_changed', $user);

        return redirect()->route('admin.password.edit')
            ->with('success', 'Password berhasil diubah. Silakan gunakan password baru untuk login berikutnya.');
    }
}
