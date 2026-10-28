<?php

namespace App\Http\Controllers;

use App\Models\CallCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

/**
 * CallCenterController (Hybrid: Web Blade & API)
 * Menangani layanan pesan & tiket bantuan dari pengguna ke administrator.
 */
class CallCenterController extends Controller
{
    /**
     * Menampilkan riwayat tiket Call Center milik pengguna yang sedang login.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $params = $this->paginasiParams($request);
            if ($params === null) {
                return $this->errorValidasi([
                    'page' => ['Nilai page harus >= 1'],
                    'limit' => ['Nilai limit harus antara 1 dan 50'],
                ]);
            }

            $query = CallCenter::with('admin')->where('user_id', $user->id);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('q')) {
                $query->where(function ($q) use ($request) {
                    $q->where('subjek', 'like', '%'.$request->q.'%')
                        ->orWhere('pesan', 'like', '%'.$request->q.'%');
                });
            }

            $paginator = $query->latest()->paginate($params['limit'], ['*'], 'page', $params['page']);

            return $this->suksesDaftar('Daftar pesan Call Center Anda berhasil diambil', $paginator->items(), $this->buildMeta($paginator));
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $query = CallCenter::with('admin')->where('user_id', $user->id);

        if ($request->filled('status') && in_array($request->status, ['menunggu', 'diproses', 'selesai'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('subjek', 'like', '%'.$request->q.'%')
                    ->orWhere('pesan', 'like', '%'.$request->q.'%');
            });
        }

        $pesanList = $query->latest()->paginate(8)->withQueryString();

        // Statistik ringkas tiket milik pengguna
        $stats = [
            'total' => CallCenter::where('user_id', $user->id)->count(),
            'menunggu' => CallCenter::where('user_id', $user->id)->where('status', 'menunggu')->count(),
            'diproses' => CallCenter::where('user_id', $user->id)->where('status', 'diproses')->count(),
            'selesai' => CallCenter::where('user_id', $user->id)->where('status', 'selesai')->count(),
        ];

        return view('call-center.index', compact('pesanList', 'stats'));
    }

    /**
     * Menampilkan form pembuatan tiket Call Center baru.
     */
    public function create(): View
    {
        return view('call-center.create');
    }

    /**
     * Menyimpan pesan Call Center baru dari pengguna ke database.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'kategori' => 'required|string|in:pertanyaan,kendala_kuis,masalah_teknis,saran,lainnya',
                'prioritas' => 'required|string|in:rendah,sedang,tinggi,darurat',
                'nomor_telepon' => 'nullable|string|max:20',
                'subjek' => 'required|string|min:5|max:150',
                'pesan' => 'required|string|min:10|max:3000',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $callCenter = CallCenter::create([
                'user_id' => $request->user()->id,
                'nama' => $request->user()->nama,
                'email' => $request->user()->email,
                'nomor_telepon' => $request->nomor_telepon,
                'kategori' => $request->kategori,
                'prioritas' => $request->prioritas,
                'subjek' => $request->subjek,
                'pesan' => $request->pesan,
                'status' => 'menunggu',
            ]);

            return $this->sukses('Pesan Call Center berhasil dikirim ke Admin', $callCenter, 201);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'in:pertanyaan,kendala_kuis,masalah_teknis,saran,lainnya'],
            'prioritas' => ['required', 'string', 'in:rendah,sedang,tinggi,darurat'],
            'nomor_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'subjek' => ['required', 'string', 'min:5', 'max:150'],
            'pesan' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'kategori.required' => 'Kategori pesan pengaduan wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
            'prioritas.required' => 'Tingkat prioritas pesan wajib dipilih.',
            'prioritas.in' => 'Tingkat prioritas tidak valid.',
            'nomor_telepon.regex' => 'Format nomor telepon/WhatsApp hanya boleh berisi angka dan simbol (+, -, ()).',
            'nomor_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
            'subjek.required' => 'Subjek atau ringkasan masalah wajib diisi.',
            'subjek.min' => 'Subjek pesan minimal 5 karakter.',
            'subjek.max' => 'Subjek pesan maksimal 150 karakter.',
            'pesan.required' => 'Rincian pesan atau kendala wajib diisi.',
            'pesan.min' => 'Pesan pengaduan minimal 10 karakter.',
            'pesan.max' => 'Pesan pengaduan maksimal 3000 karakter.',
        ]);

        $callCenter = CallCenter::create([
            'user_id' => $request->user()->id,
            'nama' => $request->user()->nama,
            'email' => $request->user()->email,
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'kategori' => $validated['kategori'],
            'prioritas' => $validated['prioritas'],
            'subjek' => $validated['subjek'],
            'pesan' => $validated['pesan'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('call-center.show', $callCenter->id)
            ->with('success', 'Pesan Anda berhasil dikirim ke Admin Call Center. Kami akan segera merespon tiket Anda.');
    }

    /**
     * Menampilkan detail satu tiket Call Center dan tanggapan admin.
     */
    public function show(Request $request, int $id): View|JsonResponse
    {
        $callCenter = CallCenter::with(['user', 'admin'])->findOrFail($id);

        // Otorisasi: Pengguna hanya boleh melihat tiket miliknya sendiri (kecuali admin)
        if ($callCenter->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return $this->error('Anda tidak berhak melihat tiket ini', 'FORBIDDEN', 403);
            }
            abort(403, 'Anda tidak berhak mengakses tiket Call Center ini.');
        }

        if ($request->expectsJson()) {
            return $this->sukses('Detail tiket Call Center', $callCenter);
        }

        return view('call-center.show', compact('callCenter'));
    }

    /**
     * Menampilkan form edit tiket Call Center (hanya jika belum diproses).
     */
    public function edit(Request $request, int $id): View|RedirectResponse
    {
        $callCenter = CallCenter::findOrFail($id);

        // Otorisasi
        if ($callCenter->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            abort(403, 'Anda tidak berhak menyunting tiket ini.');
        }

        // Tiket yang sedang diproses atau sudah selesai tidak boleh diedit lagi
        if ($callCenter->status !== 'menunggu') {
            return redirect()->route('call-center.show', $callCenter->id)
                ->with('error', 'Pesan Call Center yang sedang diproses atau sudah selesai tidak dapat disunting kembali.');
        }

        return view('call-center.edit', compact('callCenter'));
    }

    /**
     * Memperbarui pesan Call Center pengguna yang masih dalam status 'menunggu'.
     */
    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $callCenter = CallCenter::findOrFail($id);

        // Otorisasi
        if ($callCenter->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return $this->error('Anda tidak berhak menyunting tiket ini', 'FORBIDDEN', 403);
            }
            abort(403);
        }

        if ($callCenter->status !== 'menunggu') {
            if ($request->expectsJson()) {
                return $this->error('Tiket yang sedang diproses atau sudah selesai tidak dapat diubah', 'INVALID_STATE', 422);
            }

            return redirect()->route('call-center.show', $callCenter->id)
                ->with('error', 'Pesan tidak dapat disunting karena sudah masuk tahap proses atau selesai.');
        }

        // ── Jalur REST API (JSON) ──
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'kategori' => 'required|string|in:pertanyaan,kendala_kuis,masalah_teknis,saran,lainnya',
                'prioritas' => 'required|string|in:rendah,sedang,tinggi,darurat',
                'nomor_telepon' => 'nullable|string|max:20',
                'subjek' => 'required|string|min:5|max:150',
                'pesan' => 'required|string|min:10|max:3000',
            ]);

            if ($validator->fails()) {
                return $this->errorValidasi($validator->errors()->toArray());
            }

            $callCenter->update($validator->validated());

            return $this->sukses('Pesan Call Center berhasil diperbarui', $callCenter);
        }

        // ── Jalur Antarmuka Web (Blade) ──
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'in:pertanyaan,kendala_kuis,masalah_teknis,saran,lainnya'],
            'prioritas' => ['required', 'string', 'in:rendah,sedang,tinggi,darurat'],
            'nomor_telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s()]+$/'],
            'subjek' => ['required', 'string', 'min:5', 'max:150'],
            'pesan' => ['required', 'string', 'min:10', 'max:3000'],
        ], [
            'kategori.required' => 'Kategori pesan pengaduan wajib dipilih.',
            'kategori.in' => 'Kategori yang dipilih tidak valid.',
            'prioritas.required' => 'Tingkat prioritas pesan wajib dipilih.',
            'prioritas.in' => 'Tingkat prioritas tidak valid.',
            'nomor_telepon.regex' => 'Format nomor telepon/WhatsApp hanya boleh berisi angka dan simbol (+, -, ()).',
            'nomor_telepon.max' => 'Nomor telepon maksimal 20 karakter.',
            'subjek.required' => 'Subjek atau ringkasan masalah wajib diisi.',
            'subjek.min' => 'Subjek pesan minimal 5 karakter.',
            'subjek.max' => 'Subjek pesan maksimal 150 karakter.',
            'pesan.required' => 'Rincian pesan atau kendala wajib diisi.',
            'pesan.min' => 'Pesan pengaduan minimal 10 karakter.',
            'pesan.max' => 'Pesan pengaduan maksimal 3000 karakter.',
        ]);

        $callCenter->update($validated);

        return redirect()->route('call-center.show', $callCenter->id)
            ->with('success', 'Perubahan pesan Call Center berhasil disimpan.');
    }

    /**
     * Menghapus atau membatalkan tiket pesan Call Center.
     */
    public function destroy(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $callCenter = CallCenter::findOrFail($id);

        // Otorisasi: pemilik tiket atau admin
        if ($callCenter->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return $this->error('Anda tidak berhak menghapus tiket ini', 'FORBIDDEN', 403);
            }
            abort(403);
        }

        $callCenter->delete();

        if ($request->expectsJson()) {
            return $this->suksesKosong('Pesan Call Center berhasil dihapus');
        }

        return redirect()->route('call-center.index')
            ->with('success', 'Tiket pesan Call Center berhasil dibatalkan dan dihapus.');
    }
}
