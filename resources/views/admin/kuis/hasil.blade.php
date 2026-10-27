@extends('layouts.app')

@section('title', 'Rekap Nilai: ' . $kuis->judul)

@section('content')
<div class="page-heading" style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
    <div>
        <a href="{{ route('admin.kuis.show', $kuis->id) }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Detail Kuis</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Rekap Nilai Peserta Kuis
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Kuis: <strong>{{ $kuis->judul }}</strong> &bull; Total Hasil: {{ $hasilList->total() }} Pengerjaan
        </p>
    </div>
</div>

<div style="background:#fff; border:1px solid var(--line); border-radius:20px; overflow:hidden; box-shadow:var(--shadow); margin-bottom:32px;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; font-size:14px;">
            <thead>
                <tr style="background:#f7f9f6; border-bottom:1px solid var(--line); color:var(--deep-green); font-weight:700;">
                    <th style="padding:16px 20px;">Nama Pengguna</th>
                    <th style="padding:16px 20px;">Email</th>
                    <th style="padding:16px 20px;">Waktu Pengerjaan</th>
                    <th style="padding:16px 20px;">Skor</th>
                    <th style="padding:16px 20px;">Persentase</th>
                    <th style="padding:16px 20px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hasilList as $hasil)
                    <tr style="border-bottom:1px solid var(--line);">
                        <td style="padding:16px 20px; font-weight:700; color:var(--ink);">
                            {{ $hasil->user->nama ?? 'Pengguna' }}
                        </td>
                        <td style="padding:16px 20px; color:var(--muted); font-size:13px;">
                            {{ $hasil->user->email ?? '-' }}
                        </td>
                        <td style="padding:16px 20px; color:var(--muted); font-size:13px;">
                            {{ $hasil->created_at->format('d M Y, H:i') }} WIB
                        </td>
                        <td style="padding:16px 20px; font-weight:700; color:var(--deep-green);">
                            {{ $hasil->skor }} / {{ $hasil->skor_maks }}
                        </td>
                        <td style="padding:16px 20px;">
                            <span style="display:inline-block; font-size:12px; font-weight:800; padding:4px 10px; border-radius:99px; background:{{ $hasil->persentase >= 70 ? '#e6f4ea' : ($hasil->persentase >= 50 ? '#fef7e0' : '#fce8e6') }}; color:{{ $hasil->persentase >= 70 ? '#137333' : ($hasil->persentase >= 50 ? '#b06000' : '#c5221f') }};">
                                {{ $hasil->persentase }}%
                            </span>
                        </td>
                        <td style="padding:16px 20px; text-align:right;">
                            <a href="{{ route('hasil-kuis.show', $hasil->id) }}" class="button button-quiet button-small" style="font-size:12px;">
                                Tinjau Jawaban &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:40px 20px; text-align:center; color:var(--muted);">
                            Belum ada pengguna yang mengerjakan kuis ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="display:flex; justify-content:center;">
    {{ $hasilList->links() }}
</div>
@endsection
