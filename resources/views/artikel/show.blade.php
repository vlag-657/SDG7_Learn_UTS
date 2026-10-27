@extends('layouts.app')

@section('title', $artikel->judul)

@section('content')
<div style="max-width:820px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('artikel.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Daftar Artikel
        </a>
        <span class="eyebrow" style="display:block;"><span class="eyebrow-dot"></span> Edukasi SDG 7</span>
        <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:12px 0 16px; line-height:1.25; letter-spacing:-1px;">
            {{ $artikel->judul }}
        </h1>
        <div style="display:flex; align-items:center; gap:16px; font-size:13px; color:var(--muted);">
            <span>Dipublikasikan: <strong>{{ $artikel->created_at->format('d F Y') }}</strong></span>
            @if($artikel->sumber)
                <span>&bull;</span>
                <span>Sumber: <a href="{{ $artikel->sumber }}" target="_blank" rel="noopener noreferrer" style="color:var(--green); text-decoration:underline;">Kunjungi Rujukan</a></span>
            @endif
        </div>
    </div>

    @if($artikel->gambar_url)
        <div style="margin-bottom:32px; border-radius:20px; overflow:hidden; border:1px solid var(--line); box-shadow:var(--shadow);">
            <img src="{{ $artikel->gambar_url }}" alt="{{ $artikel->judul }}" style="width:100%; max-height:450px; object-fit:cover; display:block;">
        </div>
    @endif

    <div class="article-content" style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:36px; margin-bottom:48px; box-shadow:var(--shadow); line-height:1.8; font-size:16px; color:var(--ink);">
        {!! nl2br(e($artikel->isi)) !!}
    </div>

    @if(isset($artikelTerkait) && $artikelTerkait->count() > 0)
        <div style="border-top:1px solid var(--line); padding-top:36px;">
            <h3 style="font-size:22px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; margin:0 0 20px;">
                Artikel Terkait Lainnya
            </h3>
            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px;">
                @foreach($artikelTerkait as $terkait)
                    <div style="background:#fff; border:1px solid var(--line); border-radius:14px; padding:18px; display:flex; flex-direction:column; justify-content:space-between;">
                        <h4 style="font-size:15px; font-weight:700; margin:0 0 10px; line-height:1.35;">
                            <a href="{{ route('artikel.show', $terkait->id) }}" style="color:var(--ink); text-decoration:none;">{{ $terkait->judul }}</a>
                        </h4>
                        <a href="{{ route('artikel.show', $terkait->id) }}" style="font-size:12px; font-weight:700; color:var(--green); text-decoration:none; margin-top:auto;">
                            Baca &rarr;
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
