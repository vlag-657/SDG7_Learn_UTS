@extends('layouts.app')

@section('title', 'Daftar Artikel')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
    <div>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Wawasan Edukatif</span>
        <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Artikel Energi Bersih
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Kumpulan artikel dan riset informatif tentang transisi energi terbarukan dan SDG 7.
        </p>
    </div>
</div>

<!-- Toolbar Search & Filter -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:32px; box-shadow:var(--shadow);">
    <form action="{{ route('artikel.index') }}" method="GET" style="display:flex; align-items:center; gap:12px; flex:1; margin:0;">
        <div style="position:relative; flex:1; max-width:400px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel..."
                style="width:100%; height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px 0 38px; font-size:13px; outline:none;">
            <svg style="position:absolute; left:12px; top:13px; color:var(--muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>

        <select name="sort" onchange="this.form.submit()" style="height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px; font-size:13px; background:#fff; color:var(--ink); cursor:pointer;">
            <option value="terbaru" {{ request('sort') !== 'terlama' ? 'selected' : '' }}>Terbaru</option>
            <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Terlama</option>
        </select>

        <button type="submit" class="button button-quiet button-small">Cari</button>

        @if(request('q') || request('sort'))
            <a href="{{ route('artikel.index') }}" class="button button-quiet button-small" style="color:var(--muted);">Reset</a>
        @endif
    </form>
</div>

<!-- Grid Artikel -->
<div class="article-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-bottom:40px;">
    @forelse($artikels as $art)
        <article class="article-card" style="background:#fff; border:1px solid var(--line); border-radius:18px; overflow:hidden; display:flex; flex-direction:column; box-shadow:var(--shadow);">
            @if($art->gambar_url)
                <img src="{{ $art->gambar_url }}" alt="{{ $art->judul }}" style="width:100%; height:190px; object-fit:cover;">
            @else
                <div style="width:100%; height:190px; background:#eaf5eb; display:grid; place-items:center; color:var(--green); font-weight:700;">
                    SDG 7 Energi Terbarukan
                </div>
            @endif
            <div style="padding:22px; display:flex; flex-direction:column; flex:1;">
                <span style="font-size:12px; color:var(--muted); margin-bottom:8px;">{{ $art->created_at->format('d M Y') }}</span>
                <h2 style="font-size:18px; font-weight:700; margin:0 0 10px; line-height:1.35; color:var(--ink);">
                    <a href="{{ route('artikel.show', $art->id) }}" style="color:inherit; text-decoration:none;">{{ $art->judul }}</a>
                </h2>
                <p style="font-size:13px; color:var(--muted); line-height:1.5; margin:0 0 18px; flex:1;">
                    {{ Str::limit(strip_tags($art->isi), 120) }}
                </p>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-top:auto;">
                    <a href="{{ route('artikel.show', $art->id) }}" style="font-size:13px; font-weight:700; color:var(--green); text-decoration:none;">
                        Baca Artikel &rarr;
                    </a>
                </div>
            </div>
        </article>
    @empty
        <div style="grid-column: 1 / -1; text-align:center; padding:60px 20px; background:#fff; border:1px solid var(--line); border-radius:18px;">
            <p style="font-size:16px; color:var(--muted); margin:0 0 12px;">Tidak ditemukan artikel yang sesuai kriteria pencarian.</p>
            <a href="{{ route('artikel.index') }}" class="button button-quiet button-small">Lihat Semua Artikel</a>
        </div>
    @endforelse
</div>

<!-- Pagination Links -->
<div style="display:flex; justify-content:center;">
    {{ $artikels->links() }}
</div>
@endsection
