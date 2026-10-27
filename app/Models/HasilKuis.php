<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model HasilKuis mewakili relasi asosiatif pada ERD:
 * User -- [Mengerjakan dan Menampilkan skor] -- Quiz
 */
class HasilKuis extends Model
{
    protected $table = 'hasil_kuis';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'kuis_id',
        'kuis_judul',
        'skor',
        'skor_maks',
        'persentase',
        'jumlah_benar',
        'jumlah_pertanyaan',
        'tinjauan',
    ];

    protected $casts = [
        'tinjauan' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }
}
