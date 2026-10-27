<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $pengguna = $query->withCount(['postingan', 'balasan', 'hasilKuis'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function show(int $id): View
    {
        $pengguna = User::withCount([
            'postingan',
            'balasan',
            'hasilKuis',
        ])->findOrFail($id);

        $ringkasan = [
            'total_kuis'      => $pengguna->hasil_kuis_count,
            'rata_rata_skor'  => round((float) $pengguna->hasilKuis()->avg('persentase'), 1),
            'total_postingan' => $pengguna->postingan_count,
            'total_balasan'   => Balasan::where('user_id', $pengguna->id)->count(),
        ];

        $hasilTerakhir = $pengguna->hasilKuis()->latest()->take(5)->get();
        $postinganTerakhir = $pengguna->postingan()->latest()->take(5)->get();

        return view('admin.pengguna.show', compact('pengguna', 'ringkasan', 'hasilTerakhir', 'postinganTerakhir'));
    }
}
