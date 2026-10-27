<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Forum mewakili entitas Forum pada ERD:
 * - Atribut: ID, Isi, Tanggal (dan judul untuk topik diskusi)
 * - Relasi:
 *   1. User Berdiskusi -> belongsTo(User::class)
 *   2. Admin Membuat dan Mengelola Forum -> belongsTo(Admin::class)
 */
class Forum extends Model
{
    use HasFactory;

    protected $table = 'forum';

    protected $fillable = [
        'user_id',
        'admin_id',
        'judul',
        'isi',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    /** Relasi: User Berdiskusi di Forum */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Alias penulis untuk kompatibilitas tampilan */
    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Relasi: Admin Membuat dan Mengelola forum */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /** Balasan diskusi forum */
    public function balasan()
    {
        return $this->hasMany(Balasan::class, 'forum_id');
    }
}
