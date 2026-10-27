<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balasan;
use App\Models\Postingan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForumController extends Controller
{
    public function index(Request $request): View
    {
        $query = Postingan::with('penulis')->withCount('balasan as jumlah_balasan');

        if ($request->filled('q')) {
            $query->where('judul', 'like', '%' . $request->q . '%');
        }

        $postingan = $query->latest()->paginate(10)->withQueryString();

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

    public function hapusPostingan(int $id): RedirectResponse
    {
        $postingan = Postingan::findOrFail($id);
        $postingan->delete();

        return redirect()->route('admin.forum.index')->with('success', 'Postingan diskusi forum berhasil dihapus.');
    }

    public function hapusBalasan(int $id): RedirectResponse
    {
        $balasan = Balasan::findOrFail($id);
        $postinganId = $balasan->postingan_id;
        $balasan->delete();

        return redirect()->route('admin.forum.show', $postinganId)->with('success', 'Balasan komentar berhasil dihapus.');
    }
}
