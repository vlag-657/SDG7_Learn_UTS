<?php

namespace App\Models;

/**
 * Model Postingan mewakili thread/topik forum (alias tabel postingan).
 * Mewakili entitas Forum pada ERD.
 */
class Postingan extends Forum
{
    protected $table = 'postingan';

    protected $fillable = [
        'user_id',
        'admin_id',
        'judul',
        'isi',
        'tanggal',
    ];

    public function balasan()
    {
        return $this->hasMany(Balasan::class, 'postingan_id');
    }
}
