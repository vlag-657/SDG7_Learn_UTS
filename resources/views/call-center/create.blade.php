@extends('layouts.app')

@section('title', 'Kirim Pesan Call Center')

@section('content')
<div style="max-width:960px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('call-center.index') }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Daftar Tiket Call Center
        </a>
        <span class="eyebrow"><span class="eyebrow-dot"></span> Bantuan Langsung</span>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Formulir Pesan Pengaduan & Bantuan
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Sampaikan kendala, pertanyaan materi edukasi, atau masukan Anda. Tim administrator kami siap membantu.
        </p>
    </div>

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:28px; align-items:start;">
        <!-- Formulir Utama -->
        <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:32px; box-shadow:var(--shadow);">
            <form action="{{ route('call-center.store') }}" method="POST">
                @csrf

                <!-- Informasi Pengirim (Identitas Akun) -->
                <div style="background:#f7f9f6; border:1px solid var(--line); border-radius:14px; padding:14px 18px; margin-bottom:22px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
                    <div>
                        <span style="font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase;">Identitas Pengirim:</span>
                        <div style="font-size:14px; font-weight:700; color:var(--ink);">{{ Auth::user()->nama }}</div>
                        <div style="font-size:12px; color:var(--muted);">{{ Auth::user()->email }}</div>
                    </div>
                    <span style="font-size:11px; font-weight:700; color:var(--green); background:#eaf8ee; padding:4px 10px; border-radius:20px; border:1px solid #c2ebd0;">
                        ✓ Akun Terverifikasi
                    </span>
                </div>

                <!-- Nomor Telepon / WhatsApp -->
                <div style="margin-bottom:20px;">
                    <label for="nomor_telepon" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                        Nomor Telepon / WhatsApp <span style="font-size:12px; font-weight:500; color:var(--muted);">(Opsional)</span>
                    </label>
                    <input type="tel" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}"
                        placeholder="Contoh: 081234567890"
                        style="width:100%; height:44px; border:1px solid {{ ($errors ?? null)?->has('nomor_telepon') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('nomor_telepon') ? '#ef4444' : 'var(--line)' }}'">
                    @error('nomor_telepon')
                        <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                    @else
                        <span style="display:block; font-size:12px; color:var(--muted); margin-top:4px;">Dapat dihubungi jika admin memerlukan konfirmasi cepat.</span>
                    @enderror
                </div>

                <!-- Kategori Pesan -->
                <div style="margin-bottom:20px;">
                    <label for="kategori" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                        Kategori Pengaduan / Pesan <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="kategori" id="kategori" required
                        style="width:100%; height:44px; border:1px solid {{ ($errors ?? null)?->has('kategori') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:0 14px; font-size:14px; background:#fff; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('kategori') ? '#ef4444' : 'var(--line)' }}'">
                        <option value="">-- Pilih Kategori Masalah --</option>
                        <option value="pertanyaan" {{ old('kategori') === 'pertanyaan' ? 'selected' : '' }}>Pertanyaan Materi Edukasi</option>
                        <option value="kendala_kuis" {{ old('kategori') === 'kendala_kuis' ? 'selected' : '' }}>Kendala Pengerjaan Kuis / Ujian</option>
                        <option value="masalah_teknis" {{ old('kategori') === 'masalah_teknis' ? 'selected' : '' }}>Masalah Teknis & Akun Pengguna</option>
                        <option value="saran" {{ old('kategori') === 'saran' ? 'selected' : '' }}>Kritik, Saran & Usulan Materi</option>
                        <option value="lainnya" {{ old('kategori') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('kategori')
                        <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tingkat Prioritas (Radio Cards) -->
                <div style="margin-bottom:22px;">
                    <label style="display:block; font-size:14px; font-weight:700; margin-bottom:8px; color:var(--ink);">
                        Tingkat Prioritas <span style="color:#ef4444;">*</span>
                    </label>
                    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:10px;">
                        <label style="cursor:pointer;">
                            <input type="radio" name="prioritas" value="rendah" {{ old('prioritas') === 'rendah' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
                            <div class="priority-box" style="border:1px solid #cbd5e1; border-radius:12px; padding:10px 8px; text-align:center; transition:all 0.15s ease;">
                                <span style="display:block; font-size:12px; font-weight:700; color:#475569;">Rendah</span>
                                <span style="font-size:10px; color:var(--muted);">Santai</span>
                            </div>
                        </label>

                        <label style="cursor:pointer;">
                            <input type="radio" name="prioritas" value="sedang" {{ old('prioritas', 'sedang') === 'sedang' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
                            <div class="priority-box" style="border:1px solid #fef08a; background:#fefce8; border-radius:12px; padding:10px 8px; text-align:center; transition:all 0.15s ease;">
                                <span style="display:block; font-size:12px; font-weight:700; color:#854d0e;">Sedang</span>
                                <span style="font-size:10px; color:var(--muted);">Normal</span>
                            </div>
                        </label>

                        <label style="cursor:pointer;">
                            <input type="radio" name="prioritas" value="tinggi" {{ old('prioritas') === 'tinggi' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
                            <div class="priority-box" style="border:1px solid #fed7aa; border-radius:12px; padding:10px 8px; text-align:center; transition:all 0.15s ease;">
                                <span style="display:block; font-size:12px; font-weight:700; color:#9a3412;">Tinggi</span>
                                <span style="font-size:10px; color:var(--muted);">Mendesak</span>
                            </div>
                        </label>

                        <label style="cursor:pointer;">
                            <input type="radio" name="prioritas" value="darurat" {{ old('prioritas') === 'darurat' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
                            <div class="priority-box" style="border:1px solid #fecaca; border-radius:12px; padding:10px 8px; text-align:center; transition:all 0.15s ease;">
                                <span style="display:block; font-size:12px; font-weight:800; color:#991b1b;">⚡ Darurat</span>
                                <span style="font-size:10px; color:var(--muted);">Kritis</span>
                            </div>
                        </label>
                    </div>
                    @error('prioritas')
                        <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Subjek / Topik Masalah -->
                <div style="margin-bottom:20px;">
                    <label for="subjek" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                        Subjek Pesan <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="subjek" id="subjek" value="{{ old('subjek') }}" required
                        placeholder="Contoh: Skor Kuis Tidak Muncul Setelah Selesai Mengerjakan"
                        style="width:100%; height:44px; border:1px solid {{ ($errors ?? null)?->has('subjek') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('subjek') ? '#ef4444' : 'var(--line)' }}'">
                    @error('subjek')
                        <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                    @else
                        <span style="display:block; font-size:12px; color:var(--muted); margin-top:4px;">Tuliskan intisari masalah secara singkat (5 - 150 karakter).</span>
                    @enderror
                </div>

                <!-- Rincian Pesan -->
                <div style="margin-bottom:28px;">
                    <label for="pesan" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                        Rincian Pesan / Deskripsi Kendala <span style="color:#ef4444;">*</span>
                    </label>
                    <textarea name="pesan" id="pesan" rows="6" required
                        placeholder="Jelaskan kendala, pertanyaan, atau pesan Anda sedetail mungkin agar admin dapat memberikan solusi yang tepat..."
                        style="width:100%; border:1px solid {{ ($errors ?? null)?->has('pesan') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.6;"
                        onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('pesan') ? '#ef4444' : 'var(--line)' }}'">{{ old('pesan') }}</textarea>
                    @error('pesan')
                        <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                    @else
                        <span style="display:block; font-size:12px; color:var(--muted); margin-top:4px;">Minimal 10 karakter. Jelaskan kronologi atau konteks masalah.</span>
                    @enderror
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--line); padding-top:20px;">
                    <a href="{{ route('call-center.index') }}" class="button button-quiet">
                        Batal
                    </a>
                    <button type="submit" class="button button-primary" style="height:46px; padding:0 26px; font-size:14px;">
                        Kirim Pesan ke Admin &rarr;
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar Informasi Layanan -->
        <div style="display:flex; flex-direction:column; gap:20px;">
            <div style="background:#fff; border:1px solid var(--line); border-radius:20px; padding:22px; box-shadow:var(--shadow);">
                <h3 style="font-size:16px; font-weight:800; color:var(--deep-green); margin:0 0 12px; display:flex; align-items:center; gap:8px;">
                    <span>ℹ️</span> Panduan Laporan
                </h3>
                <ul style="margin:0; padding-left:18px; font-size:13px; color:var(--ink); line-height:1.7;">
                    <li>Pilih kategori yang paling sesuai dengan kendala Anda.</li>
                    <li>Sertakan nama kuis atau judul artikel jika berkaitan dengan konten tertentu.</li>
                    <li>Admin kami akan meninjau dan membalas tiket Anda dalam kurun waktu 1x24 jam kerja.</li>
                    <li>Anda dapat memantau status balasan tiket di halaman Call Center.</li>
                </ul>
            </div>

            <div style="background:#eaf8ee; border:1px solid #c2ebd0; border-radius:20px; padding:22px;">
                <h4 style="font-size:14px; font-weight:800; color:#186337; margin:0 0 8px;">
                    Butuh Solusi Lebih Cepat?
                </h4>
                <p style="font-size:12px; color:#166534; margin:0 0 12px; line-height:1.6;">
                    Untuk kendala mendesak, silakan gunakan hotline darurat atau hubungi tim teknis via WhatsApp:
                </p>
                <div style="font-size:14px; font-weight:800; color:#186337;">
                    💬 +62 812-3456-7890
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateRadioSelection(radio) {
    document.querySelectorAll('.priority-box').forEach(box => {
        box.style.border = '1px solid #cbd5e1';
        box.style.background = 'transparent';
    });
    const box = radio.nextElementSibling;
    if (radio.value === 'rendah') {
        box.style.border = '2px solid #64748b';
        box.style.background = '#f1f5f9';
    } else if (radio.value === 'sedang') {
        box.style.border = '2px solid #ca8a04';
        box.style.background = '#fefce8';
    } else if (radio.value === 'tinggi') {
        box.style.border = '2px solid #ea580c';
        box.style.background = '#ffedd5';
    } else if (radio.value === 'darurat') {
        box.style.border = '2px solid #dc2626';
        box.style.background = '#fee2e2';
    }
}
// Init selected radio style on load
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="prioritas"]:checked');
    if (checked) updateRadioSelection(checked);
});
</script>
@endpush
@endsection
