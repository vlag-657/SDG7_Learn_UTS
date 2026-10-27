@extends('layouts.app')

@section('title', 'Tinjauan Hasil: ' . $hasil->kuis_judul)

@section('content')
<div style="max-width:820px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('hasil-kuis.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Riwayat Hasil Kuis
        </a>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Evaluasi Pengerjaan</span>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0;">
            {{ $hasil->kuis_judul }}
        </h1>
        <p style="font-size:13px; color:var(--muted); margin:0;">
            Dikerjakan oleh <strong>{{ $hasil->user->nama ?? 'Pengguna' }}</strong> pada {{ $hasil->created_at->format('d F Y, H:i') }} WIB.
        </p>
    </div>

    <!-- Skor Ringkasan Card -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; margin-bottom:36px; box-shadow:var(--shadow); display:grid; grid-template-columns: repeat(4, 1fr); gap:16px; text-align:center;">
        <div style="padding:10px;">
            <span style="font-size:12px; color:var(--muted); display:block; margin-bottom:4px;">Total Skor</span>
            <strong style="font-size:32px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif;">{{ $hasil->skor }}</strong>
            <span style="font-size:12px; color:var(--muted); display:block;">dari {{ $hasil->skor_maks }} Poin</span>
        </div>
        <div style="padding:10px; border-left:1px solid var(--line);">
            <span style="font-size:12px; color:var(--muted); display:block; margin-bottom:4px;">Persentase</span>
            <strong style="font-size:32px; font-weight:800; color:{{ $hasil->persentase >= 70 ? '#137333' : ($hasil->persentase >= 50 ? '#b06000' : '#c5221f') }}; font-family:'Manrope',sans-serif;">
                {{ $hasil->persentase }}%
            </strong>
            <span style="font-size:12px; color:var(--muted); display:block;">Tingkat Akurasi</span>
        </div>
        <div style="padding:10px; border-left:1px solid var(--line);">
            <span style="font-size:12px; color:var(--muted); display:block; margin-bottom:4px;">Jawaban Benar</span>
            <strong style="font-size:32px; font-weight:800; color:#137333; font-family:'Manrope',sans-serif;">{{ $hasil->jumlah_benar }}</strong>
            <span style="font-size:12px; color:var(--muted); display:block;">dari {{ $hasil->jumlah_pertanyaan }} Soal</span>
        </div>
        <div style="padding:10px; border-left:1px solid var(--line);">
            <span style="font-size:12px; color:var(--muted); display:block; margin-bottom:4px;">Jawaban Salah</span>
            <strong style="font-size:32px; font-weight:800; color:#c5221f; font-family:'Manrope',sans-serif;">{{ $hasil->jumlah_pertanyaan - $hasil->jumlah_benar }}</strong>
            <span style="font-size:12px; color:var(--muted); display:block;">Perlu Ditingkatkan</span>
        </div>
    </div>

    <!-- Tinjauan Per Soal -->
    <h2 style="font-size:22px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; margin:0 0 20px;">
        Tinjauan Pembahasan Soal
    </h2>

    <div style="display:flex; flex-direction:column; gap:24px; margin-bottom:40px;">
        @if(is_array($hasil->tinjauan))
            @foreach($hasil->tinjauan as $index => $item)
                @php
                    $isBenar = $item['benar'] ?? false;
                @endphp
                <div style="background:#fff; border:1px solid {{ $isBenar ? '#c2ebd0' : '#ffd1cd' }}; border-radius:20px; padding:28px; box-shadow:var(--shadow);">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                        <span style="display:inline-block; font-size:12px; font-weight:800; background:{{ $isBenar ? '#e6f4ea' : '#fce8e6' }}; color:{{ $isBenar ? '#137333' : '#c5221f' }}; padding:4px 12px; border-radius:99px;">
                            Soal #{{ $item['urutan'] ?? ($index + 1) }} &bull; {{ $isBenar ? 'Jawaban Benar' : 'Jawaban Salah' }}
                        </span>
                        <strong style="font-size:13px; color:{{ $isBenar ? '#137333' : '#c5221f' }};">
                            +{{ $item['poin_diperoleh'] ?? 0 }} / {{ $item['poin'] ?? 0 }} Poin
                        </strong>
                    </div>

                    <h3 style="font-size:17px; font-weight:700; color:var(--ink); line-height:1.45; margin:0 0 18px;">
                        {{ $item['teks'] ?? '' }}
                    </h3>

                    <!-- Ringkasan Opsi -->
                    <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:18px;">
                        @if(isset($item['opsi']) && is_array($item['opsi']))
                            @foreach($item['opsi'] as $opsi)
                                @php
                                    $isUserChoice = isset($item['opsi_dipilih_id']) && $item['opsi_dipilih_id'] == $opsi['id'];
                                    $isCorrectChoice = isset($item['opsi_benar_id']) && $item['opsi_benar_id'] == $opsi['id'];
                                @endphp
                                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-radius:12px; font-size:13px; border:1px solid {{ $isCorrectChoice ? '#a6e9bb' : ($isUserChoice ? '#fca5a5' : '#e6e9e0') }}; background:{{ $isCorrectChoice ? '#eaf8ee' : ($isUserChoice ? '#fff1f0' : '#fff') }};">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <span style="font-weight:700; color:{{ $isCorrectChoice ? '#137333' : ($isUserChoice ? '#c5221f' : 'var(--muted)') }};">
                                            @if($isCorrectChoice) ✓ @elseif($isUserChoice) ✗ @else &bull; @endif
                                        </span>
                                        <span style="color:var(--ink); font-weight:{{ $isUserChoice || $isCorrectChoice ? '600' : '400' }};">{{ $opsi['teks'] }}</span>
                                    </div>
                                    @if($isCorrectChoice)
                                        <span style="font-size:11px; font-weight:800; color:#137333; text-transform:uppercase;">Kunci Jawaban</span>
                                    @elseif($isUserChoice)
                                        <span style="font-size:11px; font-weight:800; color:#c5221f; text-transform:uppercase;">Pilihan Anda</span>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if(!empty($item['penjelasan']))
                        <div style="background:#f7f9f6; border-left:4px solid var(--green); padding:14px 18px; border-radius:0 12px 12px 0; font-size:13px; line-height:1.6; color:var(--ink);">
                            <strong style="display:block; color:var(--deep-green); margin-bottom:4px;">💡 Penjelasan Edukatif:</strong>
                            {{ $item['penjelasan'] }}
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:48px;">
        <a href="{{ route('hasil-kuis.index') }}" class="button button-quiet">
            &larr; Riwayat Hasil Belajar
        </a>
        <a href="{{ route('kuis.index') }}" class="button button-primary">
            Jelajahi Kuis Lainnya &rarr;
        </a>
    </div>
</div>
@endsection
