@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="hero" style="margin-bottom: 48px;">
        <div class="hero-copy">
            <span class="eyebrow"><span class="eyebrow-dot"></span> Edukasi Energi Bersih #SDG7</span>
            <h1>Pelajari masa depan energi terbarukan hari ini.</h1>
            <p>Platform interaktif untuk memahami teknologi ramah lingkungan, menguji pengetahuan dengan kuis terstandar, dan berdiskusi bersama komunitas peminat energi masa depan.</p>
            <div class="hero-buttons">
                <a href="{{ route('artikel.index') }}" class="button button-primary">Mulai Belajar</a>
                <a href="{{ route('kuis.index') }}" class="button button-quiet">Coba Kuis Interaktif</a>
            </div>
        </div>
        <div class="hero-art" aria-hidden="true">
            <svg viewBox="0 0 200 200" fill="none" style="width: 200px; height: 200px;">
                <circle cx="100" cy="100" r="80" fill="#c6e977" opacity="0.25"/>
                <circle cx="100" cy="100" r="55" fill="#c6e977" opacity="0.4"/>
                <path d="M106 32L62 108h36l-8 60 48-76h-34l12-60Z" fill="#23764d"/>
            </svg>
        </div>
    </section>

    <!-- Stats Strip -->
    <div class="stats-strip" style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; background:#fff; border:1px solid var(--line); border-radius:18px; padding:24px; margin-bottom:52px; text-align:center; box-shadow:var(--shadow);">
        <div class="stat-item">
            <strong style="display:block; font-size:28px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif;">{{ $stats['artikel'] }}</strong>
            <span style="font-size:13px; color:var(--muted);">Artikel Edukatif</span>
        </div>
        <div class="stat-item" style="border-left:1px solid var(--line);">
            <strong style="display:block; font-size:28px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif;">{{ $stats['kuis'] }}</strong>
            <span style="font-size:13px; color:var(--muted);">Kuis Interaktif</span>
        </div>
        <div class="stat-item" style="border-left:1px solid var(--line);">
            <strong style="display:block; font-size:28px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif;">{{ $stats['postingan'] }}</strong>
            <span style="font-size:13px; color:var(--muted);">Topik Diskusi</span>
        </div>
        <div class="stat-item" style="border-left:1px solid var(--line);">
            <strong style="display:block; font-size:28px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif;">{{ $stats['pengguna'] }}</strong>
            <span style="font-size:13px; color:var(--muted);">Pelajar Tergabung</span>
        </div>
    </div>

    <!-- Artikel Terbaru Section -->
    <section class="section" style="margin-bottom: 56px;">
        <div class="section-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:24px;">
            <div>
                <span class="eyebrow" style="margin-bottom:6px;"><span class="eyebrow-dot"></span> Wawasan Terbaru</span>
                <h2 style="font-size:32px; font-weight:800; margin:0; letter-spacing:-1px; color:var(--deep-green); font-family:'Manrope',sans-serif;">Artikel Pilihan</h2>
            </div>
            <a href="{{ route('artikel.index') }}" class="button button-quiet button-small">Lihat Semua Artikel &rarr;</a>
        </div>

        <div class="article-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
            @forelse($artikelTerbaru as $art)
                <article class="article-card" style="background:#fff; border:1px solid var(--line); border-radius:18px; overflow:hidden; display:flex; flex-direction:column; transition:transform .2s, box-shadow .2s;">
                    @if($art->gambar_url)
                        <img src="{{ $art->gambar_url }}" alt="{{ $art->judul }}" style="width:100%; height:180px; object-fit:cover;">
                    @else
                        <div style="width:100%; height:180px; background:#eaf5eb; display:grid; place-items:center; color:var(--green); font-weight:700;">
                            SDG 7 Energi Bersih
                        </div>
                    @endif
                    <div style="padding:22px; display:flex; flex-direction:column; flex:1;">
                        <span style="font-size:12px; color:var(--muted); margin-bottom:8px;">{{ $art->created_at->format('d M Y') }}</span>
                        <h3 style="font-size:18px; font-weight:700; margin:0 0 10px; line-height:1.35; color:var(--ink);">
                            <a href="{{ route('artikel.show', $art->id) }}" style="color:inherit; text-decoration:none;">{{ $art->judul }}</a>
                        </h3>
                        <p style="font-size:13px; color:var(--muted); line-height:1.5; margin:0 0 16px; flex:1;">
                            {{ Str::limit(strip_tags($art->isi), 110) }}
                        </p>
                        <a href="{{ route('artikel.show', $art->id) }}" style="font-size:13px; font-weight:700; color:var(--green); text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            Baca Selengkapnya &rarr;
                        </a>
                    </div>
                </article>
            @empty
                <p style="color:var(--muted);">Belum ada artikel yang tersedia.</p>
            @endforelse
        </div>
    </section>

    <!-- Kuis Unggulan Section -->
    <section class="section" style="margin-bottom: 56px;">
        <div class="section-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:24px;">
            <div>
                <span class="eyebrow" style="margin-bottom:6px;"><span class="eyebrow-dot"></span> Uji Pemahaman</span>
                <h2 style="font-size:32px; font-weight:800; margin:0; letter-spacing:-1px; color:var(--deep-green); font-family:'Manrope',sans-serif;">Kuis Interaktif</h2>
            </div>
            <a href="{{ route('kuis.index') }}" class="button button-quiet button-small">Lihat Semua Kuis &rarr;</a>
        </div>

        <div class="quiz-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
            @forelse($kuisUnggulan as $k)
                <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:24px; display:flex; flex-direction:column; justify-content:space-between; box-shadow:var(--shadow);">
                    <div>
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                            <span style="display:inline-block; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; background:#e6f4ea; color:#137333; padding:4px 10px; border-radius:99px;">
                                {{ $k->pertanyaan_count }} Pertanyaan
                            </span>
                            <span style="font-size:12px; color:var(--muted); font-weight:600;">
                                Total: {{ $k->total_poin ?? 0 }} Poin
                            </span>
                        </div>
                        <h3 style="font-size:19px; font-weight:700; color:var(--ink); margin:0 0 10px; line-height:1.3;">
                            {{ $k->judul }}
                        </h3>
                        <p style="font-size:13px; color:var(--muted); line-height:1.5; margin:0 0 20px;">
                            {{ Str::limit($k->deskripsi, 100) }}
                        </p>
                    </div>
                    <a href="{{ route('kuis.show', $k->id) }}" class="button button-primary button-small button-block">
                        Lihat Kuis &rarr;
                    </a>
                </div>
            @empty
                <p style="color:var(--muted);">Belum ada kuis yang tersedia.</p>
            @endforelse
        </div>
    </section>

    <!-- Komunitas Banner CTA -->
    <div class="cta-panel" style="background:linear-gradient(135deg, var(--deep-green) 0%, #1c523c 100%); color:#fff; border-radius:24px; padding:40px; display:flex; align-items:center; justify-content:space-between; gap:24px;">
        <div style="max-width:540px;">
            <span style="display:inline-block; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.8px; background:rgba(255,255,255,0.15); padding:4px 12px; border-radius:99px; margin-bottom:12px;">
                Ruang Diskusi Terbuka
            </span>
            <h2 style="font-size:28px; font-weight:800; font-family:'Manrope',sans-serif; margin:0 0 12px; line-height:1.25;">Punya pertanyaan seputar energi bersih?</h2>
            <p style="font-size:14px; opacity:0.85; margin:0; line-height:1.6;">Diskusikan kendala instalasi surya, teknologi turbin, efisiensi konsumsi listrik, dan gagasan inovatif bersama rekan lainnya di Forum Diskusi.</p>
        </div>
        <div>
            <a href="{{ route('forum.index') }}" class="button" style="background:var(--lime); color:var(--deep-green); font-weight:800; white-space:nowrap; padding:0 24px;">
                Buka Forum Diskusi &rarr;
            </a>
        </div>
    </div>
@endsection
