@extends('layouts.app')

@section('title', 'Pusat Bantuan & Call Center')

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px; flex-wrap:wrap; gap:16px;">
    <div>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Layanan Bantuan & Pengaduan</span>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Call Center & Dukungan Pengguna
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Kirimkan pertanyaan materi, laporkan kendala kuis atau akun, dan pantau status tanggapan admin secara langsung.
        </p>
    </div>
    <div>
        <a href="{{ route('call-center.create') }}" class="button button-primary" style="height:44px; padding:0 22px; font-size:14px; display:inline-flex; align-items:center; gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            <span>+ Buat Pesan Baru</span>
        </a>
    </div>
</div>

<!-- Informasi Kontak Cepat / Hotline Call Center -->
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:18px; margin-bottom:32px;">
    <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:20px; box-shadow:var(--shadow); display:flex; align-items:center; gap:16px;">
        <div style="width:48px; height:48px; border-radius:14px; background:#e8f5ed; color:var(--green); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
            </svg>
        </div>
        <div>
            <span style="font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px;">Hotline Bebas Pulsa</span>
            <strong style="display:block; font-size:16px; color:var(--ink); margin-top:2px;">0800-1-SDG7-HELP</strong>
            <span style="font-size:11px; color:var(--muted);">Senin - Jumat, 08:00 - 17:00 WIB</span>
        </div>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:20px; box-shadow:var(--shadow); display:flex; align-items:center; gap:16px;">
        <div style="width:48px; height:48px; border-radius:14px; background:#e0f2fe; color:#0284c7; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
        </div>
        <div>
            <span style="font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px;">WhatsApp Bantuan</span>
            <strong style="display:block; font-size:16px; color:var(--ink); margin-top:2px;">+62 812-3456-7890</strong>
            <span style="font-size:11px; color:var(--muted);">Layanan Cepat Tanggap 24/7</span>
        </div>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:20px; box-shadow:var(--shadow); display:flex; align-items:center; gap:16px;">
        <div style="width:48px; height:48px; border-radius:14px; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>
        <div>
            <span style="font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:0.5px;">Surel Administrator</span>
            <strong style="display:block; font-size:16px; color:var(--ink); margin-top:2px;">support@sdg7learn.com</strong>
            <span style="font-size:11px; color:var(--muted);">Tanggapan dalam 1x24 jam kerja</span>
        </div>
    </div>
</div>

<!-- Kartu Ringkasan Metrik Pengguna -->
<div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:28px;">
    <div style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:18px 20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:var(--muted); font-weight:600; display:block; margin-bottom:4px;">Total Pesan Dikirim</span>
        <strong style="font-size:28px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif;">
            {{ $stats['total'] }}
        </strong>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:18px 20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:#92400e; font-weight:600; display:block; margin-bottom:4px;">Menunggu Respon</span>
        <strong style="font-size:28px; font-weight:800; color:#b45309; font-family:'Manrope',sans-serif;">
            {{ $stats['menunggu'] }}
        </strong>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:18px 20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:#0369a1; font-weight:600; display:block; margin-bottom:4px;">Sedang Diproses</span>
        <strong style="font-size:28px; font-weight:800; color:#0284c7; font-family:'Manrope',sans-serif;">
            {{ $stats['diproses'] }}
        </strong>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:18px 20px; box-shadow:var(--shadow);">
        <span style="font-size:12px; color:#15803d; font-weight:600; display:block; margin-bottom:4px;">Selesai Ditanggapi</span>
        <strong style="font-size:28px; font-weight:800; color:#16a34a; font-family:'Manrope',sans-serif;">
            {{ $stats['selesai'] }}
        </strong>
    </div>
</div>

<!-- Toolbar Pencarian & Filter Status -->
<div class="toolbar" style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:16px 20px; margin-bottom:24px; box-shadow:var(--shadow); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
    <!-- Filter Status Tab -->
    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('call-center.index', array_merge(request()->except('status', 'page'))) }}" 
           class="button {{ !request('status') ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Semua ({{ $stats['total'] }})
        </a>
        <a href="{{ route('call-center.index', array_merge(request()->except('status', 'page'), ['status' => 'menunggu'])) }}" 
           class="button {{ request('status') === 'menunggu' ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Menunggu ({{ $stats['menunggu'] }})
        </a>
        <a href="{{ route('call-center.index', array_merge(request()->except('status', 'page'), ['status' => 'diproses'])) }}" 
           class="button {{ request('status') === 'diproses' ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Diproses ({{ $stats['diproses'] }})
        </a>
        <a href="{{ route('call-center.index', array_merge(request()->except('status', 'page'), ['status' => 'selesai'])) }}" 
           class="button {{ request('status') === 'selesai' ? 'button-primary' : 'button-quiet' }} button-small" style="font-size:12px;">
            Selesai ({{ $stats['selesai'] }})
        </a>
    </div>

    <!-- Form Pencarian Subjek -->
    <form action="{{ route('call-center.index') }}" method="GET" style="display:flex; align-items:center; gap:8px; margin:0;">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari subjek atau isi pesan..."
            style="min-width:240px; height:38px; border:1px solid var(--line); border-radius:10px; padding:0 12px; font-size:13px; outline:none;"
            onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
        <button type="submit" class="button button-quiet button-small" style="height:38px;">Cari</button>
        @if(request('q'))
            <a href="{{ route('call-center.index', request()->except('q')) }}" class="button button-quiet button-small" style="height:38px;">Reset</a>
        @endif
    </form>
</div>

<!-- Daftar Pesan / Tiket Pengaduan Pengguna -->
<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px; width:70px;">ID</th>
                    <th style="padding:16px 20px;">Subjek & Kategori</th>
                    <th style="padding:16px 20px; width:130px;">Prioritas</th>
                    <th style="padding:16px 20px; width:150px;">Status</th>
                    <th style="padding:16px 20px; width:160px;">Tanggapan Admin</th>
                    <th style="padding:16px 20px; width:140px;">Waktu Kirim</th>
                    <th style="padding:16px 20px; text-align:right; width:160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanList as $pesan)
                    <tr style="border-bottom:1px solid var(--line); transition:background-color 0.15s ease;" onmouseover="this.style.backgroundColor='#fafbf9'" onmouseout="this.style.backgroundColor='transparent'">
                        <!-- ID Tiket -->
                        <td style="padding:16px 20px; font-weight:700; color:var(--muted); font-size:12px;">
                            #CC-{{ str_pad($pesan->id, 4, '0', STR_PAD_LEFT) }}
                        </td>

                        <!-- Subjek & Kategori -->
                        <td style="padding:16px 20px;">
                            <a href="{{ route('call-center.show', $pesan->id) }}" style="font-weight:700; color:var(--ink); text-decoration:none; display:block; margin-bottom:4px; font-size:15px;">
                                {{ $pesan->subjek }}
                            </a>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:12px; color:var(--muted); background:#f3f4f6; padding:2px 8px; border-radius:6px; font-weight:600;">
                                    {{ $pesan->kategori_label }}
                                </span>
                                @if($pesan->nomor_telepon)
                                    <span style="font-size:12px; color:var(--muted); display:inline-flex; align-items:center; gap:4px;">
                                        📞 {{ $pesan->nomor_telepon }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Prioritas (Kondisional Badge) -->
                        <td style="padding:16px 20px;">
                            @if($pesan->prioritas === 'darurat')
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:800; background:#fee2e2; color:#991b1b; border:1px solid #fecaca; text-transform:uppercase;">
                                    ⚡ Darurat
                                </span>
                            @elseif($pesan->prioritas === 'tinggi')
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:800; background:#ffedd5; color:#9a3412; border:1px solid #fed7aa; text-transform:uppercase;">
                                    ▲ Tinggi
                                </span>
                            @elseif($pesan->prioritas === 'sedang')
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; background:#fef9c3; color:#854d0e; border:1px solid #fef08a;">
                                    ● Sedang
                                </span>
                            @else
                                <span style="display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:600; background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;">
                                    ▼ Rendah
                                </span>
                            @endif
                        </td>

                        <!-- Status Tiket (Kondisional Badge) -->
                        <td style="padding:16px 20px;">
                            @if($pesan->status === 'menunggu')
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#d97706;"></span>
                                    Menunggu
                                </span>
                            @elseif($pesan->status === 'diproses')
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#0284c7;"></span>
                                    Diproses
                                </span>
                            @elseif($pesan->status === 'selesai')
                                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700; background:#dcfce7; color:#15803d; border:1px solid #bbf7d0;">
                                    <span style="width:6px; height:6px; border-radius:50%; background:#16a34a;"></span>
                                    Selesai
                                </span>
                            @endif
                        </td>

                        <!-- Status Tanggapan Admin -->
                        <td style="padding:16px 20px; font-size:13px;">
                            @if($pesan->tanggapan)
                                <span style="color:#15803d; font-weight:700; display:inline-flex; align-items:center; gap:4px;">
                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Ada Tanggapan
                                </span>
                                @if($pesan->kecepatan_penanganan)
                                    <div style="margin-top:3px;">
                                        <span style="font-size:10px; font-weight:700; padding:2px 6px; border-radius:6px; {{ $pesan->kecepatan_penanganan_badge_style }}">
                                            {{ $pesan->kecepatan_penanganan_icon }} {{ $pesan->kecepatan_penanganan_label }}
                                        </span>
                                    </div>
                                @endif
                            @else
                                <span style="color:var(--muted); font-style:italic;">Belum ada balasan</span>
                            @endif
                        </td>

                        <!-- Tanggal Kirim -->
                        <td style="padding:16px 20px; color:var(--muted); font-size:13px;">
                            {{ $pesan->created_at->format('d M Y') }}
                            <div style="font-size:11px; color:#9ca3af;">{{ $pesan->created_at->format('H:i') }} WIB</div>
                        </td>

                        <!-- Aksi CRUD -->
                        <td style="padding:16px 20px; text-align:right;">
                            <div style="display:inline-flex; align-items:center; gap:6px;">
                                <a href="{{ route('call-center.show', $pesan->id) }}" class="button button-quiet button-small" style="font-size:12px; padding:4px 10px;">
                                    Buka
                                </a>

                                {{-- Kondisional: Hanya bisa disunting jika status masih 'menunggu' --}}
                                @if($pesan->status === 'menunggu')
                                    <a href="{{ route('call-center.edit', $pesan->id) }}" class="button button-quiet button-small" style="font-size:12px; padding:4px 10px; color:#b45309;">
                                        Edit
                                    </a>
                                @endif

                                {{-- Aksi Hapus / Batalkan Pesan --}}
                                <form action="{{ route('call-center.destroy', $pesan->id) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus tiket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button-danger button-small" style="font-size:12px; padding:4px 8px;" title="Batalkan Tiket">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding:48px 20px; text-align:center; color:var(--muted);">
                            <div style="max-width:360px; margin:0 auto;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px; color:#9ca3af; display:block;">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                                <p style="font-size:15px; font-weight:700; color:var(--ink); margin:0 0 6px;">Belum Ada Tiket Pesan</p>
                                <p style="font-size:13px; color:var(--muted); margin:0 0 16px;">
                                    Punya pertanyaan tentang materi energi bersih, kuis, atau kendala platform? Kirim pesan ke admin sekarang.
                                </p>
                                <a href="{{ route('call-center.create') }}" class="button button-primary button-small">
                                    Kirim Pesan Pertama Anda &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $pesanList->links() }}
</div>
@endsection
