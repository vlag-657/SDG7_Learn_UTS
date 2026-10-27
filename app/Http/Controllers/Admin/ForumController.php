<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balasan;
use App\Models\Postingan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin\ForumController (Hybrid: Web Admin Moderasi Forum & REST API Admin)
 */
class ForumController extends Controller
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

            $query = Postingan::with('penulis')->withCount('balasan as jumlah_balasan');

            if ($request->filled('q')) {
                $query->where('judul', 'like', '%' . $request->q . '%');
            }

            $paginator = $query->latest()->paginate($params['limit'], ['*'], 'page', $params['page']);

            $data = $paginator->getCollection()->map(fn ($p) => [
                'id'             => $p->id,
                'judul'          => $p->judul,
                'cuplikan'       => mb_substr($p->isi, 0, 100) . '…',
                'penulis'        => $p->penulis?->nama ?? 'Pengguna',
                'jumlah_balasan' => (int) $p->jumlah_balasan,
                'tanggal'        => $p->created_at->toIso8601ZuluString(),
            ]);

            return $this->suksesDaftar('Daftar postingan moderasi berhasil diambil', $data, $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $query = Postingan::with('penulis')
            ->withCount('balasan as jumlah_balasan');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $postingan = $query->latest()->paginate(15)->withQueryString();

        return view('admin.forum.index', compact('postingan'));
    }

    public function show(int $id): View
    {
        $postingan = Postingan::with([
            'penulis',
            'balasan' => fn ($q) => $q->with('penulis')->oldest(),
        ])
            ->withCount('balasan as jumlah_balasan')
            ->findOrFail($id);

        return view('admin.forum.show', compact('postingan'));
    }

    public function hapusPostingan(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $postingan = Postingan::find($id);
        if (!$postingan) {
            return $request->expectsJson()
                ? $this->error('Postingan tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        $postingan->balasan()->delete();
        $postingan->delete();

        if ($request->expectsJson()) {
            return $this->suksesKosong('Postingan dan balasannya berhasil dihapus');
        }

        return redirect()->route('admin.forum.index')->with('success', 'Postingan diskusi dan seluruh balasannya berhasil dihapus.');
    }

    public function hapusBalasan(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $balasan = Balasan::find($id);
        if (!$balasan) {
            return $request->expectsJson()
                ? $this->error('Balasan tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        $balasan->delete();

        if ($request->expectsJson()) {
            return $this->suksesKosong('Balasan berhasil dihapus');
        }

        return back()->with('success', 'Balasan berhasil dihapus.');
    }
}
