@extends('layouts.app')

@section('title', 'Tambah Artikel Baru (Admin)')

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.artikel.index') }}" style="font-size:13px; font-weight:700; color:var(--muted); text-decoration:none;">&larr; Kembali ke Daftar Artikel Admin</a>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px;">
            Tambah Artikel Edukasi
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Buat konten bacaan baru mengenai energi terbarukan dan keberlanjutan.
        </p>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow); margin-bottom:40px;">
        <form action="{{ route('admin.artikel.store') }}" method="POST">
            @csrf

            <div style="margin-bottom:18px;">
                <label for="judul" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Judul Artikel *</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required autofocus
                    placeholder="Contoh: Mengoptimalkan Pemanfaatan Energi Angin Lepas Pantai"
                    style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px;">
                <div>
                    <label for="sumber" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Sumber Rujukan *</label>
                    <input type="text" name="sumber" id="sumber" value="{{ old('sumber') }}" required
                        placeholder="Contoh: https://www.irena.org atau ESDM RI"
                        style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                </div>
                <div>
                    <label for="gambar_url" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">URL Cover Gambar</label>
                    <input type="url" name="gambar_url" id="gambar_url" value="{{ old('gambar_url') }}"
                        placeholder="https://images.unsplash.com/..."
                        style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                </div>
            </div>

            <div style="margin-bottom:28px;">
                <label for="isi" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">Konten Lengkap Artikel *</label>
                <textarea name="isi" id="isi" rows="12" required
                    placeholder="Tuliskan isi artikel edukasi lengkap di sini..."
                    style="width:100%; border:1px solid var(--line); border-radius:12px; padding:16px; font-size:14px; font-family:inherit; outline:none; line-height:1.7;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">{{ old('isi') }}</textarea>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <a href="{{ route('admin.artikel.index') }}" class="button button-quiet">Batal</a>
                <button type="submit" class="button button-primary" style="height:48px; padding:0 28px; font-size:14px;">
                    Simpan & Terbitkan Artikel &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
