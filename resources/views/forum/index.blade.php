@extends('layouts.app')

@section('title', 'Forum Diskusi Komunitas')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
    <div>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Ruang Kolaborasi</span>
        <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Forum Diskusi Energi Bersih
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Berbagi pengalaman, bertanya tentang tantangan teknis, dan bertukar wawasan seputar SDG 7.
        </p>
    </div>
    @auth
        <a href="{{ route('forum.create') }}" class="button button-primary">
            + Buat Diskusi Baru
        </a>
    @else
        <a href="{{ route('login') }}" class="button button-primary">
            Masuk untuk Berdiskusi &rarr;
        </a>
    @endauth
</div>

<!-- Toolbar Search & Filter -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:32px; box-shadow:var(--shadow);">
    <form action="{{ route('forum.index') }}" method="GET" style="display:flex; align-items:center; gap:12px; flex:1; margin:0;">
        <div style="position:relative; flex:1; max-width:400px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik diskusi..."
                style="width:100%; height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px 0 38px; font-size:13px; outline:none;">
            <svg style="position:absolute; left:12px; top:13px; color:var(--muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>

        <select name="sort" onchange="this.form.submit()" style="height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px; font-size:13px; background:#fff; color:var(--ink); cursor:pointer;">
            <option value="terbaru" {{ request('sort') !== 'terlama' ? 'selected' : '' }}>Diskusi Terbaru</option>
            <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Diskusi Terlama</option>
        </select>

        <button type="submit" class="button button-quiet button-small">Cari</button>
        @if(request('q') || request('sort'))
            <a href="{{ route('forum.index') }}" class="button button-quiet button-small" style="color:var(--muted);">Reset</a>
        @endif
    </form>
</div>

<!-- List Topik Diskusi -->
<div class="forum-list" style="display:flex; flex-direction:column; gap:16px; margin-bottom:40px;">
    @forelse($postingan as $thread)
        <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:24px; box-shadow:var(--shadow); display:flex; justify-content:space-between; align-items:center; gap:20px; transition:transform .15s, box-shadow .15s;">
            <div style="flex:1;">
                <div style="display:flex; align-items:center; gap:10px; font-size:12px; color:var(--muted); margin-bottom:8px;">
                    <span style="display:inline-flex; align-items:center; justify-content:center; width:24px; height:24px; border-radius:50%; background:var(--lime); color:var(--deep-green); font-size:10px; font-weight:800;">
                        {{ strtoupper(substr($thread->penulis->nama ?? 'A', 0, 2)) }}
                    </span>
                    <strong style="color:var(--ink);">{{ $thread->penulis->nama ?? 'Pengguna' }}</strong>
                    @if(($thread->penulis->role ?? '') === 'admin')
                        <span style="background:#fef3c7; color:#92400e; font-size:10px; font-weight:800; padding:2px 8px; border-radius:99px;">Admin</span>
                    @endif
                    <span>&bull;</span>
                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                </div>

                <h2 style="font-size:19px; font-weight:700; margin:0 0 8px; line-height:1.35; color:var(--ink);">
                    <a href="{{ route('forum.show', $thread->id) }}" style="color:inherit; text-decoration:none;">{{ $thread->judul }}</a>
                </h2>

                <p style="font-size:13px; color:var(--muted); line-height:1.5; margin:0;">
                    {{ Str::limit(strip_tags($thread->isi), 150) }}
                </p>
            </div>

            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:12px; min-width:120px;">
                <div style="display:flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--deep-green); background:#f7f9f6; padding:6px 14px; border-radius:99px; border:1px solid var(--line);">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <span>{{ $thread->jumlah_balasan }} Balasan</span>
                </div>
                <a href="{{ route('forum.show', $thread->id) }}" class="button button-quiet button-small" style="font-size:12px;">
                    Buka Diskusi &rarr;
                </a>
            </div>
        </div>
    @empty
        <div style="text-align:center; padding:60px 20px; background:#fff; border:1px solid var(--line); border-radius:18px;">
            <p style="font-size:16px; color:var(--muted); margin:0 0 12px;">Belum ada topik diskusi yang dimulai.</p>
            @auth
                <a href="{{ route('forum.create') }}" class="button button-primary button-small">+ Mulai Topik Diskusi Pertama</a>
            @endauth
        </div>
    @endforelse
</div>

<div style="display:flex; justify-content:center;">
    {{ $postingan->links() }}
</div>
@endsection
