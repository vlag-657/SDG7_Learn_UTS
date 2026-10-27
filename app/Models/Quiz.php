<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Quiz mewakili entitas Quiz pada ERD:
 * - Atribut: ID, isi, Poin (dan judul/deskripsi untuk tampilan)
 * - Relasi:
 *   1. Dibuat oleh Admin -> belongsTo(Admin::class)
 *   2. Dikerjakan oleh User (Menampilkan skor) -> belongsToMany(User::class, 'hasil_kuis')
 */
class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quiz';

    protected $fillable = [
        'admin_id',
        'judul',
        'isi',
        'poin',
        'deskripsi',
    ];

    /** Relasi: Admin Membuat Quiz */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /** Butir pertanyaan dalam kuis */
    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'quiz_id')->orderBy('urutan');
    }

    /** Relasi ke hasil kuis */
    public function hasil()
    {
        return $this->hasMany(HasilKuis::class, 'quiz_id');
    }

    /** Relasi: User Mengerjakan dan Menampilkan skor */
    public function users()
    {
        return $this->belongsToMany(User::class, 'hasil_kuis', 'quiz_id', 'user_id')
            ->withPivot(['skor', 'persentase', 'tinjauan'])
            ->withTimestamps();
    }
}
