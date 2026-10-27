<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArtikelController extends Controller
{
    public function index(Request $request): View
    {
        $query = Artikel::query();

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $sort = $request->query('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $artikels = $query->paginate(9)->withQueryString();

        return view('artikel.index', compact('artikels'));
    }

    public function show(int $id): View
    {
        $artikel = Artikel::findOrFail($id);

        $artikelTerkait = Artikel::where('id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return view('artikel.show', compact('artikel', 'artikelTerkait'));
    }
}
