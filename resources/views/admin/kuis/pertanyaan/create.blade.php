@extends('layouts.app')

@section('title', 'Tambah Pertanyaan Soal (Admin)')

@section('content')
<div style="max-width:780px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.kuis.show', $kuis->id) }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Detail Kuis</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Tambah Pertanyaan Soal
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Kuis: <strong>{{ $kuis->judul }}</strong>
        </p>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow); margin-bottom:40px;">
        <form action="{{ route('admin.kuis.pertanyaan.store', $kuis->id) }}" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px;">
                <div>
                    <label for="urutan" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Nomor Urut Soal *</label>
                    <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $nextUrutan) }}" min="1" required
                        style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;">
                </div>
                <div>
                    <label for="poin" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Bobot Poin *</label>
                    <input type="number" name="poin" id="poin" value="{{ old('poin', 10) }}" min="1" required
                        style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;">
                </div>
            </div>

            <div style="margin-bottom:24px;">
                <label for="teks" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Teks Pertanyaan *</label>
                <textarea name="teks" id="teks" rows="3" required autofocus
                    placeholder="Tuliskan teks pertanyaan soal..."
                    style="width:100%; border:1px solid var(--line); border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.5;">{{ old('teks') }}</textarea>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:14px; font-weight:700; margin-bottom:8px; color:var(--ink);">
                    Pilihan Jawaban (Opsi) & Kunci Jawaban Benar *
                </label>
                <span style="font-size:12px; color:var(--muted); display:block; margin-bottom:12px;">
                    Tuliskan 4 opsi jawaban dan centang radio button pada opsi yang merupakan <strong>jawaban benar</strong>.
                </span>

                <div style="display:flex; flex-direction:column; gap:10px;">
                    @for($i = 0; $i < 4; $i++)
                        <div style="display:flex; align-items:center; gap:12px; padding:10px 14px; border:1px solid var(--line); border-radius:12px; background:#f7f9f6;">
                            <input type="radio" name="benar" value="{{ $i }}" {{ old('benar', 0) == $i ? 'checked' : '' }} title="Tandai sebagai jawaban benar" style="accent-color:var(--green); width:20px; height:20px; cursor:pointer;">
                            <input type="text" name="opsi[{{ $i }}]" value="{{ old('opsi.'.$i) }}" required
                                placeholder="Pilihan opsi {{ chr(65 + $i) }}..."
                                style="flex:1; height:38px; border:1px solid var(--line); border-radius:8px; padding:0 12px; font-size:13px; background:#fff; outline:none;">
                        </div>
                    @endfor
                </div>
            </div>

            <div style="margin-bottom:28px;">
                <label for="penjelasan" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Penjelasan Edukatif (Opsional)</label>
                <textarea name="penjelasan" id="penjelasan" rows="3"
                    placeholder="Penjelasan yang akan ditampilkan kepada peserta saat meninjau hasil pengerjaan..."
                    style="width:100%; border:1px solid var(--line); border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.5;">{{ old('penjelasan') }}</textarea>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <a href="{{ route('admin.kuis.show', $kuis->id) }}" class="button button-quiet">Batal</a>
                <button type="submit" class="button button-primary" style="height:48px; padding:0 28px; font-size:14px;">
                    Simpan Pertanyaan &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
