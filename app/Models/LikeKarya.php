<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LikeKarya extends Model
{
    protected $table = 'like_karya';
    protected $primaryKey = 'id_like';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';

    protected $fillable = ['id_akun', 'id_karya', 'tanggal_like'];

    protected function casts(): array
    {
        return [
            'tanggal_like' => 'date',
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