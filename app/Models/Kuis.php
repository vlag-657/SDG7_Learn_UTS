<?php

namespace App\Models;

/**
 * Model Kuis (alias/indonesian name untuk Quiz) untuk kompatibilitas penuh.
 * Mewakili entitas Quiz pada ERD.
 */
class Kuis extends Quiz
{
    protected $table = 'kuis';

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'kuis_id')->orderBy('urutan');
    }

    public function hasil()
    {
        return $this->hasMany(HasilKuis::class, 'kuis_id');
    }
}
