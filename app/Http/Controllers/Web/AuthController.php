<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cek login untuk entitas Admin pada ERD
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            // Sinkronkan juga ke web session jika ada user admin
            Auth::guard('web')->attempt($credentials, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, Admin!');
        }

        // Cek login untuk entitas User pada ERD
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, Admin!');
            }

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali, ' . Auth::user()->nama . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'password'  => 'required|string|min:8|confirmed',
            'pekerjaan' => 'nullable|string|max:255',
            'usia'      => 'nullable|integer|min:10|max:100',
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'usia.min'           => 'Usia minimal 10 tahun.',
            'usia.max'           => 'Usia maksimal 100 tahun.',
        ]);

        $user = User::create([
            'nama'      => $validated['nama'],
            'email'     => strtolower($validated['email']),
            'password'  => Hash::make($validated['password']),
            'pekerjaan' => $validated['pekerjaan'] ?? null,
            'usia'      => $validated['usia'] ?? null,
            'role'      => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil! Selamat datang di SDG7Learn.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
