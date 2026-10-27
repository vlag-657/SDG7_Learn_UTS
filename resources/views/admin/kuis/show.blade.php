@extends('layouts.app')

@section('title', 'Detail Soal Kuis: ' . $kuis->judul)

@section('content')
<div style="max-width:880px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.kuis.index') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Daftar Kuis</a>
        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-top:8px;">
            <div>
                <span class="eyebrow"><span class="eyebrow-dot"></span> Manajemen Soal & Jawaban</span>
                <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:6px 0 4px;">
                    {{ $kuis->judul }}
                </h1>
                <p style="color:var(--muted); font-size:14px; margin:0;">
                    Total: {{ $kuis->pertanyaan->count() }} Pertanyaan &bull; Total Poin: {{ $kuis->total_poin ?? 0 }} Poin
                </p>
            </div>
            <div style="display:flex; gap:10px;">
                <a href="{{ route('admin.kuis.hasil', $kuis->id) }}" class="button button-quiet button-small">
                    Rekap Nilai Peserta
                </a>
                <a href="{{ route('admin.kuis.pertanyaan.create', $kuis->id) }}" class="button button-primary button-small">
                    + Tambah Soal Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Deskripsi Kuis Card -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:18px; padding:20px 24px; margin-bottom:28px; box-shadow:var(--shadow); display:flex; justify-content:space-between; align-items:center;">
        <div>
            <span style="font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase;">Deskripsi Kuis:</span>
            <p style="font-size:14px; color:var(--ink); margin:4px 0 0; line-height:1.5;">
                {{ $kuis->deskripsi ?: 'Tidak ada deskripsi.' }}
            </p>
        </div>
        <a href="{{ route('admin.kuis.edit', $kuis->id) }}" class="button button-quiet button-small">
            Edit Info
        </a>
    </div>

    <!-- Daftar Soal Pertanyaan -->
    <h2 style="font-size:20px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; margin:0 0 18px;">
        Daftar Pertanyaan ({{ $kuis->pertanyaan->count() }})
    </h2>

    <div style="display:flex; flex-direction:column; gap:20px; margin-bottom:48px;">
        @forelse($kuis->pertanyaan->sortBy('urutan') as $soal)
            <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:26px; box-shadow:var(--shadow);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="font-size:12px; font-weight:800; background:var(--lime); color:var(--deep-green); padding:4px 12px; border-radius:99px;">
                            Soal #{{ $soal->urutan }}
                        </span>
                        <strong style="font-size:13px; color:var(--green);">{{ $soal->poin }} Poin</strong>
                    </div>

                    <div style="display:flex; align-items:center; gap:8px;">
                        <a href="{{ route('admin.kuis.pertanyaan.edit', [$kuis->id, $soal->id]) }}" class="button button-quiet button-small" style="font-size:12px;">
                            Edit Soal
                        </a>
                        <form action="{{ route('admin.kuis.pertanyaan.destroy', [$kuis->id, $soal->id]) }}" method="POST" style="margin:0; display:inline;" onsubmit="return confirm('Hapus pertanyaan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-danger button-small" style="font-size:12px;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <h3 style="font-size:16px; font-weight:700; color:var(--ink); line-height:1.45; margin:0 0 16px;">
                    {{ $soal->teks }}
                </h3>

                <!-- Pilihan Opsi -->
                <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:14px;">
                    @foreach($soal->opsi as $opsi)
                        <div style="display:flex; align-items:center; justify-content:space-between; padding:10px 14px; border-radius:10px; font-size:13px; border:1px solid {{ $opsi->benar ? '#a6e9bb' : '#e6e9e0' }}; background:{{ $opsi->benar ? '#eaf8ee' : '#fff' }};">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <span style="font-weight:700; color:{{ $opsi->benar ? '#137333' : 'var(--muted)' }};">
                                    {{ $opsi->benar ? '✓' : '&bull;' }}
                                </span>
                                <span style="color:var(--ink); font-weight:{{ $opsi->benar ? '700' : '400' }};">{{ $opsi->teks }}</span>
                            </div>
                            @if($opsi->benar)
                                <span style="font-size:11px; font-weight:800; color:#137333; text-transform:uppercase; background:#c2ebd0; padding:2px 8px; border-radius:99px;">
                                    Kunci Jawaban Benar
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($soal->penjelasan)
                    <div style="background:#f7f9f6; border-left:3px solid var(--green); padding:10px 14px; border-radius:0 8px 8px 0; font-size:12px; color:var(--muted); line-height:1.5;">
                        <strong style="color:var(--deep-green);">Penjelasan:</strong> {{ $soal->penjelasan }}
                    </div>
                @endif
            </div>
        @empty
            <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:48px 20px; text-align:center; color:var(--muted);">
                <p style="font-size:15px; margin:0 0 16px;">Kuis ini belum memiliki pertanyaan soal.</p>
                <a href="{{ route('admin.kuis.pertanyaan.create', $kuis->id) }}" class="button button-primary button-small">
                    + Tambah Soal Pertama
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
