@extends('layouts.app')

@section('title', $postingan->judul)

@section('content')
<div style="max-width:820px; margin:0 auto;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('forum.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Forum Diskusi
        </a>
    </div>

    <!-- Main Thread Card -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); margin-bottom:32px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
            <div style="width:36px; height:36px; border-radius:50%; background:var(--lime); color:var(--deep-green); display:grid; place-items:center; font-weight:800; font-size:13px;">
                {{ strtoupper(substr($postingan->penulis->nama ?? 'A', 0, 2)) }}
            </div>
            <div>
                <div style="display:flex; align-items:center; gap:8px;">
                    <strong style="font-size:15px; color:var(--ink);">{{ $postingan->penulis->nama ?? 'Pengguna' }}</strong>
                    @if(($postingan->penulis->role ?? '') === 'admin')
                        <span style="background:#fef3c7; color:#92400e; font-size:10px; font-weight:800; padding:2px 8px; border-radius:99px;">Admin</span>
                    @endif
                </div>
                <span style="font-size:12px; color:var(--muted);">{{ $postingan->created_at->format('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        <h1 style="font-size:26px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:0 0 16px; line-height:1.3;">
            {{ $postingan->judul }}
        </h1>

        <div style="font-size:15px; line-height:1.75; color:var(--ink); margin-bottom:24px; white-space:pre-line;">
            {{ $postingan->isi }}
        </div>

        <div style="display:flex; align-items:center; justify-content:space-between; border-top:1px solid var(--line); padding-top:16px; font-size:13px; color:var(--muted);">
            <span>{{ $postingan->jumlah_balasan }} Komentar</span>
            <a href="#form-balasan" style="color:var(--green); font-weight:700; text-decoration:none;">+ Tambah Balasan</a>
        </div>
    </div>

    <!-- Balasan Section -->
    <h2 style="font-size:20px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; margin:0 0 20px;">
        Balasan Diskusi ({{ $postingan->balasan->count() }})
    </h2>

    <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:40px;">
        @forelse($postingan->balasan as $balas)
            <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:22px; box-shadow:var(--shadow);">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:28px; height:28px; border-radius:50%; background:#e8f5e9; color:var(--deep-green); display:grid; place-items:center; font-weight:800; font-size:11px;">
                            {{ strtoupper(substr($balas->penulis->nama ?? 'A', 0, 2)) }}
                        </div>
                        <div>
                            <span style="font-size:14px; font-weight:700; color:var(--ink);">{{ $balas->penulis->nama ?? 'Pengguna' }}</span>
                            @if(($balas->penulis->role ?? '') === 'admin')
                                <span style="background:#fef3c7; color:#92400e; font-size:9px; font-weight:800; padding:1px 6px; border-radius:99px; margin-left:4px;">Admin</span>
                            @endif
                        </div>
                    </div>
                    <span style="font-size:12px; color:var(--muted);">{{ $balas->created_at->diffForHumans() }}</span>
                </div>

                <div style="font-size:14px; line-height:1.65; color:var(--ink); padding-left:38px; white-space:pre-line;">
                    {{ $balas->isi }}
                </div>
            </div>
        @empty
            <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:32px; text-align:center; color:var(--muted);">
                Belum ada balasan pada topik ini. Jadilah yang pertama memberikan tanggapan!
            </div>
        @endforelse
    </div>

    <!-- Form Kirim Balasan -->
    <div id="form-balasan" style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); margin-bottom:48px;">
        <h3 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0 0 16px;">Tulis Balasan Anda</h3>

        @auth
            <form action="{{ route('forum.balas', $postingan->id) }}" method="POST">
                @csrf
                <div style="margin-bottom:18px;">
                    <textarea name="isi" id="isi" rows="4" required
                        placeholder="Ketik tanggapan Anda di sini..."
                        style="width:100%; border:1px solid var(--line); border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.6;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">{{ old('isi') }}</textarea>
                </div>
                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" class="button button-primary" style="height:44px; padding:0 24px; font-size:13px;">
                        Kirim Balasan &rarr;
                    </button>
                </div>
            </form>
        @else
            <div style="text-align:center; padding:12px;">
                <p style="font-size:14px; color:var(--muted); margin:0 0 14px;">Silakan masuk terlebih dahulu untuk ikut berdiskusi dan memberikan balasan.</p>
                <a href="{{ route('login') }}" class="button button-primary button-small">Masuk untuk Menanggapi</a>
            </div>
        @endauth
    </div>
</div>
@endsection
