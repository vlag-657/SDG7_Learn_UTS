@extends('layouts.app')

@section('title', 'Moderasi Forum (Admin)')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
    <div>
        <a href="{{ route('admin.dashboard') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Dashboard Admin</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Moderasi Forum Komunitas
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Pantau dan moderasi postingan thread serta balasan komentar anggota komunitas.
        </p>
    </div>
</div>

<!-- Toolbar Search -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; margin-bottom:24px; box-shadow:var(--shadow);">
    <form action="{{ route('admin.forum.index') }}" method="GET" style="display:flex; align-items:center; gap:12px; margin:0;">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul postingan..."
            style="flex:1; max-width:400px; height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px; font-size:13px; outline:none;">
        <button type="submit" class="button button-quiet button-small">Cari</button>
        @if(request('q'))
            <a href="{{ route('admin.forum.index') }}" class="button button-quiet button-small">Reset</a>
        @endif
    </form>
</div>

<!-- Tabel Thread Forum -->
<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px; width:60px;">ID</th>
                    <th style="padding:16px 20px;">Topik Diskusi</th>
                    <th style="padding:16px 20px;">Penulis</th>
                    <th style="padding:16px 20px;">Balasan</th>
                    <th style="padding:16px 20px;">Tanggal Dibuat</th>
                    <th style="padding:16px 20px; text-align:right;">Aksi Moderasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($postingan as $thread)
                    <tr style="border-bottom:1px solid var(--line);">
                        <td style="padding:16px 20px; color:var(--muted); font-size:12px;">#{{ $thread->id }}</td>
                        <td style="padding:16px 20px;">
                            <strong style="color:var(--ink); font-size:15px; display:block;">
                                <a href="{{ route('admin.forum.show', $thread->id) }}" style="color:inherit; text-decoration:none;">{{ $thread->judul }}</a>
                            </strong>
                            <span style="color:var(--muted); font-size:12px;">{{ Str::limit($thread->isi, 80) }}</span>
                        </td>
                        <td style="padding:16px 20px;">
                            <strong style="color:var(--ink); font-size:13px;">{{ $thread->penulis->nama ?? 'Pengguna' }}</strong>
                            <span style="display:block; font-size:11px; color:var(--muted);">{{ $thread->penulis->pekerjaan ?? '-' }}</span>
                        </td>
                        <td style="padding:16px 20px;">
                            <span style="font-weight:700; color:var(--deep-green); background:#f7f9f6; padding:3px 10px; border-radius:99px; border:1px solid var(--line); font-size:12px;">
                                {{ $thread->jumlah_balasan }} Komentar
                            </span>
                        </td>
                        <td style="padding:16px 20px; color:var(--muted); font-size:13px;">
                            {{ $thread->created_at->format('d M Y') }}
                        </td>
                        <td style="padding:16px 20px; text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:8px;">
                                <a href="{{ route('admin.forum.show', $thread->id) }}" class="button button-quiet button-small" style="font-size:12px;">
                                    Detail & Balasan
                                </a>
                                <form action="{{ route('admin.forum.destroy', $thread->id) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini beserta semua balasannya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-danger button-small" style="font-size:12px;">
                                        Hapus Thread
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:40px 20px; text-align:center; color:var(--muted);">
                            Belum ada postingan diskusi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $postingan->links() }}
</div>
@endsection
