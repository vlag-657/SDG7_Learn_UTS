@extends('layouts.app')

@section('title', 'Detail Tiket #' . $callCenter->id . ' — ' . $callCenter->subjek)

@section('content')
<div style="max-width:860px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('call-center.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Daftar Tiket Call Center
        </a>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
            <div>
                <span class="eyebrow"><span class="eyebrow-dot"></span> Tiket Bantuan #CC-{{ str_pad($callCenter->id, 4, '0', STR_PAD_LEFT) }}</span>
                <h1 style="font-size:28px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-0.5px;">
                    {{ $callCenter->subjek }}
                </h1>
                <p style="color:var(--muted); font-size:13px; margin:0;">
                    Dibuat pada {{ $callCenter->created_at->format('d F Y, H:i') }} WIB &bull; Kategori: <strong>{{ $callCenter->kategori_label }}</strong>
                </p>
            </div>

            <!-- Badge Status & Prioritas -->
            <div style="display:flex; gap:8px; align-items:center;">
                <!-- Prioritas Badge -->
                @if($callCenter->prioritas === 'darurat')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:800; background:#fee2e2; color:#991b1b; border:1px solid #fecaca; text-transform:uppercase;">
                        ⚡ Darurat
                    </span>
                @elseif($callCenter->prioritas === 'tinggi')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:800; background:#ffedd5; color:#9a3412; border:1px solid #fed7aa; text-transform:uppercase;">
                        ▲ Prioritas Tinggi
                    </span>
                @elseif($callCenter->prioritas === 'sedang')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#fef9c3; color:#854d0e; border:1px solid #fef08a;">
                        ● Prioritas Sedang
                    </span>
                @else
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;">
                        ▼ Prioritas Rendah
                    </span>
                @endif

                <!-- Status Badge -->
                @if($callCenter->status === 'menunggu')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                        Menunggu Respon
                    </span>
                @elseif($callCenter->status === 'diproses')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">
                        Sedang Diproses
                    </span>
                @elseif($callCenter->status === 'selesai')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#dcfce7; color:#15803d; border:1px solid #bbf7d0;">
                        ✓ Selesai
                    </span>
                @endif

                @if($callCenter->kecepatan_penanganan)
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; {{ $callCenter->kecepatan_penanganan_badge_style }}">
                        {{ $callCenter->kecepatan_penanganan_icon }} Penanganan: {{ $callCenter->kecepatan_penanganan_label }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Kotak Isi Pesan dari Pengguna -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); margin-bottom:28px;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--line); padding-bottom:16px; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:50%; background:var(--lime); color:var(--deep-green); font-weight:800; display:flex; align-items:center; justify-content:center; font-size:14px;">
                    {{ strtoupper(substr($callCenter->nama, 0, 2)) }}
                </div>
                <div>
                    <strong style="color:var(--ink); font-size:15px; display:block;">{{ $callCenter->nama }}</strong>
                    <span style="color:var(--muted); font-size:12px;">{{ $callCenter->email }}</span>
                </div>
            </div>

            @if($callCenter->nomor_telepon)
                <div style="font-size:13px; color:var(--ink); background:#f7f9f6; border:1px solid var(--line); padding:6px 12px; border-radius:10px; display:inline-flex; align-items:center; gap:6px;">
                    <span>📞 No. Kontak / WA:</span>
                    <strong>{{ $callCenter->nomor_telepon }}</strong>
                </div>
            @endif
        </div>

        <div style="font-size:15px; line-height:1.7; color:var(--ink); white-space:pre-line;">
            {{ $callCenter->pesan }}
        </div>
    </div>

    <!-- Kotak Tanggapan Tim Administrator (Kondisional) -->
    <div style="margin-bottom:32px;">
        @if($callCenter->tanggapan)
            <div style="background:#f4fbf6; border:1.5px solid #a7e3bc; border-radius:24px; padding:32px; box-shadow:var(--shadow);">
                <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #c8eed6; padding-bottom:16px; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:40px; height:40px; border-radius:50%; background:var(--green); color:#fff; font-weight:800; display:flex; align-items:center; justify-content:center; font-size:16px;">
                            ★
                        </div>
                        <div>
                            <strong style="color:var(--deep-green); font-size:15px; display:block;">
                                {{ $callCenter->admin?->nama ?? 'Admin Layanan SDG7Learn' }}
                            </strong>
                            <span style="color:#2f855a; font-size:12px; font-weight:600;">Tim Administrator & Call Center Resmi</span>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                        @if($callCenter->kecepatan_penanganan)
                            <span style="font-size:12px; font-weight:700; padding:4px 10px; border-radius:12px; {{ $callCenter->kecepatan_penanganan_badge_style }}">
                                {{ $callCenter->kecepatan_penanganan_icon }} Penanganan: {{ $callCenter->kecepatan_penanganan_label }}
                            </span>
                        @endif
                        @if($callCenter->ditanggapi_pada)
                            <div style="font-size:12px; color:#2f855a; background:#e2f7ea; padding:4px 10px; border-radius:12px;">
                                Ditanggapi pada: {{ $callCenter->ditanggapi_pada->format('d M Y, H:i') }} WIB
                            </div>
                        @endif
                    </div>
                </div>

                <div style="font-size:15px; line-height:1.7; color:#1a3a2a; white-space:pre-line;">
                    {{ $callCenter->tanggapan }}
                </div>
            </div>
        @else
            <div style="background:#fffcf0; border:1px solid #fed7aa; border-radius:20px; padding:24px; display:flex; align-items:center; gap:16px;">
                <div style="width:44px; height:44px; border-radius:50%; background:#fef3c7; color:#d97706; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <div>
                    <strong style="color:#92400e; font-size:14px; display:block; margin-bottom:2px;">
                        Menunggu Respon Administrator
                    </strong>
                    <p style="color:#b45309; font-size:13px; margin:0; line-height:1.5;">
                        Tiket pesan Anda telah masuk ke sistem dan sedang dalam antrean tim bantuan. Anda akan menerima pembaruan di halaman ini segera setelah admin memberikan solusi.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Tombol Aksi Pengelolaan Tiket -->
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; border-top:1px solid var(--line); padding-top:24px;">
        <a href="{{ route('call-center.index') }}" class="button button-quiet">
            &larr; Kembali ke Daftar Tiket
        </a>

        <div style="display:flex; align-items:center; gap:10px;">
            {{-- Tombol Edit hanya muncul jika status masih 'menunggu' --}}
            @if($callCenter->status === 'menunggu')
                <a href="{{ route('call-center.edit', $callCenter->id) }}" class="button button-quiet" style="color:#b45309; border-color:#fef08a;">
                    Sunting Pesan
                </a>
            @endif

            {{-- Tombol Batalkan / Hapus Tiket --}}
            <form action="{{ route('call-center.destroy', $callCenter->id) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus tiket ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="button button-danger">
                    Hapus Tiket
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
