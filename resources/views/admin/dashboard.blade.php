@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:32px;">
    <div>
        <span class="eyebrow" style="color:#d97706;"><span class="eyebrow-dot" style="background:#d97706;"></span> Hak Akses Administrator</span>
        <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Panel Dashboard Admin
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Pantau statistik konten, kelola artikel edukasi, bank soal kuis, serta pantau aktivitas komunitas.
        </p>
    </div>
</div>

<!-- Navigasi Cepat Admin Tabs -->
<div style="display:flex; gap:12px; margin-bottom:32px; flex-wrap:wrap;">
    <a href="{{ route('admin.dashboard') }}" class="button button-primary button-small">Dashboard Utama</a>
    <a href="{{ route('admin.artikel.index') }}" class="button button-quiet button-small">Kelola Artikel ({{ $stats['total_artikel'] }})</a>
    <a href="{{ route('admin.kuis.index') }}" class="button button-quiet button-small">Kelola Kuis ({{ $stats['total_kuis'] }})</a>
    <a href="{{ route('admin.forum.index') }}" class="button button-quiet button-small">Moderasi Forum ({{ $stats['total_postingan'] }})</a>
    <a href="{{ route('admin.pengguna.index') }}" class="button button-quiet button-small">Pengguna Terdaftar ({{ $stats['total_pengguna'] }})</a>
</div>

<!-- Kartu Metrik Utama -->
<div class="metric-grid" style="display:grid; grid-template-columns:repeat(4, 1fr); gap:20px; margin-bottom:40px;">
    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow);">
        <span style="font-size:13px; color:var(--muted); font-weight:600; display:block; margin-bottom:6px;">Artikel Publik</span>
        <strong style="font-size:32px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; display:block; margin-bottom:12px;">
            {{ $stats['total_artikel'] }}
        </strong>
        <a href="{{ route('admin.artikel.create') }}" style="font-size:12px; font-weight:700; color:var(--green); text-decoration:none;">
            + Tambah Artikel &rarr;
        </a>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow);">
        <span style="font-size:13px; color:var(--muted); font-weight:600; display:block; margin-bottom:6px;">Total Kuis</span>
        <strong style="font-size:32px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; display:block; margin-bottom:12px;">
            {{ $stats['total_kuis'] }}
        </strong>
        <a href="{{ route('admin.kuis.create') }}" style="font-size:12px; font-weight:700; color:var(--green); text-decoration:none;">
            + Buat Kuis Baru &rarr;
        </a>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow);">
        <span style="font-size:13px; color:var(--muted); font-weight:600; display:block; margin-bottom:6px;">Diskusi Forum</span>
        <strong style="font-size:32px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; display:block; margin-bottom:12px;">
            {{ $stats['total_postingan'] }}
        </strong>
        <a href="{{ route('admin.forum.index') }}" style="font-size:12px; font-weight:700; color:var(--green); text-decoration:none;">
            Moderasi Diskusi &rarr;
        </a>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow);">
        <span style="font-size:13px; color:var(--muted); font-weight:600; display:block; margin-bottom:6px;">Pengguna Aktif</span>
        <strong style="font-size:32px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; display:block; margin-bottom:12px;">
            {{ $stats['total_pengguna'] }}
        </strong>
        <a href="{{ route('admin.pengguna.index') }}" style="font-size:12px; font-weight:700; color:var(--green); text-decoration:none;">
            Lihat Pengguna &rarr;
        </a>
    </div>
</div>

<!-- Kolom 2: Tabel Aktivitas Terbaru -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:28px; margin-bottom:48px;">
    <!-- Artikel Terbaru Admin -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <h2 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0;">Artikel Terbaru</h2>
            <a href="{{ route('admin.artikel.index') }}" style="font-size:12px; font-weight:700; color:var(--green); text-decoration:none;">Kelola Semua</a>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px;">
            @forelse($artikelTerbaru as $art)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#f7f9f6; border-radius:12px; font-size:13px;">
                    <span style="font-weight:600; color:var(--ink); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:280px;">
                        {{ $art->judul }}
                    </span>
                    <a href="{{ route('admin.artikel.edit', $art->id) }}" style="color:var(--green); font-weight:700; font-size:12px; text-decoration:none;">Edit</a>
                </div>
            @empty
                <p style="color:var(--muted); font-size:13px;">Belum ada artikel.</p>
            @endforelse
        </div>
    </div>

    <!-- Pengerjaan Kuis Terbaru -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <h2 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0;">Percobaan Kuis Terbaru</h2>
            <span style="font-size:12px; color:var(--muted);">Total: {{ $stats['total_percobaan'] }}</span>
        </div>
        <div style="display:flex; flex-direction:column; gap:12px;">
            @forelse($hasilKuisTerbaru as $hk)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 14px; background:#f7f9f6; border-radius:12px; font-size:13px;">
                    <div>
                        <strong style="color:var(--ink);">{{ $hk->user->nama ?? 'User' }}</strong>
                        <span style="color:var(--muted); font-size:11px; display:block;">{{ $hk->kuis_judul }}</span>
                    </div>
                    <div style="text-align:right;">
                        <span style="font-weight:800; color:{{ $hk->persentase >= 70 ? '#137333' : '#b06000' }}; font-size:12px;">{{ $hk->persentase }}%</span>
                        <a href="{{ route('hasil-kuis.show', $hk->id) }}" style="display:block; font-size:11px; color:var(--green); text-decoration:none;">Tinjau</a>
                    </div>
                </div>
            @empty
                <p style="color:var(--muted); font-size:13px;">Belum ada pengerjaan kuis.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
