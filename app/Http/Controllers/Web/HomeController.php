<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kuis;
use App\Models\Postingan;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $artikelTerbaru = Artikel::latest()->take(3)->get();
        $kuisUnggulan = Kuis::withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin')
            ->latest()
            ->take(3)
            ->get();

        $stats = [
            'artikel'   => Artikel::count(),
            'kuis'      => Kuis::count(),
            'postingan' => Postingan::count(),
            'pengguna'  => User::where('role', 'user')->count(),
        ];

        return view('home', compact('artikelTerbaru', 'kuisUnggulan', 'stats'));
    }
}
