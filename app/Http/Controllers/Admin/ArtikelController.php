<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Admin\ArtikelController (Hybrid: Web Admin Panel & REST API Admin)
 */
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

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'judul'      => 'required|string|max:255',
                'isi'        => 'required|string',
                'sumber'     => 'required|string|max:255',
                'gambar_url' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $artikel = Artikel::create([
                'judul'      => $request->judul,
                'isi'        => $request->isi,
                'sumber'     => $request->sumber,
                'gambar_url' => $request->gambar_url,
            ]);

            return $this->sukses('Artikel berhasil dibuat', [
                'id'              => $artikel->id,
                'judul'           => $artikel->judul,
                'isi'             => $artikel->isi,
                'sumber'          => $artikel->sumber,
                'gambar_url'      => $artikel->gambar_url,
                'tanggal'         => $artikel->created_at->toIso8601ZuluString(),
                'diperbarui_pada' => $artikel->updated_at->toIso8601ZuluString(),
            ], 201);
        }

        // ── Jalur Antarmuka Web (Blade) ──
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

    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $artikel = Artikel::find($id);
        if (!$artikel) {
            return $request->expectsJson()
                ? $this->error('Artikel tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'judul'      => 'nullable|string|max:255',
                'isi'        => 'nullable|string',
                'sumber'     => 'nullable|string|max:255',
                'gambar_url' => 'nullable|url',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $artikel->update(array_filter([
                'judul'      => $request->judul,
                'isi'        => $request->isi,
                'sumber'     => $request->sumber,
                'gambar_url' => $request->gambar_url,
            ], fn ($v) => $v !== null));

            return $this->sukses('Artikel berhasil diperbarui', [
                'id'              => $artikel->id,
                'judul'           => $artikel->judul,
                'isi'             => $artikel->isi,
                'sumber'          => $artikel->sumber,
                'gambar_url'      => $artikel->gambar_url,
                'tanggal'         => $artikel->created_at->toIso8601ZuluString(),
                'diperbarui_pada' => $artikel->updated_at->toIso8601ZuluString(),
            ]);
        }

        // ── Jalur Antarmuka Web (Blade) ──
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

        return redirect()->route('admin.artikel.index')->with('success', 'Perubahan artikel berhasil disimpan!');
    }

    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $artikel = Artikel::find($id);
        if (!$artikel) {
            return $request->expectsJson()
                ? $this->error('Artikel tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        $artikel->delete();

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            return $this->suksesKosong('Artikel berhasil dihapus');
        }

        // ── Jalur Antarmuka Web (Blade) ──
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus.');
    }
}
