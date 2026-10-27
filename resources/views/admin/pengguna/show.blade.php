@extends('layouts.app')

@section('title', 'Detail Pengguna: ' . $pengguna->nama)

@section('content')
<div style="max-width:840px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.pengguna.index') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Daftar Pengguna</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Profil Pengguna: {{ $pengguna->nama }}
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            ID Pengguna: #{{ $pengguna->id }} &bull; Bergabung pada {{ $pengguna->created_at->format('d F Y') }}
        </p>
    </div>

    <!-- Informasi Profil Pengguna Card -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); margin-bottom:32px;">
        <div style="display:flex; align-items:center; gap:20px; margin-bottom:24px;">
            <div style="width:64px; height:64px; border-radius:50%; background:var(--lime); color:var(--deep-green); font-size:24px; font-weight:800; display:grid; place-items:center;">
                {{ strtoupper(substr($pengguna->nama, 0, 2)) }}
            </div>
            <div>
                <h2 style="font-size:22px; font-weight:800; color:var(--deep-green); margin:0 0 4px;">{{ $pengguna->nama }}</h2>
                <span style="font-size:14px; color:var(--muted);">{{ $pengguna->email }}</span>
                <span style="display:inline-block; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; padding:2px 8px; border-radius:99px; margin-left:8px; background:{{ $pengguna->role === 'admin' ? '#fef3c7' : '#e6f4ea' }}; color:{{ $pengguna->role === 'admin' ? '#92400e' : '#137333' }};">
                    {{ strtoupper($pengguna->role) }}
                </span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; border-top:1px solid var(--line); padding-top:20px; text-align:center;">
            <div style="background:#f7f9f6; padding:14px; border-radius:12px;">
                <span style="font-size:11px; color:var(--muted); display:block; margin-bottom:4px;">Kuis Diikuti</span>
                <strong style="font-size:22px; color:var(--deep-green);">{{ $ringkasan['total_kuis'] }}</strong>
            </div>
            <div style="background:#f7f9f6; padding:14px; border-radius:12px;">
                <span style="font-size:11px; color:var(--muted); display:block; margin-bottom:4px;">Rata-rata Skor</span>
                <strong style="font-size:22px; color:var(--deep-green);">{{ $ringkasan['rata_rata_skor'] }}%</strong>
            </div>
            <div style="background:#f7f9f6; padding:14px; border-radius:12px;">
                <span style="font-size:11px; color:var(--muted); display:block; margin-bottom:4px;">Thread Forum</span>
                <strong style="font-size:22px; color:var(--deep-green);">{{ $ringkasan['total_postingan'] }}</strong>
            </div>
            <div style="background:#f7f9f6; padding:14px; border-radius:12px;">
                <span style="font-size:11px; color:var(--muted); display:block; margin-bottom:4px;">Balasan Forum</span>
                <strong style="font-size:22px; color:var(--deep-green);">{{ $ringkasan['total_balasan'] }}</strong>
            </div>
        </div>
    </div>

    <!-- Aktivitas Pengerjaan Kuis Terakhir -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow); margin-bottom:32px;">
        <h3 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0 0 16px;">
            Riwayat Percobaan Kuis Terakhir
        </h3>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @forelse($hasilTerakhir as $hasil)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#f7f9f6; border-radius:12px; font-size:13px;">
                    <div>
                        <strong style="color:var(--ink);">{{ $hasil->kuis_judul }}</strong>
                        <span style="display:block; font-size:11px; color:var(--muted);">{{ $hasil->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:14px;">
                        <span style="font-weight:700; color:var(--deep-green);">Skor: {{ $hasil->skor }}/{{ $hasil->skor_maks }} ({{ $hasil->persentase }}%)</span>
                        <a href="{{ route('hasil-kuis.show', $hasil->id) }}" class="button button-quiet button-small" style="font-size:11px; padding:0 10px; min-height:30px;">
                            Tinjau &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <p style="color:var(--muted); font-size:13px; margin:0;">Belum pernah mengerjakan kuis.</p>
            @endforelse
        </div>
    </div>

    <!-- Aktivitas Postingan Forum Terakhir -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow); margin-bottom:48px;">
        <h3 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0 0 16px;">
            Topik Diskusi yang Pernah Dibuat
        </h3>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @forelse($postinganTerakhir as $post)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#f7f9f6; border-radius:12px; font-size:13px;">
                    <div>
                        <strong style="color:var(--ink);">
                            <a href="{{ route('forum.show', $post->id) }}" style="color:inherit; text-decoration:none;">{{ $post->judul }}</a>
                        </strong>
                        <span style="display:block; font-size:11px; color:var(--muted);">{{ $post->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <a href="{{ route('admin.forum.show', $post->id) }}" class="button button-quiet button-small" style="font-size:11px; padding:0 10px; min-height:30px;">
                        Lihat Thread &rarr;
                    </a>
                </div>
            @empty
                <p style="color:var(--muted); font-size:13px; margin:0;">Belum pernah membuat thread diskusi.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
