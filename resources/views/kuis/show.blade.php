@extends('layouts.app')

@section('title', $kuis->judul)

@section('content')
<div style="max-width:760px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('kuis.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Daftar Kuis
        </a>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Detail & Panduan Kuis</span>
        <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 12px; letter-spacing:-1px;">
            {{ $kuis->judul }}
        </h1>
        <p style="font-size:15px; color:var(--muted); line-height:1.6; margin:0 0 24px;">
            {{ $kuis->deskripsi }}
        </p>
    </div>

    <!-- Parameter Card -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:28px; margin-bottom:32px; box-shadow:var(--shadow);">
        <h2 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0 0 16px;">Ketentuan Kuis</h2>
        <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:16px; margin-bottom:24px;">
            <div style="background:#f7f9f6; padding:14px 18px; border-radius:12px; border:1px solid var(--line);">
                <span style="font-size:12px; color:var(--muted); display:block;">Jumlah Pertanyaan</span>
                <strong style="font-size:20px; color:var(--deep-green);">{{ $kuis->pertanyaan_count }} Soal</strong>
            </div>
            <div style="background:#f7f9f6; padding:14px 18px; border-radius:12px; border:1px solid var(--line);">
                <span style="font-size:12px; color:var(--muted); display:block;">Total Poin Maksimal</span>
                <strong style="font-size:20px; color:var(--deep-green);">{{ $kuis->total_poin ?? 0 }} Poin</strong>
            </div>
        </div>

        <ul style="color:var(--muted); font-size:14px; line-height:1.8; margin:0 0 24px; padding-left:20px;">
            <li>Setiap pertanyaan memiliki 1 pilihan jawaban yang benar.</li>
            <li>Anda dapat memilih salah satu opsi untuk setiap soal.</li>
            <li>Setelah menyelesaikan pengerjaan, skor dan pembahasan lengkap akan langsung ditampilkan.</li>
        </ul>

        @auth
            <a href="{{ route('kuis.kerjakan', $kuis->id) }}" class="button button-primary button-block" style="height:50px; font-size:15px;">
                Mulai Mengerjakan Kuis Sekarang &rarr;
            </a>
        @else
            <a href="{{ route('login') }}" class="button button-primary button-block" style="height:50px; font-size:15px;">
                Masuk untuk Mengerjakan Kuis &rarr;
            </a>
        @endauth
    </div>

    @if(isset($riwayatPengerjaan) && count($riwayatPengerjaan) > 0)
        <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow);">
            <h2 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0 0 16px;">Riwayat Pengerjaan Anda untuk Kuis Ini</h2>
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($riwayatPengerjaan as $riwayat)
                    <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:#f7f9f6; border-radius:12px; font-size:13px;">
                        <div>
                            <span style="color:var(--muted);">{{ $riwayat->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:14px;">
                            <span style="font-weight:700; color:var(--deep-green);">Skor: {{ $riwayat->skor }}/{{ $riwayat->skor_maks }} ({{ $riwayat->persentase }}%)</span>
                            <a href="{{ route('hasil-kuis.show', $riwayat->id) }}" style="color:var(--green); font-weight:700; text-decoration:none;">
                                Tinjau &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
