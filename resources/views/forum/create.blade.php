@extends('layouts.app')

@section('title', 'Buat Topik Diskusi Baru')

@section('content')
<div style="max-width:760px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('forum.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Forum
        </a>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Diskusi Komunitas</span>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Mulai Diskusi Baru
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Ajukan pertanyaan, bagikan pengalaman proyek, atau buka percakapan seputar energi terbarukan.
        </p>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow); margin-bottom:40px;">
        <form action="{{ route('forum.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:20px;">
                <label for="judul" style="display:block; font-size:14px; font-weight:700; margin-bottom:8px; color:var(--ink);">Judul Diskusi *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required autofocus
                    placeholder="Contoh: Pengalaman Menggunakan Panel Surya Portable untuk Mahasiswa"
                    style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                <span style="display:block; font-size:12px; color:var(--muted); margin-top:4px;">Gunakan judul yang ringkas dan deskriptif.</span>
            </div>

            <div style="margin-bottom:28px;">
                <label for="isi" style="display:block; font-size:14px; font-weight:700; margin-bottom:8px; color:var(--ink);">Isi Diskusi *</label>
                <textarea name="isi" id="isi" rows="8" required
                    placeholder="Jelaskan pertanyaan atau topik yang ingin Anda diskusikan secara rinci..."
                    style="width:100%; border:1px solid var(--line); border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.6;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">{{ old('isi') }}</textarea>
                <span style="display:block; font-size:12px; color:var(--muted); margin-top:4px;">Minimal 10 karakter.</span>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <a href="{{ route('forum.index') }}" class="button button-quiet">Batal</a>
                <button type="submit" class="button button-primary" style="height:48px; padding:0 28px; font-size:14px;">
                    Publikasikan Diskusi &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
