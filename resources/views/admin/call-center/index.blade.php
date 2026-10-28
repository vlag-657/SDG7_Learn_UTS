@extends('layouts.app')

@section('title', 'Kelola Call Center & Pengaduan (Admin)')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px; flex-wrap:wrap; gap:16px;">
    <div>
        <a href="{{ route('admin.dashboard') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Dashboard Admin</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Kelola Call Center & Pesan Pengaduan
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Pantau dan tanggapi pesan pengaduan, kendala materi, serta pertanyaan teknis dari seluruh pengguna platform.
        </p>
    </div>
</div>

<!-- Navigasi Cepat Admin Tabs -->
<div style="display:flex; gap:12px; margin-bottom:32px; flex-wrap:wrap;">
    <a href="{{ route('admin.dashboard') }}" class="button button-quiet button-small">Dashboard Utama</a>
    <a href="{{ route('admin.artikel.index') }}" class="button button-quiet button-small">Kelola Artikel</a>
    <a href="{{ route('admin.kuis.index') }}" class="button button-quiet button-small">Kelola Kuis</a>
    <a href="{{ route('admin.forum.index') }}" class="button button-quiet button-small">Moderasi Forum</a>
    <a href="{{ route('admin.pengguna.index') }}" class="button button-quiet button-small">Pengguna Terdaftar</a>
    <a href="{{ route('admin.call-center.index') }}" class="button button-primary button-small">Call Center ({{ $stats['total'] }})</a>
</div>

<!-- Kartu Ringkasan Metrik Admin -->
<div style="display:grid; grid-template-columns:repeat(5, 1fr); gap:16px; margin-bottom:32px;">
    <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:var(--muted); font-weight:600; display:block; margin-bottom:4px;">Total Pesan Masuk</span>
        <strong style="font-size:28px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif;">
            {{ $stats['total'] }}
        </strong>
    </div>

    <div style="background:#fff; border:1px solid #fde68a; border-radius:18px; padding:20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:#92400e; font-weight:600; display:block; margin-bottom:4px;">Menunggu Respon</span>
        <strong style="font-size:28px; font-weight:800; color:#b45309; font-family:'Manrope',sans-serif;">
            {{ $stats['menunggu'] }}
        </strong>
    </div>

    <div style="background:#fff; border:1px solid #bae6fd; border-radius:18px; padding:20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:#0369a1; font-weight:600; display:block; margin-bottom:4px;">Sedang Diproses</span>
        <strong style="font-size:28px; font-weight:800; color:#0284c7; font-family:'Manrope',sans-serif;">
            {{ $stats['diproses'] }}
        </strong>
    </div>

    <div style="background:#fff; border:1px solid #bbf7d0; border-radius:18px; padding:20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:#15803d; font-weight:600; display:block; margin-bottom:4px;">Selesai Ditanggapi</span>
        <strong style="font-size:28px; font-weight:800; color:#16a34a; font-family:'Manrope',sans-serif;">
            {{ $stats['selesai'] }}
        </strong>
    </div>

    <div style="background:#fff; border:1px solid #fecaca; border-radius:18px; padding:20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:#991b1b; font-weight:700; display:block; margin-bottom:4px;">⚡ Darurat Aktif</span>
        <strong style="font-size:28px; font-weight:800; color:#dc2626; font-family:'Manrope',sans-serif;">
            {{ $stats['darurat'] }}
        </strong>
    </div>
</div>

<!-- Toolbar Search & Filter Status -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; margin-bottom:24px; box-shadow:var(--shadow); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
    <!-- Filter Status Tabs -->
    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.call-center.index', array_merge(request()->except('status', 'page'))) }}" 
           class="button {{ !request('status') ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Semua ({{ $stats['total'] }})
        </a>
        <a href="{{ route('admin.call-center.index', array_merge(request()->except('status', 'page'), ['status' => 'menunggu'])) }}" 
           class="button {{ request('status') === 'menunggu' ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Menunggu ({{ $stats['menunggu'] }})
        </a>
        <a href="{{ route('admin.call-center.index', array_merge(request()->except('status', 'page'), ['status' => 'diproses'])) }}" 
           class="button {{ request('status') === 'diproses' ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Diproses ({{ $stats['diproses'] }})
        </a>
        <a href="{{ route('admin.call-center.index', array_merge(request()->except('status', 'page'), ['status' => 'selesai'])) }}" 
           class="button {{ request('status') === 'selesai' ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Selesai ({{ $stats['selesai'] }})
        </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.call-center.index') }}" method="GET" style="display:flex; align-items:center; gap:8px; margin:0;">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, subjek..."
            style="min-width:260px; height:38px; border:1px solid var(--line); border-radius:10px; padding:0 12px; font-size:13px; outline:none;"
            onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
        <button type="submit" class="button button-quiet button-small" style="height:38px;">Cari</button>
        @if(request('q'))
            <a href="{{ route('admin.call-center.index', request()->except('q')) }}" class="button button-quiet button-small" style="height:38px;">Reset</a>
        @endif
    </form>
</div>

<!-- Tabel Pengaduan Masuk -->
<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px; width:70px;">ID</th>
                    <th style="padding:16px 20px; width:220px;">Pengirim & Kontak</th>
                    <th style="padding:16px 20px;">Subjek & Cuplikan Pesan</th>
                    <th style="padding:16px 20px; width:120px;">Prioritas</th>
                    <th style="padding:16px 20px; width:140px;">Status</th>
                    <th style="padding:16px 20px; width:130px;">Waktu Masuk</th>
                    <th style="padding:16px 20px; text-align:right; width:160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($callCenters as $cc)
                    <tr style="border-bottom:1px solid var(--line); transition:background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#fafbf9'" onmouseout="this.style.backgroundColor='transparent'">
                        <!-- ID -->
                        <td style="padding:16px 20px; font-weight:700; color:var(--muted); font-size:12px;">
                            #CC-{{ str_pad($cc->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- Pengirim -->
                        <td style="padding:16px 20px;">
                            <strong style="color:var(--ink); display:block; font-size:14px;">{{ $cc->nama }}</strong>
                            <span style="font-size:12px; color:var(--muted); display:block;">{{ $cc->email }}</span>
                            @if($cc->nomor_telepon)
                                <span style="font-size:11px; color:#0284c7; display:inline-flex; align-items:center; gap:2px; margin-top:2px;">
                                    📞 {{ $cc->nomor_telepon }}
                                </span>
                            @endif
                        </td>

                        <!-- Subjek & Pesan Cuplikan -->
                        <td style="padding:16px 20px;">
                            <a href="{{ route('admin.call-center.show', $cc->id) }}" style="font-weight:700; color:var(--ink); text-decoration:none; display:block; margin-bottom:4px; font-size:15px;">
                                {{ $cc->subjek }}
                            </a>
                            <p style="margin:0 0 4px; font-size:12px; color:var(--muted); max-width:420px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $cc->pesan }}
                            </p>
                            <span style="font-size:11px; color:var(--muted); background:#f3f4f6; padding:2px 8px; border-radius:6px; font-weight:600;">
                                {{ $cc->kategori_label }}
                            </span>
                        </td>

                        <!-- Prioritas -->
                        <td style="padding:16px 20px;">
                            @if($cc->prioritas === 'darurat')
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:800; background:#fee2e2; color:#991b1b; border:1px solid #fecaca; text-transform:uppercase;">
                                    ⚡ Darurat
                                </span>
                            @elseif($cc->prioritas === 'tinggi')
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:800; background:#ffedd5; color:#9a3412; border:1px solid #fed7aa; text-transform:uppercase;">
                                    ▲ Tinggi
                                </span>
                            @elseif($cc->prioritas === 'sedang')
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; background:#fef9c3; color:#854d0e; border:1px solid #fef08a;">
                                    ● Sedang
                                </span>
                            @else
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;">
                                    ▼ Rendah
                                </span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td style="padding:16px 20px;">
                            @if($cc->status === 'menunggu')
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                                    Menunggu
                                </span>
                            @elseif($cc->status === 'diproses')
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">
                                    Diproses
                                </span>
                            @elseif($cc->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#dcfce7; color:#15803d; border:1px solid #bbf7d0;">
                                    ✓ Selesai
                                </span>
                            @endif

                            @if($cc->kecepatan_penanganan)
                                <div style="margin-top:4px;">
                                    <span style="font-size:10px; font-weight:700; padding:2px 6px; border-radius:8px; {{ $cc->kecepatan_penanganan_badge_style }}">
                                        {{ $cc->kecepatan_penanganan_icon }} {{ $cc->kecepatan_penanganan_label }}
                                    </span>
                                </div>
                            @endif
                        </td>

                        <!-- Waktu Masuk -->
                        <td style="padding:16px 20px; color:var(--muted); font-size:13px;">
                            {{ $cc->created_at->format('d M Y') }}
                            <div style="font-size:11px; color:#9ca3af;">{{ $cc->created_at->format('H:i') }} WIB</div>
                        </td>

                        <!-- Aksi Admin -->
                        <td style="padding:16px 20px; text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                <a href="{{ route('admin.call-center.show', $cc->id) }}" class="button button-quiet button-small" style="font-size:12px; padding:4px 10px;">
                                    Tanggapi
                                </a>

                                <form action="{{ route('admin.call-center.destroy', $cc->id) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket Call Center ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-danger button-small" style="font-size:12px; padding:4px 8px;" title="Hapus Tiket">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:48px 20px; text-align:center; color:var(--muted);">
                            Tidak ada pesan pengaduan yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $callCenters->links() }}
</div>
@endsection
