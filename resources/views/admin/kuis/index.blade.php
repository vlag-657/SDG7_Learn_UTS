@extends('layouts.app')

@section('title', 'Kelola Kuis (Admin)')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
    <div>
        <a href="{{ route('admin.dashboard') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Dashboard Admin</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Kelola Kuis Interaktif
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Kelola judul kuis, bank soal pertanyaan, opsi jawaban, dan rekap perolehan nilai peserta.
        </p>
    </div>
    <a href="{{ route('admin.kuis.create') }}" class="button button-primary">
        + Buat Kuis Baru
    </a>
</div>

<!-- Toolbar Search -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; margin-bottom:24px; box-shadow:var(--shadow);">
    <form action="{{ route('admin.kuis.index') }}" method="GET" style="display:flex; align-items:center; gap:12px; margin:0;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul kuis..."
            style="flex:1; max-width:400px; height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px; font-size:13px; outline:none;">
        <button type="submit" class="button button-quiet button-small">Cari</button>
        @if(request('q'))
            <a href="{{ route('admin.kuis.index') }}" class="button button-quiet button-small">Reset</a>
        @endif
    </form>
</div>

<!-- Tabel Kuis -->
<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px; width:60px;">ID</th>
                    <th style="padding:16px 20px;">Judul Kuis</th>
                    <th style="padding:16px 20px;">Jumlah Soal</th>
                    <th style="padding:16px 20px;">Total Poin</th>
                    <th style="padding:16px 20px; text-align:right;">Aksi & Kelola</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kuises as $kuis)
                    <tr style="border-bottom:1px solid var(--line);">
                        <td style="padding:16px 20px; color:var(--muted); font-size:12px;">#{{ $kuis->id }}</td>
                        <td style="padding:16px 20px;">
                            <strong style="color:var(--ink); font-size:15px; display:block;">{{ $kuis->judul }}</strong>
                            <span style="color:var(--muted); font-size:12px;">{{ Str::limit($kuis->deskripsi, 60) }}</span>
                        </td>
                        <td style="padding:16px 20px;">
                            <span style="display:inline-block; font-size:12px; font-weight:700; background:#e8f5e9; color:var(--deep-green); padding:3px 10px; border-radius:99px;">
                                {{ $kuis->pertanyaan_count }} Soal
                            </span>
                        </td>
                        <td style="padding:16px 20px; font-weight:700; color:var(--ink);">
                            {{ $kuis->total_poin ?? 0 }} Poin
                        </td>
                        <td style="padding:16px 20px; text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:8px;">
                                <a href="{{ route('admin.kuis.show', $kuis->id) }}" class="button button-quiet button-small" style="font-weight:700;">
                                    Kelola Soal
                                </a>
                                <a href="{{ route('admin.kuis.hasil', $kuis->id) }}" class="button button-quiet button-small" style="color:var(--green);">
                                    Rekap Nilai
                                </a>
                                <a href="{{ route('admin.kuis.edit', $kuis->id) }}" class="button button-quiet button-small">
                                    Edit
                                </a>
                                <form action="{{ route('admin.kuis.destroy', $kuis->id) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini beserta semua pertanyaan dan opsinya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-danger button-small">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:40px 20px; text-align:center; color:var(--muted);">
                            Belum ada kuis yang tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $kuises->links() }}
</div>
@endsection
