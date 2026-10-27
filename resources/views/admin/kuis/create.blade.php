@extends('layouts.app')

@section('title', 'Buat Kuis Baru (Admin)')

@section('content')
<div style="max-width:760px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.kuis.index') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Daftar Kuis</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Buat Kuis Baru
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Langkah 1: Masukkan judul dan deskripsi kuis. Setelah kuis tersimpan, Anda dapat menambahkan daftar pertanyaan.
        </p>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow); margin-bottom:40px;">
        <form action="{{ route('admin.kuis.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:20px;">
                <label for="judul" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Judul Kuis *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required autofocus
                    placeholder="Contoh: Pengenalan Pembangkit Listrik Tenaga Bayu (PLTB)"
                    style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
            </div>

            <div style="margin-bottom:28px;">
                <label for="deskripsi" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Deskripsi Singkat</label>
                <textarea name="deskripsi" id="deskripsi" rows="5"
                    placeholder="Tuliskan petunjuk umum atau pengantar materi kuis..."
                    style="width:100%; border:1px solid var(--line); border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.6;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">{{ old('deskripsi') }}</textarea>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <a href="{{ route('admin.kuis.index') }}" class="button button-quiet">Batal</a>
                <button type="submit" class="button button-primary" style="height:48px; padding:0 28px; font-size:14px;">
                    Lanjut ke Tambah Soal &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
