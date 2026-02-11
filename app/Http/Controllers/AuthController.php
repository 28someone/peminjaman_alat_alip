<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password tidak valid.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        ActivityLogger::log('login', 'Pengguna berhasil login.', $request);

        return redirect()->route('dashboard')->with('success', 'Login berhasil.');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'regex:/^[0-9]+$/', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => 'peminjam',
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        ActivityLogger::log('register', 'Pengguna baru mendaftar akun.', $request);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang!')
            ->with('register_popup', [
                'title' => 'Pendaftaran Berhasil',
                'message' => 'Akun ' . $user->name . ' sudah aktif. Silakan mulai ajukan peminjaman dari katalog alat.',
            ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        ActivityLogger::log('logout', 'Pengguna logout.', $request);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}

