<?php

namespace App\Http\Controllers;

use App\Models\HasilKuis;
use App\Models\Postingan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * ProfilController (Hybrid: Melayani Antarmuka Profil Web & REST API)
 */
class ProfilController extends Controller
{
    /**
     * Menampilkan halaman profil (Blade View)
     */
    public function index(Request $request): View|JsonResponse
    {
        return $this->show($request);
    }

    /**
     * Menampilkan detail profil (Blade View atau JSON API)
     */
    public function show(Request $request): View|JsonResponse
    {
        $user = $request->user() ?? Auth::user();

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            return $this->sukses('Profil berhasil diambil', [
                'id'          => $user->id,
                'nama'        => $user->nama,
                'email'       => $user->email,
                'pekerjaan'   => $user->pekerjaan,
                'usia'        => $user->usia,
                'role'        => $user->role,
                'dibuat_pada' => $user->created_at->toIso8601ZuluString(),
            ]);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $aktivitasTerbaru = [
            'kuis'      => HasilKuis::where('user_id', $user->id)->latest()->take(3)->get(),
            'postingan' => Postingan::where('user_id', $user->id)->latest()->take(3)->get(),
        ];

        return view('profil.index', compact('user', 'aktivitasTerbaru'));
    }

    /**
     * Memperbarui data profil pengguna (Web Redirect atau JSON API)
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user() ?? Auth::user();

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'nama'      => 'required|string|max:255',
                'pekerjaan' => 'nullable|string|max:255',
                'usia'      => 'nullable|integer|min:10|max:100',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $user->update([
                'nama'      => $request->nama,
                'pekerjaan' => $request->pekerjaan,
                'usia'      => $request->usia,
            ]);

            return $this->sukses('Profil berhasil diperbarui', [
                'id'        => $user->id,
                'nama'      => $user->nama,
                'email'     => $user->email,
                'pekerjaan' => $user->pekerjaan,
                'usia'      => $user->usia,
                'role'      => $user->role,
            ]);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'nama'      => ['required', 'string', 'max:255'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
            'usia'      => ['nullable', 'integer', 'min:5', 'max:120'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Mengubah kata sandi pengguna (Web Redirect atau JSON API)
     */
    public function ubahPassword(Request $request): RedirectResponse|JsonResponse
    {
        $user = $request->user() ?? Auth::user();

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'password_lama' => 'required|string',
                'password_baru' => 'required|string|min:8|different:password_lama|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            if (!Hash::check($request->password_lama, $user->password)) {
                return $this->error('Password lama salah', 'UNAUTHORIZED', 401);
            }

            $user->update(['password' => Hash::make($request->password_baru)]);

            return $this->suksesKosong('Password berhasil diubah');
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $request->validate([
            'password_sekarang'     => ['required', 'current_password'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password_sekarang.required'         => 'Kata sandi saat ini wajib diisi.',
            'password_sekarang.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required'                  => 'Kata sandi baru wajib diisi.',
            'password.min'                       => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'                 => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Kata sandi berhasil diubah!');
    }
}
