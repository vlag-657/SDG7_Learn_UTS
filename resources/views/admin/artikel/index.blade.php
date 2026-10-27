@extends('layouts.app')

@section('title', 'Kelola Artikel (Admin)')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
    <div>
        <a href="{{ route('admin.dashboard') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Dashboard Admin</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Kelola Artikel Edukasi
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Tambah, sunting, dan hapus materi bacaan edukasi SDG 7.
        </p>
    </div>
    <a href="{{ route('admin.artikel.create') }}" class="button button-primary">
        + Tambah Artikel Baru
    </a>
</div>

<!-- Toolbar Search -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; margin-bottom:24px; box-shadow:var(--shadow);">
    <form action="{{ route('admin.artikel.index') }}" method="GET" style="display:flex; align-items:center; gap:12px; margin:0;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel..."
            style="flex:1; max-width:400px; height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px; font-size:13px; outline:none;">
        <button type="submit" class="button button-quiet button-small">Cari</button>
        @if(request('q'))
            <a href="{{ route('admin.artikel.index') }}" class="button button-quiet button-small">Reset</a>
        @endif
    </form>
</div>

<!-- Tabel Artikel -->
<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px; width:60px;">ID</th>
                    <th style="padding:16px 20px;">Judul Artikel</th>
                    <th style="padding:16px 20px;">Sumber Rujukan</th>
                    <th style="padding:16px 20px;">Tanggal Dibuat</th>
                    <th style="padding:16px 20px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($artikels as $art)
                    <tr style="border-bottom:1px solid var(--line);">
                        <td style="padding:16px 20px; color:var(--muted); font-size:12px;">#{{ $art->id }}</td>
                        <td style="padding:16px 20px; font-weight:700; color:var(--ink);">
                            <a href="{{ route('artikel.show', $art->id) }}" target="_blank" style="color:inherit; text-decoration:none;">{{ $art->judul }}</a>
                        </td>
                        <td style="padding:16px 20px; color:var(--muted); font-size:12px; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $art->sumber }}
                        </td>
                        <td style="padding:16px 20px; color:var(--muted); font-size:13px;">
                            {{ $art->created_at->format('d M Y') }}
                        </td>
                        <td style="padding:16px 20px; text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:8px;">
                                <a href="{{ route('admin.artikel.edit', $art->id) }}" class="button button-quiet button-small" style="font-size:12px;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.artikel.destroy', $art->id) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-danger button-small" style="font-size:12px;">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:40px 20px; text-align:center; color:var(--muted);">
                            Tidak ada artikel yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $artikels->links() }}
</div>
@endsection
