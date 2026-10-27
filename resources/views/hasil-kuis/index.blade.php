@extends('layouts.app')

@section('title', 'Riwayat Hasil Belajar')

@section('content')
<div class="page-heading" style="margin-bottom:32px;">
    <span class="eyebrow"><span class="eyebrow-dot"></span> Rekam Jejak Prestasi</span>
    <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
        Riwayat Hasil Kuis
    </h1>
    <p style="color:var(--muted); font-size:14px; margin:0;">
        Daftar semua percobaan kuis yang telah Anda selesaikan beserta skor dan evaluasi performa.
    </p>
</div>

<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px;">Judul Kuis</th>
                    <th style="padding:16px 20px;">Tanggal Dikerjakan</th>
                    <th style="padding:16px 20px;">Jawaban Benar</th>
                    <th style="padding:16px 20px;">Skor Diperoleh</th>
                    <th style="padding:16px 20px;">Persentase</th>
                    <th style="padding:16px 20px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                    <tr style="border-bottom:1px solid var(--line); transition:background .15s;">
                        <td style="padding:16px 20px; font-weight:700; color:var(--ink);">
                            {{ $item->kuis_judul }}
                        </td>
                        <td style="padding:16px 20px; color:var(--muted); font-size:13px;">
                            {{ $item->created_at->format('d M Y, H:i') }} WIB
                        </td>
                        <td style="padding:16px 20px;">
                            {{ $item->jumlah_benar }} dari {{ $item->jumlah_pertanyaan }} soal
                        </td>
                        <td style="padding:16px 20px; font-weight:700; color:var(--deep-green);">
                            {{ $item->skor }} / {{ $item->skor_maks }}
                        </td>
                        <td style="padding:16px 20px;">
                            <span style="display:inline-block; font-size:12px; font-weight:800; padding:4px 10px; border-radius:99px; background:{{ $item->persentase >= 70 ? '#e6f4ea' : ($item->persentase >= 50 ? '#fef7e0' : '#fce8e6') }}; color:{{ $item->persentase >= 70 ? '#137333' : ($item->persentase >= 50 ? '#b06000' : '#c5221f') }};">
                                {{ $item->persentase }}%
                            </span>
                        </td>
                        <td style="padding:16px 20px; text-align:right;">
                            <a href="{{ route('hasil-kuis.show', $item->id) }}" class="button button-quiet button-small" style="font-size:12px;">
                                Tinjauan Detail &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:48px 20px; text-align:center; color:var(--muted);">
                            Anda belum pernah mengerjakan kuis apapun. <br>
                            <a href="{{ route('kuis.index') }}" class="button button-primary button-small" style="margin-top:14px; display:inline-flex;">Mulai Belajar dengan Kuis</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $riwayat->links() }}
</div>
@endsection
