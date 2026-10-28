@extends('layouts.app')

@section('title', 'Tanggapi Tiket #' . $callCenter->id . ' — ' . $callCenter->subjek)

@section('content')
<div style="max-width:900px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('admin.call-center.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Panel Call Center Admin
        </a>
        <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:16px;">
            <div>
                <span class="eyebrow" style="color:#d97706;"><span class="eyebrow-dot" style="background:#d97706;"></span> Panel Respon Administrator</span>
                <h1 style="font-size:28px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-0.5px;">
                    {{ $callCenter->subjek }}
                </h1>
                <p style="color:var(--muted); font-size:13px; margin:0;">
                    Tiket #CC-{{ str_pad($callCenter->id, 4, '0', STR_PAD_LEFT) }} &bull; Diterima pada {{ $callCenter->created_at->format('d F Y, H:i') }} WIB &bull; Kategori: <strong>{{ $callCenter->kategori_label }}</strong>
                </p>
            </div>

            <!-- Badge Status & Prioritas -->
            <div style="display:flex; gap:8px; align-items:center;">
                @if($callCenter->prioritas === 'darurat')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:800; background:#fee2e2; color:#991b1b; border:1px solid #fecaca; text-transform:uppercase;">
                        ⚡ Darurat
                    </span>
                @elseif($callCenter->prioritas === 'tinggi')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:800; background:#ffedd5; color:#9a3412; border:1px solid #fed7aa; text-transform:uppercase;">
                        ▲ Tinggi
                    </span>
                @elseif($callCenter->prioritas === 'sedang')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#fef9c3; color:#854d0e; border:1px solid #fef08a;">
                        ● Sedang
                    </span>
                @else
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:600; background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;">
                        ▼ Rendah
                    </span>
                @endif

                @if($callCenter->status === 'menunggu')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#fef3c7; color:#92400e; border:1px solid #fde68a;">
                        Menunggu Respon
                    </span>
                @elseif($callCenter->status === 'diproses')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd;">
                        Sedang Diproses
                    </span>
                @elseif($callCenter->status === 'selesai')
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; background:#dcfce7; color:#15803d; border:1px solid #bbf7d0;">
                        ✓ Selesai
                    </span>
                @endif

                @if($callCenter->kecepatan_penanganan)
                    <span style="padding:6px 14px; border-radius:20px; font-size:12px; font-weight:700; {{ $callCenter->kecepatan_penanganan_badge_style }}">
                        {{ $callCenter->kecepatan_penanganan_icon }} Penanganan: {{ $callCenter->kecepatan_penanganan_label }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Kotak Rincian Pesan Pengguna -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); margin-bottom:28px;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid var(--line); padding-bottom:16px; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#e8f5ed; color:var(--green); font-weight:800; display:flex; align-items:center; justify-content:center; font-size:15px;">
                    {{ strtoupper(substr($callCenter->nama, 0, 2)) }}
                </div>
                <div>
                    <strong style="color:var(--ink); font-size:15px; display:block;">{{ $callCenter->nama }}</strong>
                    <span style="color:var(--muted); font-size:12px;">{{ $callCenter->email }}</span>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:8px;">
                @if($callCenter->nomor_telepon)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $callCenter->nomor_telepon) }}" target="_blank" 
                       style="font-size:12px; font-weight:700; color:#15803d; background:#eaf8ee; border:1px solid #c2ebd0; padding:6px 12px; border-radius:10px; text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                        <span>💬 Chat WhatsApp:</span>
                        <strong>{{ $callCenter->nomor_telepon }}</strong>
                    </a>
                @endif

                @if($callCenter->user_id)
                    <a href="{{ route('admin.pengguna.show', $callCenter->user_id) }}" class="button button-quiet button-small" style="font-size:12px;">
                        Lihat Profil Akun &rarr;
                    </a>
                @endif
            </div>
        </div>

        <div style="font-size:15px; line-height:1.7; color:var(--ink); white-space:pre-line;">
            {{ $callCenter->pesan }}
        </div>
    </div>

    <!-- Form Tindak Lanjut & Tanggapan Admin -->
    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow); margin-bottom:32px;">
        <h2 style="font-size:18px; font-weight:800; color:var(--deep-green); margin:0 0 8px; display:flex; align-items:center; gap:8px;">
            <span>✍️</span> Tindak Lanjut & Respon Administrator
        </h2>
        <p style="color:var(--muted); font-size:13px; margin:0 0 24px;">
            Berikan tanggapan resmi atau solusi kendala. Tanggapan ini akan langsung dapat dibaca oleh pengguna di halaman detail tiket mereka.
        </p>

        <form action="{{ route('admin.call-center.update', $callCenter->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:20px; margin-bottom:20px;">
                <!-- Pilihan Status Tiket -->
                <div>
                    <label for="status" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                        Status Penanganan Tiket <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="status" id="status" required
                        style="width:100%; height:44px; border:1px solid {{ ($errors ?? null)?->has('status') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:0 14px; font-size:14px; background:#fff; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('status') ? '#ef4444' : 'var(--line)' }}'">
                        <option value="menunggu" {{ old('status', $callCenter->status) === 'menunggu' ? 'selected' : '' }}>⏳ Menunggu Respon</option>
                        <option value="diproses" {{ old('status', $callCenter->status) === 'diproses' ? 'selected' : '' }}>⚙️ Sedang Diproses (Dalam Investigasi / Pengerjaan)</option>
                        <option value="selesai" {{ old('status', $callCenter->status) === 'selesai' ? 'selected' : '' }}>✅ Selesai (Solusi Telah Diberikan)</option>
                    </select>
                    @error('status')
                        <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Pilihan Kecepatan Penanganan -->
                <div>
                    <label for="kecepatan_penanganan" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                        Kecepatan Penanganan <span style="font-weight:400; color:var(--muted); font-size:12px;">(Pilihan Tingkat Respon)</span>
                    </label>
                    <select name="kecepatan_penanganan" id="kecepatan_penanganan"
                        style="width:100%; height:44px; border:1px solid {{ ($errors ?? null)?->has('kecepatan_penanganan') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:0 14px; font-size:14px; background:#fff; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('kecepatan_penanganan') ? '#ef4444' : 'var(--line)' }}'">
                        <option value="">-- Pilih Kecepatan Penanganan --</option>
                        <option value="sangat_cepat" {{ old('kecepatan_penanganan', $callCenter->kecepatan_penanganan) === 'sangat_cepat' ? 'selected' : '' }}>⚡ Sangat Cepat</option>
                        <option value="sedang" {{ old('kecepatan_penanganan', $callCenter->kecepatan_penanganan) === 'sedang' ? 'selected' : '' }}>⏱️ Sedang</option>
                        <option value="biasa" {{ old('kecepatan_penanganan', $callCenter->kecepatan_penanganan) === 'biasa' ? 'selected' : '' }}>📋 Biasa</option>
                    </select>
                    @error('kecepatan_penanganan')
                        <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Teks Balasan Tanggapan Admin -->
            <div style="margin-bottom:24px;">
                <label for="tanggapan" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                    Isi Tanggapan / Solusi Resmi
                </label>
                <textarea name="tanggapan" id="tanggapan" rows="6"
                    placeholder="Tuliskan respon resmi, klarifikasi masalah, atau instruksi langkah penanganan untuk pengguna..."
                    style="width:100%; border:1px solid {{ ($errors ?? null)?->has('tanggapan') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.6;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('tanggapan') ? '#ef4444' : 'var(--line)' }}'">{{ old('tanggapan', $callCenter->tanggapan) }}</textarea>
                @error('tanggapan')
                    <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                @else
                    <span style="display:block; font-size:12px; color:var(--muted); margin-top:4px;">
                        Tanggapan dapat diperbarui kapan saja seiring perkembangan investigasi kendala.
                    </span>
                @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--line); padding-top:20px;">
                <div style="font-size:12px; color:var(--muted);">
                    @if($callCenter->ditanggapi_pada)
                        Terakhir diperbarui: {{ $callCenter->ditanggapi_pada->format('d M Y, H:i') }} WIB
                    @endif
                </div>
                <button type="submit" class="button button-primary" style="height:46px; padding:0 26px; font-size:14px;">
                    Simpan Tanggapan & Perbarui Status &rarr;
                </button>
            </div>
        </form>
    </div>

    <!-- Tombol Navigasi Bawah & Hapus -->
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <a href="{{ route('admin.call-center.index') }}" class="button button-quiet">
            &larr; Kembali ke Daftar Call Center
        </a>

        <form action="{{ route('admin.call-center.destroy', $callCenter->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket Call Center ini dari sistem?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="button button-danger">
                Hapus Tiket dari Sistem
            </button>
        </form>
    </div>
</div>
@endsection
