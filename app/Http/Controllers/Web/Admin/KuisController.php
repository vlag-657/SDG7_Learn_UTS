<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKuis;
use App\Models\Kuis;
use App\Models\Opsi;
use App\Models\Pertanyaan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KuisController extends Controller
{
    public function index(Request $request): View
    {
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ], [
            'judul.required' => 'Judul kuis wajib diisi.',
        ]);

        $kuis = Kuis::create($validated);

        return redirect()->route('admin.kuis.show', $kuis->id)->with('success', 'Kuis berhasil dibuat! Silakan tambahkan pertanyaan kuis.');
    }

    public function show(int $id): View
    {
        $kuis = Kuis::with([
            'pertanyaan' => fn ($q) => $q->orderBy('urutan'),
            'pertanyaan.opsi',
        ])
            ->withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin')
            ->findOrFail($id);

        return view('admin.kuis.show', compact('kuis'));
    }

    public function edit(int $id): View
    {
        $kuis = Kuis::findOrFail($id);

        return view('admin.kuis.edit', compact('kuis'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $kuis = Kuis::findOrFail($id);

        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kuis->update($validated);

        return redirect()->route('admin.kuis.show', $kuis->id)->with('success', 'Kuis berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $kuis = Kuis::findOrFail($id);
        $kuis->delete();

        return redirect()->route('admin.kuis.index')->with('success', 'Kuis berhasil dihapus.');
    }

    public function hasil(Request $request, int $id): View
    {
        $kuis = Kuis::findOrFail($id);

        $query = HasilKuis::with('user')->where('kuis_id', $id);

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

    public function tambahPertanyaan(Request $request, int $id): RedirectResponse
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
        ], [
            'teks.required'  => 'Teks pertanyaan wajib diisi.',
            'poin.required'  => 'Poin soal wajib diisi.',
            'benar.required' => 'Pilih salah satu opsi sebagai jawaban yang benar.',
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

    public function ubahPertanyaan(Request $request, int $id, int $pertanyaan_id): RedirectResponse
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

            return redirect()->route('admin.kuis.show', $id)->with('success', 'Pertanyaan berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pertanyaan: ' . $e->getMessage());
        }
    }

    public function hapusPertanyaan(int $id, int $pertanyaan_id): RedirectResponse
    {
        $pertanyaan = Pertanyaan::where('kuis_id', $id)->findOrFail($pertanyaan_id);
        $pertanyaan->delete();

        return redirect()->route('admin.kuis.show', $id)->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
