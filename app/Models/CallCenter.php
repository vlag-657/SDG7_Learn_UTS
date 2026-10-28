<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model CallCenter mewakili pesan/layanan bantuan pengguna ke Admin.
 */
class CallCenter extends Model
{
    use HasFactory;

    protected $table = 'call_center';

    protected $fillable = [
        'user_id',
        'admin_id',
        'nama',
        'email',
        'nomor_telepon',
        'kategori',
        'prioritas',
        'subjek',
        'pesan',
        'status',
        'tanggapan',
        'ditanggapi_pada',
        'kecepatan_penanganan',
    ];

    protected function casts(): array
    {
        return [
            'ditanggapi_pada' => 'datetime',
        ];
    }

    /**
     * Relasi ke pengirim pesan (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke admin yang menanggapi pesan (Admin)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Label kategori yang ramah pengguna
     */
    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'pertanyaan' => 'Pertanyaan Materi',
            'kendala_kuis' => 'Kendala Kuis',
            'masalah_teknis' => 'Masalah Teknis & Akun',
            'saran' => 'Kritik & Saran',
            default => 'Lainnya',
        };
    }

    /**
     * Label status bahasa Indonesia
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'Menunggu Respon',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            default => ucfirst($this->status),
        };
    }

    /**
     * Warna & styling badge status
     */
    public function getStatusBadgeStyleAttribute(): string
    {
        return match ($this->status) {
            'menunggu' => 'background:#fef3c7; color:#92400e; border:1px solid #fde68a;',
            'diproses' => 'background:#e0f2fe; color:#075985; border:1px solid #bae6fd;',
            'selesai' => 'background:#dcfce7; color:#166534; border:1px solid #bbf7d0;',
            default => 'background:#f3f4f6; color:#374151; border:1px solid #e5e7eb;',
        };
    }

    /**
     * Warna & styling badge prioritas
     */
    public function getPrioritasBadgeStyleAttribute(): string
    {
        return match ($this->prioritas) {
            'rendah' => 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;',
            'sedang' => 'background:#fef9c3; color:#854d0e; border:1px solid #fef08a;',
            'tinggi' => 'background:#ffedd5; color:#9a3412; border:1px solid #fed7aa;',
            'darurat' => 'background:#fee2e2; color:#991b1b; border:1px solid #fecaca;',
            default => 'background:#f3f4f6; color:#4b5563; border:1px solid #e5e7eb;',
        };
    }

    /**
     * Label kecepatan penanganan admin
     */
    public function getKecepatanPenangananLabelAttribute(): ?string
    {
        return match ($this->kecepatan_penanganan) {
            'sangat_cepat' => 'Sangat Cepat',
            'sedang' => 'Sedang',
            'biasa' => 'Biasa',
            default => $this->kecepatan_penanganan ? ucfirst($this->kecepatan_penanganan) : null,
        };
    }

    /**
     * Warna & styling badge kecepatan penanganan
     */
    public function getKecepatanPenangananBadgeStyleAttribute(): string
    {
        return match ($this->kecepatan_penanganan) {
            'sangat_cepat' => 'background:#dcfce7; color:#166534; border:1px solid #86efac;',
            'sedang' => 'background:#e0f2fe; color:#075985; border:1px solid #7dd3fc;',
            'biasa' => 'background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;',
            default => 'background:#f3f4f6; color:#4b5563; border:1px solid #e5e7eb;',
        };
    }

    /**
     * Ikon kecepatan penanganan
     */
    public function getKecepatanPenangananIconAttribute(): string
    {
        return match ($this->kecepatan_penanganan) {
            'sangat_cepat' => '⚡',
            'sedang' => '⏱️',
            'biasa' => '📋',
            default => '🏷️',
        };
    }
}
