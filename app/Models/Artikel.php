<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Artikel mewakili entitas Artikel pada ERD:
 * - Atribut: ID, ISI, Tanggal, Sumber (dan judul/gambar_url untuk visual)
 * - Relasi:
 *   1. Admin Membuat dan Mengelola artikel -> belongsTo(Admin::class)
 *   2. User Membaca Artikel -> diakses publik/user
 */
class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikel';

    protected $fillable = [
        'admin_id',
        'judul',
        'isi',
        'sumber',
        'tanggal',
        'gambar_url',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /** Relasi: Admin Membuat dan Mengelola artikel */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
