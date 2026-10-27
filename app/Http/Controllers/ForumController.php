<?php

namespace App\Http\Controllers;

use App\Models\Balasan;
use App\Models\Postingan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * ForumController (Hybrid: Melayani Antarmuka Web Blade & REST API)
 */
class ForumController extends Controller
{
    /**
     * Menampilkan daftar postingan forum (Blade View atau JSON API)
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

            $sort  = $request->query('sort', '-tanggal');
            $query = Postingan::with('penulis')->withCount('balasan as jumlah_balasan');

            if ($request->filled('q')) {
                $query->where('judul', 'like', '%' . $request->q . '%');
            }

            $query->orderBy('created_at', str_starts_with($sort, '-') ? 'desc' : 'asc');
            $paginator = $query->paginate($params['limit'], ['*'], 'page', $params['page']);
            $data = $paginator->getCollection()->map(fn ($p) => $this->formatPostinganRingkas($p));

            return $this->suksesDaftar('Daftar postingan berhasil diambil', $data, $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $query = Postingan::with('penulis')
            ->withCount('balasan as jumlah_balasan');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $sort = $request->query('sort', 'terbaru');
        if ($sort === 'terlama') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $postingan = $query->paginate(10)->withQueryString();

        return view('forum.index', compact('postingan'));
    }

    /**
     * Menampilkan form buat thread baru (Khusus Web)
     */
    public function create(): View
    {
        return view('forum.create');
    }

    /**
     * Menyimpan thread diskusi baru (Web Redirect atau JSON API)
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'judul' => 'required|string|max:255',
                'isi'   => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $postingan = Postingan::create([
                'user_id' => $request->user()->id,
                'judul'   => $request->judul,
                'isi'     => $request->isi,
            ]);

            $postingan->load('penulis');
            $postingan->loadCount('balasan as jumlah_balasan');

            return $this->sukses('Postingan berhasil dibuat', $this->formatPostinganDetail($postingan), 201);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi'   => 'required|string|min:10',
        ], [
            'judul.required' => 'Judul diskusi wajib diisi.',
            'judul.max'      => 'Judul maksimal 255 karakter.',
            'isi.required'   => 'Isi diskusi wajib diisi.',
            'isi.min'        => 'Isi diskusi minimal 10 karakter.',
        ]);

        $post = Postingan::create([
            'user_id' => Auth::id(),
            'judul'   => $validated['judul'],
            'isi'     => $validated['isi'],
        ]);

        return redirect()->route('forum.show', $post->id)->with('success', 'Diskusi berhasil dipublikasikan!');
    }

    /**
     * Menampilkan detail thread dan balasan (Blade View atau JSON API)
     */
    public function show(Request $request, int $id): View|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $postingan = Postingan::with('penulis')
                ->withCount('balasan as jumlah_balasan')
                ->find($id);

            if (!$postingan) {
                return $this->error('Postingan tidak ditemukan', 'NOT_FOUND', 404);
            }

            return $this->sukses('Detail postingan berhasil diambil', $this->formatPostinganDetail($postingan));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $postingan = Postingan::with([
            'penulis',
            'balasan' => fn ($q) => $q->with('penulis')->oldest(),
        ])
            ->withCount('balasan as jumlah_balasan')
            ->findOrFail($id);

        return view('forum.show', compact('postingan'));
    }

    /**
     * Mengirim balasan ke dalam forum (Web Redirect atau JSON API)
     */
    public function balas(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $postingan = Postingan::find($id);
        if (!$postingan) {
            return $request->expectsJson()
                ? $this->error('Postingan tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'isi' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $balasan = Balasan::create([
                'forum_id'     => $postingan->id,
                'postingan_id' => $postingan->id,
                'user_id'      => $request->user()->id,
                'isi'          => $request->isi,
            ]);

            $balasan->load('penulis');

            return $this->sukses('Balasan berhasil ditambahkan', $this->formatBalasan($balasan), 201);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'isi' => 'required|string|min:3',
        ], [
            'isi.required' => 'Balasan tidak boleh kosong.',
            'isi.min'      => 'Balasan minimal 3 karakter.',
        ]);

        Balasan::create([
            'forum_id'     => $postingan->id,
            'postingan_id' => $postingan->id,
            'user_id'      => Auth::id(),
            'isi'          => $validated['isi'],
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    /**
     * Endpoint API daftar balasan per postingan
     */
    public function balasan(Request $request, int $id): JsonResponse
    {
        $postingan = Postingan::find($id);
        if (!$postingan) {
            return $this->error('Postingan tidak ditemukan', 'NOT_FOUND', 404);
        }

        $params = $this->paginasiParams($request);
        if ($params === null) {
            return $this->errorValidasi([
                'page'  => ['Nilai page harus >= 1'],
                'limit' => ['Nilai limit harus antara 1 dan 50'],
            ]);
        }

        $paginator = Balasan::with('penulis')
            ->where('postingan_id', $id)
            ->orWhere('forum_id', $id)
            ->orderBy('created_at', 'asc')
            ->paginate($params['limit'], ['*'], 'page', $params['page']);

        $data = $paginator->getCollection()->map(fn ($b) => $this->formatBalasan($b));

        return $this->suksesDaftar('Daftar balasan berhasil diambil', $data, $this->buildMeta($paginator));
    }

    // ── Helper Formatter API ──

    private function formatPostinganRingkas(Postingan $p): array
    {
        $isi = $p->isi ?? '';
        $cuplikan = mb_strlen($isi) > 120 ? mb_substr($isi, 0, 120) . '…' : $isi;

        return [
            'id'             => $p->id,
            'judul'          => $p->judul,
            'cuplikan'       => $cuplikan,
            'jumlah_balasan' => (int) $p->jumlah_balasan,
            'penulis'        => [
                'id'        => $p->penulis->id ?? null,
                'nama'      => $p->penulis->nama ?? 'Pengguna',
                'pekerjaan' => $p->penulis->pekerjaan ?? null,
            ],
            'tanggal'        => $p->created_at->toIso8601ZuluString(),
        ];
    }

    private function formatPostinganDetail(Postingan $p): array
    {
        return [
            'id'             => $p->id,
            'judul'          => $p->judul,
            'isi'            => $p->isi,
            'jumlah_balasan' => (int) $p->jumlah_balasan,
            'penulis'        => [
                'id'        => $p->penulis->id ?? null,
                'nama'      => $p->penulis->nama ?? 'Pengguna',
                'pekerjaan' => $p->penulis->pekerjaan ?? null,
            ],
            'tanggal'        => $p->created_at->toIso8601ZuluString(),
        ];
    }

    private function formatBalasan(Balasan $b): array
    {
        return [
            'id'      => $b->id,
            'isi'     => $b->isi,
            'penulis' => [
                'id'        => $b->penulis->id ?? null,
                'nama'      => $b->penulis->nama ?? 'Pengguna',
                'pekerjaan' => $b->penulis->pekerjaan ?? null,
            ],
            'tanggal' => $b->created_at->toIso8601ZuluString(),
        ];
    }
}
