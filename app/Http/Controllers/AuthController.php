<?php

namespace App\Http\Controllers;

use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * AuthController (Hybrid: Melayani Autentikasi Web Sesi Blade & Token REST API)
 */
class AuthController extends Controller
{
    /**
     * Menampilkan form login (Web)
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses login pengguna atau admin (Web Redirect atau JSON API)
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $user = User::where('email', strtolower($request->email))->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error('Email atau password salah', 'UNAUTHORIZED', 401);
            }

            [$accessToken, $refreshToken] = $this->terbitkanToken($user);

            return $this->sukses('Login berhasil', [
                'user'          => $this->formatUser($user),
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type'    => 'Bearer',
                'expires_in'    => 900,
            ]);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            Auth::guard('web')->attempt($credentials, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, Admin!');
        }

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

    /**
     * Menampilkan form register (Web)
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses pendaftaran akun baru (Web Redirect atau JSON API)
     */
    public function register(Request $request): RedirectResponse|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'nama'      => 'required|string|max:255',
                'email'     => 'required|email|max:255|unique:users,email',
                'password'  => 'required|string|min:8|confirmed',
                'pekerjaan' => 'nullable|string|max:255',
                'usia'      => 'nullable|integer|min:10|max:100',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            if (User::where('email', strtolower($request->email))->exists()) {
                return $this->error('Email sudah terdaftar', 'CONFLICT', 409);
            }

            $user = User::create([
                'nama'      => $request->nama,
                'email'     => strtolower($request->email),
                'password'  => Hash::make($request->password),
                'pekerjaan' => $request->pekerjaan,
                'usia'      => $request->usia,
                'role'      => 'user',
            ]);

            [$accessToken, $refreshToken] = $this->terbitkanToken($user);

            return $this->sukses('Registrasi berhasil', [
                'user'          => $this->formatUser($user),
                'access_token'  => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type'    => 'Bearer',
                'expires_in'    => 900,
            ], 201);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'usia'      => ['nullable', 'integer', 'min:5', 'max:120'],
        ], [
            'nama.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
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

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat bergabung di SDG7 Learn.');
    }

    /**
     * Memproses keluar sesi / revoke token (Web Redirect atau JSON API)
     */
    public function logout(Request $request): RedirectResponse|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            if ($request->user()) {
                $request->user()->currentAccessToken()?->delete();
            }
            return $this->suksesKosong('Logout berhasil');
        }

        // ── Jalur Antarmuka Web (Blade) ──
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Memperbarui access token dengan refresh token (Khusus REST API)
     */
    public function refresh(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorValidasi($validator->errors()->toArray());
        }

        $record = RefreshToken::where('token_hash', hash('sha256', $request->refresh_token))
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return $this->error('Refresh token tidak valid atau kedaluwarsa', 'UNAUTHORIZED', 401);
        }

        $record->update(['revoked' => true]);
        $user = $record->user;
        [$accessToken, $newRefreshToken] = $this->terbitkanToken($user);

        return $this->sukses('Token berhasil diperbarui', [
            'access_token'  => $accessToken,
            'refresh_token' => $newRefreshToken,
            'token_type'    => 'Bearer',
            'expires_in'    => 900,
        ]);
    }

    // ── Helper API ──

    private function terbitkanToken(User $user): array
    {
        $accessToken = $user->createToken(
            'access_token',
            [$user->role === 'admin' ? 'role:admin' : 'role:user'],
            now()->addMinutes(15)
        )->plainTextToken;

        $rawRefreshToken = Str::random(64);
        RefreshToken::create([
            'user_id'    => $user->id,
            'token_hash' => hash('sha256', $rawRefreshToken),
            'expires_at' => now()->addDays(7),
            'revoked'    => false,
        ]);

        return [$accessToken, $rawRefreshToken];
    }

    private function formatUser(User $user): array
    {
        return [
            'id'        => $user->id,
            'nama'      => $user->nama,
            'email'     => $user->email,
            'pekerjaan' => $user->pekerjaan,
            'usia'      => $user->usia,
            'role'      => $user->role,
        ];
    }
}
