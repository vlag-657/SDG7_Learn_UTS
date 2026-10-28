<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CallCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * Admin\CallCenterController (Hybrid: Web Admin Panel & REST API Admin)
 * Menangani moderasi, respon tiket, dan tindak lanjut pesan Call Center pengguna.
 */
class CallCenterController extends Controller
{
    /**
     * Menampilkan daftar semua pesan pengaduan dan tiket Call Center masuk.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = CallCenter::with(['user', 'admin']);

        if ($request->filled('status') && in_array($request->status, ['menunggu', 'diproses', 'selesai'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas') && in_array($request->prioritas, ['rendah', 'sedang', 'tinggi', 'darurat'])) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('subjek', 'like', '%'.$request->q.'%')
                    ->orWhere('pesan', 'like', '%'.$request->q.'%')
                    ->orWhere('nama', 'like', '%'.$request->q.'%')
                    ->orWhere('email', 'like', '%'.$request->q.'%');
            });
        }

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $params = $this->paginasiParams($request);
            if ($params === null) {
                return $this->errorValidasi([
                    'page' => ['Nilai page harus >= 1'],
                    'limit' => ['Nilai limit harus antara 1 dan 50'],
                ]);
            }

            $paginator = $query->latest()->paginate($params['limit'], ['*'], 'page', $params['page']);

            return $this->suksesDaftar('Daftar seluruh tiket Call Center berhasil diambil', $paginator->items(), $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $callCenters = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => CallCenter::count(),
            'menunggu' => CallCenter::where('status', 'menunggu')->count(),
            'diproses' => CallCenter::where('status', 'diproses')->count(),
            'selesai' => CallCenter::where('status', 'selesai')->count(),
            'darurat' => CallCenter::where('prioritas', 'darurat')->where('status', '!=', 'selesai')->count(),
        ];

        return view('admin.call-center.index', compact('callCenters', 'stats'));
    }

    /**
     * Menampilkan detail pesan tiket dan antarmuka penanganan untuk admin.
     */
    public function show(int $id): View
    {
        $callCenter = CallCenter::with(['user', 'admin'])->findOrFail($id);

        return view('admin.call-center.show', compact('callCenter'));
    }

    /**
     * Menyimpan respon / balasan admin dan memperbarui status tiket pengaduan.
     */
    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $callCenter = CallCenter::findOrFail($id);

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'status' => 'required|string|in:menunggu,diproses,selesai',
                'kecepatan_penanganan' => 'nullable|string|in:sangat_cepat,sedang,biasa',
                'tanggapan' => 'nullable|string|max:4000',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $adminId = Auth::guard('admin')->id() ?? (Auth::user()?->role === 'admin' ? Auth::id() : null);

            $callCenter->update([
                'status' => $request->status,
                'kecepatan_penanganan' => $request->kecepatan_penanganan,
                'tanggapan' => $request->tanggapan,
                'admin_id' => $adminId,
                'ditanggapi_pada' => now(),
            ]);

            return $this->sukses('Tanggapan tiket berhasil diperbarui', $callCenter);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:menunggu,diproses,selesai'],
            'kecepatan_penanganan' => ['nullable', 'string', 'in:sangat_cepat,sedang,biasa'],
            'tanggapan' => ['nullable', 'string', 'max:4000'],
        ], [
            'status.required' => 'Status tiket wajib ditentukan.',
            'status.in' => 'Status tiket tidak valid.',
            'kecepatan_penanganan.in' => 'Pilihan kecepatan penanganan tidak valid (pilih sangat cepat, sedang, atau biasa).',
            'tanggapan.max' => 'Tanggapan maksimal 4000 karakter.',
        ]);

        $adminId = Auth::guard('admin')->id() ?? (Auth::user()?->role === 'admin' ? Auth::id() : null);

        $callCenter->update([
            'status' => $validated['status'],
            'kecepatan_penanganan' => $validated['kecepatan_penanganan'] ?? null,
            'tanggapan' => $validated['tanggapan'],
            'admin_id' => $adminId,
            'ditanggapi_pada' => now(),
        ]);

        return redirect()->route('admin.call-center.show', $callCenter->id)
            ->with('success', 'Status tiket dan tanggapan admin berhasil disimpan!');
    }

    /**
     * Menghapus tiket Call Center dari sistem secara permanen.
     */
    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $callCenter = CallCenter::findOrFail($id);
        $callCenter->delete();

        if ($request->expectsJson()) {
            return $this->suksesKosong('Tiket Call Center berhasil dihapus');
        }

        return redirect()->route('admin.call-center.index')
            ->with('success', 'Tiket pesan Call Center berhasil dihapus.');
    }
}
