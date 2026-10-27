@extends('layouts.app')

@section('title', 'Masuk ke Akun')

@section('content')
<div class="auth-layout" style="display:grid; grid-template-columns: 1fr 1fr; gap:48px; align-items:center; max-width:960px; margin:40px auto;">
    <div class="auth-aside">
        <span class="eyebrow"><span class="eyebrow-dot"></span> Selamat Datang Kembali</span>
        <h1 style="font-size:40px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:16px 0; line-height:1.15; letter-spacing:-1.5px;">
            Lanjutkan perjalanan belajarmu.
        </h1>
        <p style="color:var(--muted); font-size:15px; line-height:1.6; margin-bottom:28px;">
            Masuk untuk mengakses materi kuis interaktif, melacak riwayat skor, serta berbagi gagasan di forum diskusi energi terbarukan.
        </p>

        <div style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:18px 20px; font-size:13px;">
            <strong style="display:block; color:var(--deep-green); margin-bottom:6px;">Akun Uji Coba (Demo):</strong>
            <div style="color:var(--muted); line-height:1.5;">
                <div><span style="font-weight:600; color:var(--ink);">Admin:</span> <code>admin@sdg7learn.com</code> | Pass: <code>admin123</code></div>
                <div><span style="font-weight:600; color:var(--ink);">User:</span> <code>cristian@gmail.com</code> | Pass: <code>password123</code></div>
            </div>
        </div>
    </div>

    <div class="auth-card" style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow);">
        <h2 style="font-size:24px; font-weight:800; margin:0 0 6px; color:var(--deep-green); font-family:'Manrope',sans-serif;">Masuk</h2>
        <p style="font-size:13px; color:var(--muted); margin:0 0 24px;">Masukkan kredensial akun Anda di bawah ini.</p>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div style="margin-bottom:18px;">
                <label for="email" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    placeholder="nama@email.com"
                    style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none; transition:border-color .2s;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
            </div>

            <div style="margin-bottom:20px;">
                <label for="password" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Password</label>
                <input type="password" name="password" id="password" required
                    placeholder="••••••••"
                    style="width:100%; height:46px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none; transition:border-color .2s;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
            </div>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; font-size:13px;">
                <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer; color:var(--muted);">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="button button-primary button-block" style="height:48px; font-size:14px;">
                Masuk ke Akun &rarr;
            </button>
        </form>

        <div style="margin-top:24px; text-align:center; font-size:13px; color:var(--muted);">
            Belum punya akun? <a href="{{ route('register') }}" style="color:var(--green); font-weight:700; text-decoration:none;">Daftar gratis di sini</a>
        </div>
    </div>
</div>
@endsection
