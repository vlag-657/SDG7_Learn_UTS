<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HasilKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HasilKuisController extends Controller
{
    public function index(Request $request): View
    {
        $riwayat = HasilKuis::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('hasil-kuis.index', compact('riwayat'));
    }

    public function show(int $id): View
    {
        $hasil = HasilKuis::with(['kuis', 'user'])->findOrFail($id);

        // Otorisasi: user biasa hanya boleh melihat miliknya sendiri, admin boleh melihat semua
        if (Auth::user()->role === 'user' && $hasil->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak melihat hasil kuis ini.');
        }

        return view('hasil-kuis.show', compact('hasil'));
    }
}
