@extends('layouts.app')

@section('title', 'Edit Kuis (Admin)')

@section('content')
<div style="max-width:760px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.kuis.show', $kuis->id) }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Detail Kuis</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Edit Informasi Kuis
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            ID Kuis: #{{ $kuis->id }}
        </p>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow); margin-bottom:40px;">
        <form action="{{ route('admin.kuis.update', $kuis->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom:20px;">
                <label for="judul" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Judul Kuis *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul', $kuis->judul) }}" required
                    style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
            </div>

            <div style="margin-bottom:28px;">
                <label for="deskripsi" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="5"
                    style="width:100%; border:1px solid var(--line); border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.6;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">{{ old('deskripsi', $kuis->deskripsi) }}</textarea>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <a href="{{ route('admin.kuis.show', $kuis->id) }}" class="button button-quiet">Batal</a>
                <button type="submit" class="button button-primary" style="height:48px; padding:0 28px; font-size:14px;">
                    Simpan Perubahan &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
