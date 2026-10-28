@extends('layouts.app')

@section('title', 'Sunting Pesan Call Center #' . $callCenter->id)

@section('content')
<div style="max-width:800px; margin:0 auto;">
    <div style="margin-bottom:24px;">
        <a href="{{ route('call-center.show', $callCenter->id) }}" style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:var(--muted); text-decoration:none; margin-bottom:16px;">
            &larr; Kembali ke Detail Tiket #CC-{{ str_pad($callCenter->id, 4, '0', STR_PAD_LEFT) }}
        </a>
        <span class="eyebrow" style="color:#d97706;"><span class="eyebrow-dot" style="background:#d97706;"></span> Sunting Tiket</span>
        <h1 style="font-size:32px; font-weight:800; font-family:'Manrope',sans-serif; color:var(--deep-green); margin:8px 0 4px; letter-spacing:-1px;">
            Sunting Pesan Call Center
        </h1>
        <p style="color:var(--muted); font-size:14px; margin:0;">
            Perbarui informasi atau rincian pesan pengaduan Anda sebelum diproses oleh admin.
        </p>
    </div>

    <div style="background:#fff; border:1px solid var(--line); border-radius:24px; padding:36px; box-shadow:var(--shadow);">
        <form action="{{ route('call-center.update', $callCenter->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nomor Telepon / WhatsApp -->
            <div style="margin-bottom:20px;">
                <label for="nomor_telepon" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                    Nomor Telepon / WhatsApp <span style="font-size:12px; font-weight:500; color:var(--muted);">(Opsional)</span>
                </label>
                <input type="tel" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $callCenter->nomor_telepon) }}"
                    placeholder="Contoh: 081234567890"
                    style="width:100%; height:44px; border:1px solid {{ ($errors ?? null)?->has('nomor_telepon') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('nomor_telepon') ? '#ef4444' : 'var(--line)' }}'">
                @error('nomor_telepon')
                    <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
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
                    <option value="pertanyaan" {{ old('kategori', $callCenter->kategori) === 'pertanyaan' ? 'selected' : '' }}>Pertanyaan Materi Edukasi</option>
                    <option value="kendala_kuis" {{ old('kategori', $callCenter->kategori) === 'kendala_kuis' ? 'selected' : '' }}>Kendala Pengerjaan Kuis / Ujian</option>
                    <option value="masalah_teknis" {{ old('kategori', $callCenter->kategori) === 'masalah_teknis' ? 'selected' : '' }}>Masalah Teknis & Akun Pengguna</option>
                    <option value="saran" {{ old('kategori', $callCenter->kategori) === 'saran' ? 'selected' : '' }}>Kritik, Saran & Usulan Materi</option>
                    <option value="lainnya" {{ old('kategori', $callCenter->kategori) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                        <input type="radio" name="prioritas" value="rendah" {{ old('prioritas', $callCenter->prioritas) === 'rendah' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
                        <div class="priority-box" style="border:1px solid #cbd5e1; border-radius:12px; padding:10px 8px; text-align:center; transition:all 0.15s ease;">
                            <span style="display:block; font-size:12px; font-weight:700; color:#475569;">Rendah</span>
                            <span style="font-size:10px; color:var(--muted);">Santai</span>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" name="prioritas" value="sedang" {{ old('prioritas', $callCenter->prioritas) === 'sedang' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
                        <div class="priority-box" style="border:1px solid #fef08a; border-radius:12px; padding:10px 8px; text-align:center; transition:all 0.15s ease;">
                            <span style="display:block; font-size:12px; font-weight:700; color:#854d0e;">Sedang</span>
                            <span style="font-size:10px; color:var(--muted);">Normal</span>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" name="prioritas" value="tinggi" {{ old('prioritas', $callCenter->prioritas) === 'tinggi' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
                        <div class="priority-box" style="border:1px solid #fed7aa; border-radius:12px; padding:10px 8px; text-align:center; transition:all 0.15s ease;">
                            <span style="display:block; font-size:12px; font-weight:700; color:#9a3412;">Tinggi</span>
                            <span style="font-size:10px; color:var(--muted);">Mendesak</span>
                        </div>
                    </label>

                    <label style="cursor:pointer;">
                        <input type="radio" name="prioritas" value="darurat" {{ old('prioritas', $callCenter->prioritas) === 'darurat' ? 'checked' : '' }} style="display:none;" onchange="updateRadioSelection(this)">
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
                <input type="text" name="subjek" id="subjek" value="{{ old('subjek', $callCenter->subjek) }}" required
                    style="width:100%; height:44px; border:1px solid {{ ($errors ?? null)?->has('subjek') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:0 14px; font-size:14px; outline:none;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('subjek') ? '#ef4444' : 'var(--line)' }}'">
                @error('subjek')
                    <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Rincian Pesan -->
            <div style="margin-bottom:28px;">
                <label for="pesan" style="display:block; font-size:14px; font-weight:700; margin-bottom:6px; color:var(--ink);">
                    Rincian Pesan / Deskripsi Kendala <span style="color:#ef4444;">*</span>
                </label>
                <textarea name="pesan" id="pesan" rows="6" required
                    style="width:100%; border:1px solid {{ ($errors ?? null)?->has('pesan') ? '#ef4444' : 'var(--line)' }}; border-radius:12px; padding:14px; font-size:14px; font-family:inherit; outline:none; line-height:1.6;"
                    onfocus="this.style.borderColor='var(--green)'" onblur="this.style.borderColor='{{ ($errors ?? null)?->has('pesan') ? '#ef4444' : 'var(--line)' }}'">{{ old('pesan', $callCenter->pesan) }}</textarea>
                @error('pesan')
                    <span style="display:block; font-size:12px; color:#ef4444; margin-top:4px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--line); padding-top:20px;">
                <a href="{{ route('call-center.show', $callCenter->id) }}" class="button button-quiet">
                    Batal
                </a>
                <button type="submit" class="button button-primary" style="height:46px; padding:0 26px; font-size:14px;">
                    Simpan Perubahan &rarr;
                </button>
            </div>
        </form>
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
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="prioritas"]:checked');
    if (checked) updateRadioSelection(checked);
});
</script>
@endpush
@endsection
