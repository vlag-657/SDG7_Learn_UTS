@extends('layouts.app')

@section('title', 'Moderasi Diskusi: ' . $postingan->judul)

@section('content')
<div style="max-width:820px; margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.forum.index') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Moderasi Forum</a>
    </div>

    <!-- Main Thread Moderation Card -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); margin-bottom:32px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:36px; height:36px; border-radius:50%; background:var(--lime); color:var(--deep-green); display:grid; place-items:center; font-weight:800; font-size:13px;">
                    {{ strtoupper(substr($postingan->penulis->nama ?? 'A', 0, 2)) }}
                </div>
                <div>
                    <strong style="font-size:15px; color:var(--ink);">{{ $postingan->penulis->nama ?? 'Pengguna' }}</strong>
                    <span style="font-size:12px; color:var(--muted); display:block;">Email: {{ $postingan->penulis->email ?? '-' }} &bull; {{ $postingan->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
            </div>

            <form action="{{ route('admin.forum.destroy', $postingan->id) }}" method="POST" onsubmit="return confirm('Hapus seluruh postingan thread ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="button button-danger button-small" style="font-size:12px;">
                    Hapus Seluruh Thread
                </button>
            </form>
        </div>

        <h1 style="font-size:24px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:0 0 16px;">
            {{ $postingan->judul }}
        </h1>

        <div style="font-size:15px; line-height:1.75; color:var(--ink); margin-bottom:16px; white-space:pre-line;">
            {{ $postingan->isi }}
        </div>
    </div>

    <!-- Moderasi Daftar Balasan -->
    <h2 style="font-size:20px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; margin:0 0 18px;">
        Moderasi Balasan ({{ $postingan->balasan->count() }})
    </h2>

    <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:48px;">
        @forelse($postingan->balasan as $balas)
            <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:20px; box-shadow:var(--shadow); display:flex; justify-content:space-between; align-items:flex-start; gap:16px;">
                <div style="flex:1;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                        <strong style="font-size:13px; color:var(--ink);">{{ $balas->penulis->nama ?? 'Pengguna' }}</strong>
                        <span style="font-size:11px; color:var(--muted);">({{ $balas->penulis->email ?? '-' }})</span>
                        <span>&bull;</span>
                        <span style="font-size:12px; color:var(--muted);">{{ $balas->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div style="font-size:14px; line-height:1.6; color:var(--ink); white-space:pre-line;">
                        {{ $balas->isi }}
                    </div>
                </div>

                <form action="{{ route('admin.forum.balasan.destroy', $balas->id) }}" method="POST" onsubmit="return confirm('Hapus balasan komentar ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button button-danger button-small" style="font-size:11px; padding:0 12px; min-height:34px;">
                        Hapus Balasan
                    </button>
                </form>
            </div>
        @empty
            <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:32px; text-align:center; color:var(--muted);">
                Tidak ada balasan pada topik diskusi ini.
            </div>
        @endforelse
    </div>
</div>
@endsection
