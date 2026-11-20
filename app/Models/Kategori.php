<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $timestamps = false;

    protected $fillable = ['jenis_kategori'];

    public function karya(): HasMany
    {
        return $this->hasMany(Karya::class, 'id_kategori', 'id_kategori');
    }
}