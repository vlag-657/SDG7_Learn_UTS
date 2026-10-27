<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model User mewakili entitas User pada ERD:
 * - Atribut: ID, Nama, Pekerjaan, Usia, Password, ID_Forum (dan email untuk auth)
 * - Relasi:
 *   1. Berdiskusi di Forum -> hasMany(Forum::class) & belongsTo(Forum::class, 'id_forum')
 *   2. Membaca Artikel -> akses konten artikel
 *   3. Mengerjakan dan Menampilkan skor -> hasMany(HasilKuis::class) & belongsToMany(Quiz::class)
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'pekerjaan',
        'usia',
        'id_forum', // Atribut ID_Forum pada ERD
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /** Atribut ID_Forum pada ERD (referensi forum utama/aktif user) */
    public function forumUtama()
    {
        return $this->belongsTo(Forum::class, 'id_forum');
    }

    /** Relasi: User Berdiskusi di Forum */
    public function forums()
    {
        return $this->hasMany(Forum::class, 'user_id');
    }

    public function postingan()
    {
        return $this->hasMany(Postingan::class, 'user_id');
    }

    public function balasan()
    {
        return $this->hasMany(Balasan::class, 'user_id');
    }

    /** Relasi: User Mengerjakan dan Menampilkan skor */
    public function hasilKuis()
    {
        return $this->hasMany(HasilKuis::class, 'user_id');
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'hasil_kuis', 'user_id', 'quiz_id')
            ->withPivot(['skor', 'persentase', 'tinjauan'])
            ->withTimestamps();
    }
}