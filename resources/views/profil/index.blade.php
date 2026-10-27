@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="page-heading" style="margin-bottom:32px;">
    <span class="eyebrow"><span class="eyebrow-dot"></span> Pengaturan Akun</span>
    <h1 style="font-size:36px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
        Profil Saya
    </h1>
    <p style="color:var(--muted); font-size:14px; margin:0;">
        Kelola informasi profil pribadi dan keamanan kata sandi Anda.
    </p>
</div>

<div class="profile-layout" style="display:grid; grid-template-columns:1fr 2fr; gap:32px; align-items:start; margin-bottom:48px;">
    <!-- Kolom Kiri: Ringkasan Profil Card -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); text-align:center;">
        <div style="width:80px; height:80px; border-radius:50%; background:var(--lime); color:var(--deep-green); font-size:28px; font-weight:800; display:grid; place-items:center; margin:0 auto 16px;">
            {{ strtoupper(substr($user->nama, 0, 2)) }}
        </div>
        <h2 style="font-size:20px; font-weight:800; color:var(--deep-green); margin:0 0 4px;">{{ $user->nama }}</h2>
        <p style="font-size:13px; color:var(--muted); margin:0 0 12px;">{{ $user->email }}</p>

        <span style="display:inline-block; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.8px; padding:4px 12px; border-radius:99px; margin-bottom:20px; background:{{ $user->role === 'admin' ? '#fef3c7' : '#e6f4ea' }}; color:{{ $user->role === 'admin' ? '#92400e' : '#137333' }};">
            Peran: {{ strtoupper($user->role) }}
        </span>

        <div style="border-top:1px solid var(--line); padding-top:18px; text-align:left; font-size:13px; line-height:1.8; color:var(--ink);">
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--muted);">Pekerjaan:</span>
                <strong>{{ $user->pekerjaan ?? '-' }}</strong>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--muted);">Usia:</span>
                <strong>{{ $user->usia ? $user->usia . ' Tahun' : '-' }}</strong>
            </div>
            <div style="display:flex; justify-content:space-between;">
                <span style="color:var(--muted);">Bergabung:</span>
                <strong>{{ $user->created_at->format('d M Y') }}</strong>
            </div>
        </div>

        <div style="border-top:1px solid var(--line); margin-top:18px; padding-top:18px; display:grid; grid-template-columns:repeat(3, 1fr); gap:8px; text-align:center;">
            <div style="background:#f7f9f6; padding:10px 4px; border-radius:10px;">
                <strong style="display:block; font-size:18px; color:var(--deep-green);">{{ $user->hasil_kuis_count ?? 0 }}</strong>
                <span style="font-size:10px; color:var(--muted);">Kuis</span>
            </div>
            <div style="background:#f7f9f6; padding:10px 4px; border-radius:10px;">
                <strong style="display:block; font-size:18px; color:var(--deep-green);">{{ $user->postingan_count ?? 0 }}</strong>
                <span style="font-size:10px; color:var(--muted);">Thread</span>
            </div>
            <div style="background:#f7f9f6; padding:10px 4px; border-radius:10px;">
                <strong style="display:block; font-size:18px; color:var(--deep-green);">{{ $user->balasan_count ?? 0 }}</strong>
                <span style="font-size:10px; color:var(--muted);">Balasan</span>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Formulir Update Data & Password -->
    <div style="display:flex; flex-direction:column; gap:28px;">
        <!-- Form Update Data Diri -->
        <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow);">
            <h2 style="font-size:20px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; margin:0 0 6px;">Ubah Data Diri</h2>
            <p style="font-size:13px; color:var(--muted); margin:0 0 20px;">Perbarui informasi identitas akun Anda.</p>

            <form action="{{ route('profil.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom:16px;">
                    <label for="nama" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Nama Lengkap *</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required
                        style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                    <div>
                        <label for="pekerjaan" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan', $user->pekerjaan) }}"
                            style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                            onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                    </div>
                    <div>
                        <label for="usia" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Usia</label>
                        <input type="number" name="usia" id="usia" value="{{ old('usia', $user->usia) }}" min="10" max="100"
                            style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                            onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" class="button button-primary" style="height:44px; padding:0 24px; font-size:13px;">
                        Simpan Perubahan Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Form Ganti Password -->
        <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow);">
            <h2 style="font-size:20px; font-weight:800; color:var(--deep-green); font-family:'Manrope',sans-serif; margin:0 0 6px;">Keamanan Kata Sandi</h2>
            <p style="font-size:13px; color:var(--muted); margin:0 0 20px;">Perbarui password akun untuk menjaga keamanan.</p>

            <form action="{{ route('profil.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom:16px;">
                    <label for="password_lama" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Password Saat Ini *</label>
                    <input type="password" name="password_lama" id="password_lama" required
                        placeholder="••••••••"
                        style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                    <div>
                        <label for="password_baru" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Password Baru *</label>
                        <input type="password" name="password_baru" id="password_baru" required
                            placeholder="Minimal 8 karakter"
                            style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                            onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                    </div>
                    <div>
                        <label for="password_baru_confirmation" style="display:block; font-size:13px; font-weight:700; margin-bottom:6px; color:var(--ink);">Ulangi Password Baru *</label>
                        <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" required
                            placeholder="Ulangi password baru"
                            style="width:100%; height:44px; border:1px solid var(--line); border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                            onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='var(--line)'">
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end;">
                    <button type="submit" class="button button-quiet" style="height:44px; padding:0 24px; font-size:13px; font-weight:700;">
                        Ganti Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
