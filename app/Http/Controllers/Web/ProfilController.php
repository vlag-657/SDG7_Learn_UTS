<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfilController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $user->loadCount(['postingan', 'balasan', 'hasilKuis']);

        $aktivitasTerbaru = $user->hasilKuis()->latest()->take(5)->get();

        return view('profil.index', compact('user', 'aktivitasTerbaru'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'usia'      => 'nullable|integer|min:10|max:100',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'usia.min'      => 'Usia minimal 10 tahun.',
            'usia.max'      => 'Usia maksimal 100 tahun.',
        ]);

        $user->update([
            'nama'      => $validated['nama'],
            'pekerjaan' => $validated['pekerjaan'] ?? null,
            'usia'      => $validated['usia'] ?? null,
        ]);

        return back()->with('success', 'Profil Anda berhasil diperbarui.');
    }

    public function ubahPassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'password_lama'              => 'required|string',
            'password_baru'              => 'required|string|min:8|confirmed',
            'password_baru_confirmation' => 'required|string',
        ], [
            'password_lama.required'     => 'Password lama wajib diisi.',
            'password_baru.required'     => 'Password baru wajib diisi.',
            'password_baru.min'          => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed'    => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama Anda tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
