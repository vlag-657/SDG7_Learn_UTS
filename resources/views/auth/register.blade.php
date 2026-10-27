@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="auth-layout" style="display:grid; grid-template-columns: 1fr 1fr; gap:48px; align-items:center; max-width:960px; margin:30px auto;">
    <div class="auth-aside">
        <span class="eyebrow"><span class="eyebrow-dot"></span> Bergabung Sekarang</span>
        <h1 style="font-size:38px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:16px 0; line-height:1.2; letter-spacing:-1.5px;">
            Mulai langkahmu menuju masa depan energi bersih.
        </h1>
        <p style="color:var(--muted); font-size:15px; line-height:1.6; margin-bottom:24px;">
            Daftarkan diri Anda untuk mengikuti kuis bersertifikat, memantau pencapaian belajar, dan berjejaring dengan sesama pegiat energi terbarukan.
        </p>
        <ul style="color:var(--muted); font-size:14px; line-height:1.8; padding-left:20px;">
            <li>Akses penuh ke semua kuis & materi SDG 7</li>
            <li>Rekap skor & pembahasan jawaban detail</li>
            <li>Partisipasi aktif dalam forum diskusi</li>
        </ul>
    </div>

    <div class="auth-card" style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow);">
        <h2 style="font-size:24px; font-weight:800; margin:0 0 6px; color:var(--deep-green); font-family:'Manrope',sans-serif;">Daftar Akun</h2>
        <p style="font-size:13px; color:var(--muted); margin:0 0 20px;">Lengkapi data diri Anda di bawah ini.</p>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div style="margin-bottom:16px;">
                <label for="nama" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required autofocus
                    placeholder="Contoh: Budi Santoso"
                    style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
            </div>

            <div style="margin-bottom:16px;">
                <label for="email" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    placeholder="nama@email.com"
                    style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
                <div>
                    <label for="pekerjaan" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Pekerjaan</label>
                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}"
                        placeholder="Contoh: Mahasiswa"
                        style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;">
                </div>
                <div>
                    <label for="usia" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Usia</label>
                    <input type="number" name="usia" id="usia" value="{{ old('usia') }}" min="10" max="100"
                        placeholder="Contoh: 21"
                        style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:24px;">
                <div>
                    <label for="password" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Password *</label>
                    <input type="password" name="password" id="password" required
                        placeholder="Min 8 karakter"
                        style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;">
                </div>
                <div>
                    <label for="password_confirmation" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Ulangi Password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Ulangi password"
                        style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;">
                </div>
            </div>

            <button type="submit" class="button button-primary button-block" style="height:48px; font-size:14px;">
                Daftar Sekarang &rarr;
            </button>
        </form>

        <div style="margin-top:20px; text-align:center; font-size:13px; color:var(--muted);">
            Sudah memiliki akun? <a href="{{ route('login') }}" style="color:var(--green); font-weight:700; text-decoration:none;">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
