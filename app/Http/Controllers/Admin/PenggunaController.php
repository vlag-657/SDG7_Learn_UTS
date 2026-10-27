<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilKuis;
use App\Models\Postingan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin\PenggunaController (Hybrid: Web Admin Pengguna & REST API Admin)
 */
class PenggunaController extends Controller
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

            $query = User::where('role', 'user');

            if ($request->filled('q')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->q . '%')
                        ->orWhere('email', 'like', '%' . $request->q . '%');
                });
            }

            $paginator = $query->latest()->paginate($params['limit'], ['*'], 'page', $params['page']);

            $data = $paginator->getCollection()->map(fn ($u) => [
                'id'          => $u->id,
                'nama'        => $u->nama,
                'email'       => $u->email,
                'pekerjaan'   => $u->pekerjaan,
                'usia'        => $u->usia,
                'role'        => $u->role,
                'dibuat_pada' => $u->created_at->toIso8601ZuluString(),
            ]);

            return $this->suksesDaftar('Daftar pengguna berhasil diambil', $data, $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $query = User::query();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        $pengguna = $query->latest()->paginate(15)->withQueryString();

        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function show(Request $request, int $id): View|JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return $request->expectsJson()
                ? $this->error('Pengguna tidak ditemukan', 'NOT_FOUND', 404)
                : abort(404);
        }

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            return $this->sukses('Detail pengguna berhasil diambil', [
                'id'          => $user->id,
                'nama'        => $user->nama,
                'email'       => $user->email,
                'pekerjaan'   => $user->pekerjaan,
                'usia'        => $user->usia,
                'role'        => $user->role,
                'dibuat_pada' => $user->created_at->toIso8601ZuluString(),
            ]);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $hasilTerakhir = HasilKuis::where('user_id', $id)->latest()->take(5)->get();
        $postinganTerakhir = Postingan::where('user_id', $id)->latest()->take(5)->get();

        return view('admin.pengguna.show', compact('user', 'hasilTerakhir', 'postinganTerakhir'));
    }
}
