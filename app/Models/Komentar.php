<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';

    protected $fillable = ['id_akun', 'id_karya', 'isi_komentar', 'tanggal_komentar'];

    protected function casts(): array
    {
        return [
            'tanggal_komentar' => 'date',
        ];
    }

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

    public function karya(): BelongsTo
    {
        return $this->belongsTo(Karya::class, 'id_karya', 'id_karya');
    }
}