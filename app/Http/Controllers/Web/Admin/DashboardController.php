<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Postingan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_artikel'   => Artikel::count(),
            'total_kuis'      => Kuis::count(),
            'total_postingan' => Postingan::count(),
            'total_pengguna'  => User::where('role', 'user')->count(),
            'total_percobaan' => HasilKuis::count(),
        ];

        $artikelTerbaru = Artikel::latest()->take(5)->get();
        $postinganTerbaru = Postingan::with('penulis')->withCount('balasan')->latest()->take(5)->get();
        $hasilKuisTerbaru = HasilKuis::with(['user', 'kuis'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'artikelTerbaru', 'postinganTerbaru', 'hasilKuisTerbaru'));
    }
}
