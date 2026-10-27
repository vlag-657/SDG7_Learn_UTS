<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\RedirectResponse;
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

        $artikels = $query->latest()->paginate(10)->withQueryString();

        return view('admin.artikel.index', compact('artikels'));
    }

    public function create(): View
    {
        return view('admin.artikel.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'      => 'required|string|max:255',
            'isi'        => 'required|string',
            'sumber'     => 'required|string|max:255',
            'gambar_url' => 'nullable|url',
        ], [
            'judul.required'  => 'Judul artikel wajib diisi.',
            'isi.required'    => 'Isi konten artikel wajib diisi.',
            'sumber.required' => 'Sumber artikel wajib diisi.',
            'gambar_url.url'  => 'Format URL gambar tidak valid.',
        ]);

        Artikel::create($validated);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel edukasi berhasil ditambahkan!');
    }

    public function edit(int $id): View
    {
        $artikel = Artikel::findOrFail($id);

        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);

        $validated = $request->validate([
            'judul'      => 'required|string|max:255',
            'isi'        => 'required|string',
            'sumber'     => 'required|string|max:255',
            'gambar_url' => 'nullable|url',
        ], [
            'judul.required'  => 'Judul artikel wajib diisi.',
            'isi.required'    => 'Isi konten artikel wajib diisi.',
            'sumber.required' => 'Sumber artikel wajib diisi.',
            'gambar_url.url'  => 'Format URL gambar tidak valid.',
        ]);

        $artikel->update($validated);

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->delete();

        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
