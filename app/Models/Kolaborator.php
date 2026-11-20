<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Tabel kolaborator memakai primary key gabungan (id_karya, id_akun),
 * jadi dibuat sebagai Pivot. Tambah/hapus kolaborator lewat relasi:
 *   $karya->kolaborator()->attach($idAkun);
 *   $karya->kolaborator()->detach($idAkun);
 */
class Kolaborator extends Pivot
{
    protected $table = 'kolaborator';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id_karya', 'id_akun'];

    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

    public function karya(): BelongsTo
    {
        return $this->belongsTo(Karya::class, 'id_karya', 'id_karya');
    }
}