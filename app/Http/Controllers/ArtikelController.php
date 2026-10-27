<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * ArtikelController (Hybrid: Melayani Antarmuka Web Blade & REST API)
 */
class ArtikelController extends Controller
{
    /**
     * Menampilkan katalog artikel (Blade View atau JSON API)
     */
    public function index(Request $request): View|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $params = $this->paginasiParams($request);
            if ($params === null) {
                return $this->errorValidasi([
                    'page'  => ['Nilai page harus >= 1'],
                    'limit' => ['Nilai limit harus antara 1 dan 50'],
                ]);
            }

            $validator = Validator::make($request->query(), [
                'q'    => 'nullable|string|max:100',
                'sort' => 'nullable|string|in:tanggal,-tanggal',
            ]);
            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $query = Artikel::query();
            if ($request->filled('q')) {
                $query->where('judul', 'like', '%' . $request->q . '%');
            }

            $sort = $request->query('sort', '-tanggal');
            $query->orderBy('created_at', str_starts_with($sort, '-') ? 'desc' : 'asc');

            $paginator = $query->paginate($params['limit'], ['*'], 'page', $params['page']);
            $data = $paginator->getCollection()->map(fn ($a) => $this->formatRingkas($a));

            return $this->suksesDaftar('Daftar artikel berhasil diambil', $data, $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
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

    /**
     * Menampilkan detail artikel (Blade View atau JSON API)
     */
    public function show(Request $request, int $id): View|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $artikel = Artikel::find($id);
            if (!$artikel) {
                return $this->error('Artikel tidak ditemukan', 'NOT_FOUND', 404);
            }

            return $this->sukses('Detail artikel berhasil diambil', [
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
        $artikel = Artikel::findOrFail($id);

        $artikelTerkait = Artikel::where('id', '!=', $id)
            ->latest()
            ->take(3)
            ->get();

        return view('artikel.show', compact('artikel', 'artikelTerkait'));
    }

    /**
     * Helper format ringkas untuk API
     */
    private function formatRingkas(Artikel $artikel): array
    {
        $isi = $artikel->isi ?? '';
        $ringkasan = mb_strlen($isi) > 150 ? mb_substr($isi, 0, 150) . '…' : $isi;

        return [
            'id'         => $artikel->id,
            'judul'      => $artikel->judul,
            'ringkasan'  => $ringkasan,
            'sumber'     => $artikel->sumber,
            'gambar_url' => $artikel->gambar_url,
            'tanggal'    => $artikel->created_at->toIso8601ZuluString(),
        ];
    }
}
