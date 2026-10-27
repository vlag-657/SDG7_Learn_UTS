<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HasilKuis;
use App\Models\Kuis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KuisController extends Controller
{
    public function index(Request $request): View
    {
        $userId = Auth::id();

        $query = Kuis::withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $kuises = $query->latest()->paginate(9)->withQueryString();

        // Ambil skor terbaik user per kuis
        $skorTerbaikUser = [];
        if ($userId) {
            $skorTerbaikUser = HasilKuis::where('user_id', $userId)
                ->selectRaw('kuis_id, max(skor) as max_skor')
                ->groupBy('kuis_id')
                ->pluck('max_skor', 'kuis_id')
                ->toArray();
        }

        return view('kuis.index', compact('kuises', 'skorTerbaikUser'));
    }

    public function show(int $id): View
    {
        $kuis = Kuis::withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin')
            ->findOrFail($id);

        $riwayatPengerjaan = [];
        if (Auth::check()) {
            $riwayatPengerjaan = HasilKuis::where('kuis_id', $id)
                ->where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get();
        }

        return view('kuis.show', compact('kuis', 'riwayatPengerjaan'));
    }

    public function kerjakan(int $id): View
    {
        $kuis = Kuis::with([
            'pertanyaan' => fn ($q) => $q->orderBy('urutan'),
            'pertanyaan.opsi',
        ])
            ->withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin')
            ->findOrFail($id);

        return view('kuis.kerjakan', compact('kuis'));
    }

    public function simpanJawaban(Request $request, int $id): RedirectResponse
    {
        $kuis = Kuis::with(['pertanyaan.opsi'])->findOrFail($id);

        $request->validate([
            'jawaban' => 'required|array',
        ], [
            'jawaban.required' => 'Silakan jawab pertanyaan kuis terlebih dahulu.',
        ]);

        $jawabanInput = $request->input('jawaban', []);

        DB::beginTransaction();
        try {
            $skor = 0;
            $jumlahBenar = 0;
            $totalPoin = 0;
            $tinjauan = [];

            foreach ($kuis->pertanyaan->sortBy('urutan') as $pertanyaan) {
                $opsiBenar = $pertanyaan->opsi->firstWhere('benar', true);
                $opsiDipilihId = isset($jawabanInput[$pertanyaan->id]) ? (int) $jawabanInput[$pertanyaan->id] : null;
                $opsiDipilih = $opsiDipilihId ? $pertanyaan->opsi->firstWhere('id', $opsiDipilihId) : null;
                $benar = ($opsiDipilihId !== null) && ($opsiBenar && $opsiDipilihId === $opsiBenar->id);
                $poinDiperoleh = $benar ? $pertanyaan->poin : 0;

                $skor += $poinDiperoleh;
                $totalPoin += $pertanyaan->poin;
                if ($benar) {
                    $jumlahBenar++;
                }

                $tinjauan[] = [
                    'pertanyaan_id'   => $pertanyaan->id,
                    'urutan'          => $pertanyaan->urutan,
                    'teks'            => $pertanyaan->teks,
                    'poin'            => $pertanyaan->poin,
                    'opsi'            => $pertanyaan->opsi->map(fn ($o) => ['id' => $o->id, 'teks' => $o->teks])->values()->toArray(),
                    'opsi_dipilih_id' => $opsiDipilihId,
                    'opsi_dipilih'    => $opsiDipilih?->teks,
                    'opsi_benar_id'   => $opsiBenar?->id,
                    'opsi_benar'      => $opsiBenar?->teks,
                    'benar'           => $benar,
                    'poin_diperoleh'  => $poinDiperoleh,
                    'penjelasan'      => $pertanyaan->penjelasan,
                ];
            }

            $persentase = $totalPoin > 0 ? round(($skor / $totalPoin) * 100, 2) : 0;

            $hasil = HasilKuis::create([
                'user_id'           => Auth::id(),
                'kuis_id'           => $kuis->id,
                'kuis_judul'        => $kuis->judul,
                'skor'              => $skor,
                'skor_maks'         => $totalPoin,
                'persentase'        => $persentase,
                'jumlah_benar'      => $jumlahBenar,
                'jumlah_pertanyaan' => $kuis->pertanyaan->count(),
                'tinjauan'          => $tinjauan,
            ]);

            DB::commit();

            return redirect()->route('hasil-kuis.show', $hasil->id)
                ->with('success', 'Kuis berhasil dikerjakan! Berikut adalah rekap hasil dan tinjauan pengerjaan Anda.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses jawaban kuis: ' . $e->getMessage());
        }
    }
}
