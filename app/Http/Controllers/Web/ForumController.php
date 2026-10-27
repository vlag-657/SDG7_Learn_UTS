<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Balasan;
use App\Models\Postingan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
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

    public function create(): View
    {
        return view('forum.create');
    }

    public function store(Request $request): RedirectResponse
    {
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

    public function show(int $id): View
    {
        $postingan = Postingan::with([
            'penulis',
            'balasan' => fn ($q) => $q->with('penulis')->oldest(),
        ])
            ->withCount('balasan as jumlah_balasan')
            ->findOrFail($id);

        return view('forum.show', compact('postingan'));
    }

    public function balas(Request $request, int $id): RedirectResponse
    {
        $postingan = Postingan::findOrFail($id);

        $validated = $request->validate([
            'isi' => 'required|string|min:3',
        ], [
            'isi.required' => 'Isi balasan tidak boleh kosong.',
            'isi.min'      => 'Isi balasan minimal 3 karakter.',
        ]);

        Balasan::create([
            'postingan_id' => $postingan->id,
            'user_id'      => Auth::id(),
            'isi'          => $validated['isi'],
        ]);

        return redirect()->route('forum.show', $id . '#balasan-terakhir')->with('success', 'Balasan Anda berhasil dikirim.');
    }
}
