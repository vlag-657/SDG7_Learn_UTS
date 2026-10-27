@extends('layouts.app')

@section('title', 'Daftar Kuis Interaktif')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
    <div>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Uji Pengetahuan</span>
        <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Kuis Energi Terbarukan
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Uji wawasan Anda seputar prinsip energi bersih, potensi surya, geotermal, hingga efisiensi konsumsi listrik harian.
        </p>
    </div>
</div>

<!-- Toolbar Search -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:32px; box-shadow:var(--shadow);">
    <form action="{{ route('kuis.index') }}" method="GET" style="display:flex; align-items:center; gap:12px; flex:1; margin:0;">
        <div style="position:relative; flex:1; max-width:400px;">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul kuis..."
                style="width:100%; height:42px; border:1px solid var(--line); border-radius:10px; padding:0 14px 0 38px; font-size:13px; outline:none;">
            <svg style="position:absolute; left:12px; top:13px; color:var(--muted);" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>
        <button type="submit" class="button button-quiet button-small">Cari</button>
        @if(request('q'))
            <a href="{{ route('kuis.index') }}" class="button button-quiet button-small" style="color:var(--muted);">Reset</a>
        @endif
    </form>
</div>

<!-- Grid Kuis -->
<div class="quiz-grid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:24px; margin-bottom:40px;">
    @forelse($kuises as $kuis)
        @php
            $sudahDikerjakan = isset($skorTerbaikUser[$kuis->id]);
            $skorTerbaik = $skorTerbaikUser[$kuis->id] ?? null;
        @endphp
        <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:24px; display:flex; flex-direction:column; justify-content:space-between; box-shadow:var(--shadow);">
            <div>
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                    <span style="display:inline-block; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; background:#e6f4ea; color:#137333; padding:4px 10px; border-radius:99px;">
                        {{ $kuis->pertanyaan_count }} Pertanyaan
                    </span>
                    <span style="font-size:12px; color:var(--muted); font-weight:600;">
                        Maks: {{ $kuis->total_poin ?? 0 }} Poin
                    </span>
                </div>

                <h2 style="font-size:20px; font-weight:700; color:var(--ink); margin:0 0 10px; line-height:1.3;">
                    <a href="{{ route('kuis.show', $kuis->id) }}" style="color:inherit; text-decoration:none;">{{ $kuis->judul }}</a>
                </h2>
                <p style="font-size:13px; color:var(--muted); line-height:1.5; margin:0 0 20px;">
                    {{ Str::limit($kuis->deskripsi, 110) }}
                </p>

                @if($sudahDikerjakan)
                    <div style="background:#f7f9f6; border:1px solid #e2e8df; border-radius:10px; padding:8px 12px; margin-bottom:18px; font-size:12px; display:flex; align-items:center; justify-content:space-between;">
                        <span style="color:#1b5e20; font-weight:600;">✓ Pernah dikerjakan</span>
                        <strong style="color:var(--deep-green);">Skor Terbaik: {{ $skorTerbaik }}</strong>
                    </div>
                @endif
            </div>

            <div style="display:flex; gap:10px;">
                <a href="{{ route('kuis.show', $kuis->id) }}" class="button button-quiet button-small" style="flex:1;">Info</a>
                <a href="{{ route('kuis.kerjakan', $kuis->id) }}" class="button button-primary button-small" style="flex:2;">
                    {{ $sudahDikerjakan ? 'Ulangi Kuis' : 'Mulai Kuis' }} &rarr;
                </a>
            </div>
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align:center; padding:60px 20px; background:#fff; border:1px solid var(--line); border-radius:18px;">
            <p style="font-size:16px; color:var(--muted); margin:0 0 12px;">Tidak ada kuis yang ditemukan.</p>
            <a href="{{ route('kuis.index') }}" class="button button-quiet button-small">Lihat Semua Kuis</a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div style="display:flex; justify-content:center;">
    {{ $kuises->links() }}
</div>
@endsection
