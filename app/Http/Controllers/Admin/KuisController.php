<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Opsi;
use App\Models\Pertanyaan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Admin\KuisController (Hybrid: Web Admin Manajemen Kuis & REST API Admin)
 */
class KuisController extends Controller
{
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

            $query = Kuis::withCount('pertanyaan as jumlah_pertanyaan')
                ->withSum('pertanyaan as total_poin', 'poin');

            if ($request->filled('q')) {
                $query->where('judul', 'like', '%' . $request->q . '%');
            }

            $paginator = $query->latest()->paginate($params['limit'], ['*'], 'page', $params['page']);

            $data = $paginator->getCollection()->map(fn ($k) => [
                'id'                => $k->id,
                'judul'             => $k->judul,
                'deskripsi'         => $k->deskripsi,
                'jumlah_pertanyaan' => (int) $k->jumlah_pertanyaan,
                'total_poin'        => (int) ($k->total_poin ?? 0),
                'dibuat_pada'       => $k->created_at->toIso8601ZuluString(),
            ]);

            return $this->suksesDaftar('Daftar kuis admin berhasil diambil', $data, $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $query = Kuis::withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $kuises = $query->latest()->paginate(10)->withQueryString();

        return view('admin.kuis.index', compact('kuises'));
    }

    public function create(): View
    {
        return view('admin.kuis.create');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'judul'     => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $kuis = Kuis::create([
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
            ]);

            return $this->sukses('Kuis berhasil dibuat', [
                'id'          => $kuis->id,
                'judul'       => $kuis->judul,
                'deskripsi'   => $kuis->deskripsi,
                'dibuat_pada' => $kuis->created_at->toIso8601ZuluString(),
            ], 201);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ], [
            'judul.required' => 'Judul kuis wajib diisi.',
        ]);

        $kuis = Kuis::create($validated);

        return redirect()->route('admin.kuis.show', $kuis->id)->with('success', 'Kuis berhasil dibuat! Silakan tambahkan pertanyaan kuis.');
    }

    public function show(Request $request, int $id): View|JsonResponse
    {
        $kuis = Kuis::with([
            'pertanyaan' => fn ($q) => $q->orderBy('urutan'),
            'pertanyaan.opsi',
        ])
            ->withCount('pertanyaan as jumlah_pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin')
            ->find($id);

        if (!$kuis) {
            return $request->expectsJson()
                ? $this->error('Kuis tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        if ($request->expectsJson()) {
            return $this->sukses('Detail kuis admin berhasil diambil', [
                'id'                => $kuis->id,
                'judul'             => $kuis->judul,
                'deskripsi'         => $kuis->deskripsi,
                'jumlah_pertanyaan' => (int) $kuis->jumlah_pertanyaan,
                'total_poin'        => (int) ($kuis->total_poin ?? 0),
                'pertanyaan'        => $kuis->pertanyaan->map(fn ($p) => [
                    'id'         => $p->id,
                    'urutan'     => $p->urutan,
                    'teks'       => $p->teks,
                    'poin'       => $p->poin,
                    'penjelasan' => $p->penjelasan,
                    'opsi'       => $p->opsi->map(fn ($o) => [
                        'id'    => $o->id,
                        'teks'  => $o->teks,
                        'benar' => (bool) $o->benar,
                    ]),
                ]),
            ]);
        }

        return view('admin.kuis.show', compact('kuis'));
    }

    public function edit(int $id): View
    {
        $kuis = Kuis::findOrFail($id);

        return view('admin.kuis.edit', compact('kuis'));
    }

    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $kuis = Kuis::find($id);
        if (!$kuis) {
            return $request->expectsJson()
                ? $this->error('Kuis tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'judul'     => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $kuis->update(array_filter([
                'judul'     => $request->judul,
                'deskripsi' => $request->deskripsi,
            ], fn ($v) => $v !== null));

            return $this->sukses('Kuis berhasil diperbarui', [
                'id'        => $kuis->id,
                'judul'     => $kuis->judul,
                'deskripsi' => $kuis->deskripsi,
            ]);
        }

        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kuis->update($validated);

        return redirect()->route('admin.kuis.show', $kuis->id)->with('success', 'Kuis berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $kuis = Kuis::find($id);
        if (!$kuis) {
            return $request->expectsJson()
                ? $this->error('Kuis tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        $kuis->delete();

        if ($request->expectsJson()) {
            return $this->suksesKosong('Kuis berhasil dihapus');
        }

        return redirect()->route('admin.kuis.index')->with('success', 'Kuis berhasil dihapus.');
    }

    public function hasil(Request $request, int $id): View|JsonResponse
    {
        $kuis = Kuis::findOrFail($id);

        $query = HasilKuis::with('user')->where('kuis_id', $id);

        if ($request->expectsJson()) {
            $params = $this->paginasiParams($request);
            $paginator = $query->latest()->paginate($params['limit'] ?? 10);
            $data = $paginator->getCollection()->map(fn ($h) => [
                'id'           => $h->id,
                'pengguna'     => $h->user?->nama ?? 'Pengguna',
                'skor'         => (int) $h->skor,
                'persentase'   => (float) $h->persentase,
                'tanggal'      => $h->created_at->toIso8601ZuluString(),
            ]);
            return $this->suksesDaftar('Hasil kuis berhasil diambil', $data, $this->buildMeta($paginator));
        }

        $sort = $request->query('sort', 'terbaru');
        if ($sort === 'skor') {
            $query->orderByDesc('skor');
        } else {
            $query->latest();
        }

        $hasilList = $query->paginate(15)->withQueryString();

        return view('admin.kuis.hasil', compact('kuis', 'hasilList'));
    }

    public function tambahPertanyaanForm(int $id): View
    {
        $kuis = Kuis::findOrFail($id);
        $nextUrutan = $kuis->pertanyaan()->max('urutan') + 1;

        return view('admin.kuis.pertanyaan.create', compact('kuis', 'nextUrutan'));
    }

    public function tambahPertanyaan(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $kuis = Kuis::findOrFail($id);

        $request->validate([
            'urutan'     => 'required|integer|min:1',
            'teks'       => 'required|string',
            'poin'       => 'required|integer|min:1',
            'penjelasan' => 'nullable|string',
            'opsi'       => 'required|array|min:2',
            'opsi.*'     => 'required|string',
            'benar'      => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $pertanyaan = Pertanyaan::create([
                'kuis_id'    => $kuis->id,
                'urutan'     => $request->urutan,
                'teks'       => $request->teks,
                'poin'       => $request->poin,
                'penjelasan' => $request->penjelasan,
            ]);

            $benarIndex = (int) $request->benar;
            foreach ($request->opsi as $index => $opsiTeks) {
                Opsi::create([
                    'pertanyaan_id' => $pertanyaan->id,
                    'teks'          => $opsiTeks,
                    'benar'         => ($index === $benarIndex),
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return $this->sukses('Pertanyaan berhasil ditambahkan', $pertanyaan->load('opsi'), 201);
            }

            return redirect()->route('admin.kuis.show', $kuis->id)->with('success', 'Pertanyaan berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menambahkan pertanyaan: ' . $e->getMessage());
        }
    }

    public function ubahPertanyaanForm(int $id, int $pertanyaan_id): View
    {
        $kuis = Kuis::findOrFail($id);
        $pertanyaan = Pertanyaan::with('opsi')->where('kuis_id', $id)->findOrFail($pertanyaan_id);

        return view('admin.kuis.pertanyaan.edit', compact('kuis', 'pertanyaan'));
    }

    public function ubahPertanyaan(Request $request, int $id, int $pertanyaan_id): RedirectResponse|JsonResponse
    {
        $pertanyaan = Pertanyaan::where('kuis_id', $id)->findOrFail($pertanyaan_id);

        $request->validate([
            'urutan'     => 'required|integer|min:1',
            'teks'       => 'required|string',
            'poin'       => 'required|integer|min:1',
            'penjelasan' => 'nullable|string',
            'opsi'       => 'required|array|min:2',
            'opsi.*'     => 'required|string',
            'benar'      => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $pertanyaan->update([
                'urutan'     => $request->urutan,
                'teks'       => $request->teks,
                'poin'       => $request->poin,
                'penjelasan' => $request->penjelasan,
            ]);

            $pertanyaan->opsi()->delete();

            $benarIndex = (int) $request->benar;
            foreach ($request->opsi as $index => $opsiTeks) {
                Opsi::create([
                    'pertanyaan_id' => $pertanyaan->id,
                    'teks'          => $opsiTeks,
                    'benar'         => ($index === $benarIndex),
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return $this->sukses('Pertanyaan berhasil diperbarui', $pertanyaan->load('opsi'));
            }

            return redirect()->route('admin.kuis.show', $id)->with('success', 'Pertanyaan berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pertanyaan: ' . $e->getMessage());
        }
    }

    public function hapusPertanyaan(Request $request, int $id, int $pertanyaan_id): RedirectResponse|JsonResponse
    {
        $pertanyaan = Pertanyaan::where('kuis_id', $id)->findOrFail($pertanyaan_id);
        $pertanyaan->delete();

        if ($request->expectsJson()) {
            return $this->suksesKosong('Pertanyaan berhasil dihapus');
        }

        return redirect()->route('admin.kuis.show', $id)->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
