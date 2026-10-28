<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model Admin mewakili entitas Admin pada ERD:
 * - Atribut: ID, Nama, Password (dan email untuk auth Laravel)
 * - Relasi:
 *   1. Membuat dan Mengelola artikel -> hasMany(Artikel::class)
 *   2. Membuat Quiz -> hasMany(Quiz::class)
 *   3. Membuat mengelola forum -> hasMany(Forum::class)
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin';

    protected $fillable = [
        'nama',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /** Relasi: Admin Membuat dan Mengelola artikel */
    public function artikels()
    {
        return $this->hasMany(Artikel::class, 'admin_id');
    }

    /** Relasi: Admin Membuat Quiz */
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'admin_id');
    }

    /** Relasi: Admin Membuat mengelola forum */
    public function forums()
    {
        return $this->hasMany(Forum::class, 'admin_id');
    }

    /** Relasi: Admin Menanggapi Call Center */
    public function callCenters()
    {
        return $this->hasMany(CallCenter::class, 'admin_id');
    }
}
