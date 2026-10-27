<?php

namespace App\Http\Controllers;

use App\Models\HasilKuis;
use App\Models\Kuis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * KuisController (Hybrid: Melayani Pengerjaan Kuis Web & REST API)
 */
class KuisController extends Controller
{
    /**
     * Menampilkan daftar kuis (Blade View atau JSON API)
     */
    public function index(Request $request): View|JsonResponse
    {
        $userId = $request->user()?->id ?? Auth::id();

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
                'sort' => 'nullable|string|in:dibuat_pada,-dibuat_pada',
            ]);
            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $query = Kuis::withCount('pertanyaan as jumlah_pertanyaan')
                ->withSum('pertanyaan as total_poin', 'poin');

            if ($request->filled('q')) {
                $query->where('judul', 'like', '%' . $request->q . '%');
            }

            $sort = $request->query('sort', '-dibuat_pada');
            $query->orderBy('created_at', str_starts_with($sort, '-') ? 'desc' : 'asc');

            $paginator = $query->paginate($params['limit'], ['*'], 'page', $params['page']);

            $data = $paginator->getCollection()->map(function (Kuis $kuis) use ($userId) {
                $riwayatTerakhir = $userId
                    ? HasilKuis::where('user_id', $userId)->where('kuis_id', $kuis->id)->latest()->first()
                    : null;

                return [
                    'id'                => $kuis->id,
                    'judul'             => $kuis->judul,
                    'deskripsi'         => $kuis->deskripsi,
                    'jumlah_pertanyaan' => (int) $kuis->jumlah_pertanyaan,
                    'total_poin'        => (int) ($kuis->total_poin ?? 0),
                    'status_pengerjaan' => $riwayatTerakhir ? 'SELESAI' : 'BELUM',
                    'skor_terakhir'     => $riwayatTerakhir ? (int) $riwayatTerakhir->skor : null,
                    'dibuat_pada'       => $kuis->created_at->toIso8601ZuluString(),
                ];
            });

            return $this->suksesDaftar('Daftar kuis berhasil diambil', $data, $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $query = Kuis::withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $kuises = $query->latest()->paginate(9)->withQueryString();

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

    /**
     * Menampilkan informasi kuis (Blade View atau JSON API)
     */
    public function show(Request $request, int $id): View|JsonResponse
    {
        $userId = $request->user()?->id ?? Auth::id();

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $kuis = Kuis::with([
                'pertanyaan' => fn ($q) => $q->orderBy('urutan'),
                'pertanyaan.opsi',
            ])
                ->withCount('pertanyaan as jumlah_pertanyaan')
                ->withSum('pertanyaan as total_poin', 'poin')
                ->find($id);

            if (!$kuis) {
                return $this->error('Kuis tidak ditemukan', 'NOT_FOUND', 404);
            }

            return $this->sukses('Detail kuis berhasil diambil', [
                'id'                => $kuis->id,
                'judul'             => $kuis->judul,
                'deskripsi'         => $kuis->deskripsi,
                'jumlah_pertanyaan' => (int) $kuis->jumlah_pertanyaan,
                'total_poin'        => (int) ($kuis->total_poin ?? 0),
                'pertanyaan'        => $kuis->pertanyaan->map(fn ($p) => [
                    'id'     => $p->id,
                    'urutan' => $p->urutan,
                    'teks'   => $p->teks,
                    'poin'   => $p->poin,
                    'opsi'   => $p->opsi->map(fn ($o) => [
                        'id'   => $o->id,
                        'teks' => $o->teks,
                    ]),
                ]),
            ]);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $kuis = Kuis::withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin')
            ->findOrFail($id);

        $riwayatPengerjaan = [];
        if ($userId) {
            $riwayatPengerjaan = HasilKuis::where('kuis_id', $id)
                ->where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('kuis.show', compact('kuis', 'riwayatPengerjaan'));
    }

    /**
     * Menampilkan halaman kerjakan kuis (Web) atau submit API
     */
    public function kerjakan(Request $request, int $id): View|JsonResponse
    {
        // ── Jika dipanggil sebagai POST /kuis/{id}/percobaan pada API ──
        if ($request->isMethod('post') || $request->expectsJson()) {
            $kuis = Kuis::with(['pertanyaan.opsi'])->find($id);
            if (!$kuis) {
                return $this->error('Kuis tidak ditemukan', 'NOT_FOUND', 404);
            }

            $validator = Validator::make($request->all(), [
                'jawaban'                   => 'required|array',
                'jawaban.*.pertanyaan_id'   => 'required|integer',
                'jawaban.*.opsi_dipilih_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $jawabanMap = collect($request->jawaban)->keyBy('pertanyaan_id');
            $skor = 0;
            $jumlahBenar = 0;
            $totalPoin = 0;
            $tinjauan = [];

            foreach ($kuis->pertanyaan->sortBy('urutan') as $pertanyaan) {
                $opsiBenar = $pertanyaan->opsi->firstWhere('benar', true);
                $jawabanUser = $jawabanMap->get($pertanyaan->id);
                $opsiDipilihId = $jawabanUser ? (int) $jawabanUser['opsi_dipilih_id'] : null;
                $benar = ($opsiDipilihId !== null) && ($opsiBenar && $opsiDipilihId === $opsiBenar->id);
                $poinDiperoleh = $benar ? $pertanyaan->poin : 0;

                $skor += $poinDiperoleh;
                $totalPoin += $pertanyaan->poin;
                if ($benar) {
                    $jumlahBenar++;
                }

                $tinjauan[] = [
                    'pertanyaan_id'  => $pertanyaan->id,
                    'benar'          => $benar,
                    'poin_diperoleh' => $poinDiperoleh,
                    'opsi_benar_id'  => $opsiBenar?->id,
                    'penjelasan'     => $pertanyaan->penjelasan,
                ];
            }

            $persentase = $totalPoin > 0 ? round(($skor / $totalPoin) * 100, 2) : 0;
            $hasil = HasilKuis::create([
                'user_id'           => $request->user()->id,
                'kuis_id'           => $kuis->id,
                'kuis_judul'        => $kuis->judul,
                'skor'              => $skor,
                'skor_maks'         => $totalPoin,
                'persentase'        => $persentase,
                'jumlah_benar'      => $jumlahBenar,
                'jumlah_pertanyaan' => $kuis->pertanyaan->count(),
                'tinjauan'          => $tinjauan,
            ]);

            return $this->sukses('Kuis berhasil dikerjakan', [
                'hasil_id'          => $hasil->id,
                'skor'              => $skor,
                'skor_maks'         => $totalPoin,
                'persentase'        => $persentase,
                'jumlah_benar'      => $jumlahBenar,
                'jumlah_pertanyaan' => $kuis->pertanyaan->count(),
                'tinjauan'          => $tinjauan,
            ], 201);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $kuis = Kuis::with([
            'pertanyaan' => fn ($q) => $q->orderBy('urutan'),
            'pertanyaan.opsi',
        ])
            ->withCount('pertanyaan')
            ->withSum('pertanyaan as total_poin', 'poin')
            ->findOrFail($id);

        return view('kuis.kerjakan', compact('kuis'));
    }

    /**
     * Memproses jawaban kuis dari antarmuka Web (Blade Submit)
     */
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
