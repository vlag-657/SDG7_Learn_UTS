@extends('layouts.app')

@section('title', 'Mengerjakan: ' . $kuis->judul)

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <!-- Header Kuis -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px 28px; margin-bottom:28px; box-shadow:var(--shadow); display:flex; justify-content:space-between; align-items:center;">
        <div>
            <span class="eyebrow" style="margin-bottom:4px;"><span class="eyebrow-dot"></span> Pengerjaan Kuis</span>
            <h1 style="font-size:24px; font-weight:800; color:var(--deep-green); margin:0;">{{ $kuis->judul }}</h1>
        </div>
        <div style="text-align:right;">
            <span style="font-size:12px; color:var(--muted); display:block;">Total Soal</span>
            <strong style="font-size:18px; color:var(--green);">{{ $kuis->pertanyaan->count() }} Pertanyaan</strong>
        </div>
    </div>

    <!-- Form Pengerjaan -->
    <form action="{{ route('kuis.simpan', $kuis->id) }}" method="POST">
        @csrf

        @foreach($kuis->pertanyaan->sortBy('urutan') as $index => $soal)
            <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:28px; margin-bottom:24px; box-shadow:var(--shadow);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                    <span style="display:inline-block; font-size:12px; font-weight:800; background:var(--lime); color:var(--deep-green); padding:4px 12px; border-radius:99px;">
                        Soal #{{ $soal->urutan }}
                    </span>
                    <span style="font-size:12px; font-weight:700; color:var(--muted);">
                        Nilai: {{ $soal->poin }} Poin
                    </span>
                </div>

                <p style="font-size:17px; font-weight:600; color:var(--ink); line-height:1.5; margin:0 0 20px;">
                    {{ $soal->teks }}
                </p>

                <div style="display:flex; flex-direction:column; gap:12px;">
                    @foreach($soal->opsi as $opsi)
                        <label style="display:flex; align-items:center; gap:12px; padding:14px 18px; border:1px solid var(--line); border-radius:12px; cursor:pointer; transition:border-color .15s, background .15s;">
                            <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opsi->id }}" required style="accent-color:var(--green); width:18px; height:18px;">
                            <span style="font-size:14px; color:var(--ink); line-height:1.4;">{{ $opsi->teks }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:24px; box-shadow:var(--shadow); display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
            <span style="font-size:14px; color:var(--muted);">
                Pastikan seluruh pertanyaan telah Anda jawab sebelum mengirim.
            </span>
            <button type="submit" class="button button-primary" style="height:48px; padding:0 28px; font-size:15px;" onclick="return confirm('Apakah Anda yakin ingin mengirim semua jawaban kuis ini?')">
                Kirim Jawaban Kuis &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
