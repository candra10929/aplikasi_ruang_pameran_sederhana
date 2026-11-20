<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';

    protected $fillable = ['id_akun', 'id_karya', 'aksi', 'keterangan', 'waktu'];

    protected function casts(): array
    {
        return [
            'waktu' => 'date',
        ];
    }

    /** Catat aktivitas. keterangan dibatasi 50 karakter sesuai kolom di database. */
    public static function catat(?Akun $akun, string $aksi, ?string $keterangan = null, ?Karya $karya = null): void
    {
        static::create([
            'id_akun' => $akun?->id_akun,
            'id_karya' => $karya?->id_karya,
            'aksi' => Str::limit($aksi, 50, ''),
            'keterangan' => $keterangan === null ? null : Str::limit($keterangan, 47, '...'),
            'waktu' => now(),
        ]);
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
