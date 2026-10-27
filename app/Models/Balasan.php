<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balasan extends Model
{
    protected $table = 'balasan';

    protected $fillable = [
        'forum_id',
        'postingan_id',
        'user_id',
        'isi',
    ];

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'forum_id');
    }

    public function postingan()
    {
        return $this->belongsTo(Postingan::class, 'postingan_id');
    }

    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
