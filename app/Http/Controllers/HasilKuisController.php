<?php

namespace App\Http\Controllers;

use App\Models\HasilKuis;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * HasilKuisController (Hybrid: Melayani Riwayat & Hasil Kuis Web & REST API)
 */
class HasilKuisController extends Controller
{
    /**
     * Menampilkan riwayat hasil kuis pengguna (Blade View atau JSON API)
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
                'sort' => 'nullable|string|in:dikerjakan_pada,-dikerjakan_pada',
            ]);
            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $sort = $request->query('sort', '-dikerjakan_pada');

            $paginator = HasilKuis::where('user_id', $userId)
                ->orderBy('created_at', str_starts_with($sort, '-') ? 'desc' : 'asc')
                ->paginate($params['limit'], ['*'], 'page', $params['page']);

            $data = $paginator->getCollection()->map(fn ($h) => $this->formatRingkas($h));

            return $this->suksesDaftar('Riwayat hasil kuis berhasil diambil', $data, $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $riwayat = HasilKuis::where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('hasil-kuis.index', compact('riwayat'));
    }

    /**
     * Menampilkan detail tinjauan hasil kuis (Blade View atau JSON API)
     */
    public function show(Request $request, int $id): View|JsonResponse
    {
        $user = $request->user() ?? Auth::user();

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $hasil = HasilKuis::find($id);

            if (!$hasil) {
                return $this->error('Hasil kuis tidak ditemukan', 'NOT_FOUND', 404);
            }

            if ($user && $user->role !== 'admin' && $hasil->user_id !== $user->id) {
                return $this->error('Anda tidak memiliki akses ke hasil kuis ini', 'FORBIDDEN', 403);
            }

            return $this->sukses('Detail hasil kuis berhasil diambil', $this->formatDetail($hasil));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $hasil = HasilKuis::with(['kuis', 'user'])->findOrFail($id);

        if ($user && $user->role === 'user' && $hasil->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat hasil kuis ini.');
        }

        return view('hasil-kuis.show', compact('hasil'));
    }

    // ── Helper Formatter API ──

    private function formatRingkas(HasilKuis $h): array
    {
        return [
            'id'                => $h->id,
            'kuis_id'           => $h->kuis_id,
            'kuis_judul'        => $h->kuis_judul,
            'skor'              => (int) $h->skor,
            'skor_maks'         => (int) $h->skor_maks,
            'persentase'        => (float) $h->persentase,
            'jumlah_benar'      => (int) $h->jumlah_benar,
            'jumlah_pertanyaan' => (int) $h->jumlah_pertanyaan,
            'dikerjakan_pada'   => $h->created_at->toIso8601ZuluString(),
        ];
    }

    private function formatDetail(HasilKuis $h): array
    {
        return [
            'id'                => $h->id,
            'kuis_id'           => $h->kuis_id,
            'kuis_judul'        => $h->kuis_judul,
            'skor'              => (int) $h->skor,
            'skor_maks'         => (int) $h->skor_maks,
            'persentase'        => (float) $h->persentase,
            'jumlah_benar'      => (int) $h->jumlah_benar,
            'jumlah_pertanyaan' => (int) $h->jumlah_pertanyaan,
            'dikerjakan_pada'   => $h->created_at->toIso8601ZuluString(),
            'tinjauan'          => $h->tinjauan,
        ];
    }
}
