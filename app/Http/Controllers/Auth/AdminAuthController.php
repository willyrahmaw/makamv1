<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\JsonResponse;
use App\Services\ActivityLogService;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        // Captcha penjumlahan angka sederhana
        $a = random_int(1, 100);
        $b = random_int(1, 100);
        $question = "{$a} + {$b}";

        session([
            'admin_login_captcha_answer' => $a + $b,
        ]);

        return view('admin.auth.login', [
            'captchaQuestion' => $question,
        ]);
    }

    public function refreshCaptcha(): JsonResponse
    {
        $a = random_int(1, 100);
        $b = random_int(1, 100);
        $question = "{$a} + {$b}";

        session([
            'admin_login_captcha_answer' => $a + $b,
        ]);

        return response()->json([
            'question' => $question . ' = ?',
        ]);
    }

    public function login(Request $request)
    {
        // Rate limiting: maksimal 5 percobaan per menit
        $key = 'login.' . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            ActivityLogService::log('admin_login_rate_limited', null, [
                'ip' => $request->ip(),
                'available_in_seconds' => $seconds,
            ], $request);
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.',
            ])->onlyInput('email');
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha_answer' => 'required|numeric',
        ]);

        // Validasi captcha penjumlahan
        $expected = (int) $request->session()->get('admin_login_captcha_answer');
        if (!$expected || (int) $validated['captcha_answer'] !== $expected) {
            ActivityLogService::log('admin_login_failed', null, [
                'reason' => 'captcha_wrong',
                'email' => $validated['email'],
            ], $request);
            return back()->withErrors([
                'captcha_answer' => 'Jawaban keamanan salah.',
            ])->onlyInput('email');
        }

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key);
            $request->session()->forget('admin_login_captcha_answer');
            $request->session()->regenerate();
            ActivityLogService::log('admin_login_success', Auth::guard('admin')->user(), [
                'remember' => $request->boolean('remember'),
            ], $request);
            return redirect()->intended(route('admin.dashboard'));
        }

        ActivityLogService::log('admin_login_failed', null, [
            'reason' => 'invalid_credentials',
            'email' => $validated['email'],
        ], $request);
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        ActivityLogService::log('admin_logout', Auth::guard('admin')->user(), [], $request);
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
