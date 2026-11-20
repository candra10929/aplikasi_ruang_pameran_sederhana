<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karya extends Model
{
    protected $table = 'karya';
    protected $primaryKey = 'id_karya';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'id_akun',
        'id_kategori',
        'judul',
        'deskripsi',
        'tools',
        'file_karya',
        'tanggal_upload',
        'thumbnail',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_upload' => 'date',
        ];
    }

    /**
     * Muat data yang dibutuhkan kartu karya: pemilik, kategori, jumlah like,
     * dan apakah akun yang sedang login sudah menyukainya ("disukai").
     */
    public function scopeDenganInfo(Builder $query, ?int $idAkun = null): Builder
    {
        return $query->with(['pemilik', 'kategori'])
            ->withCount('likes')
            ->when($idAkun, fn (Builder $q) => $q->withExists([
                'likes as disukai' => fn ($l) => $l->where('id_akun', $idAkun),
            ]));
    }

    public function getIsVideoAttribute(): bool
    {
        return strtolower(pathinfo((string) $this->file_karya, PATHINFO_EXTENSION)) === 'mp4';
    }

    public function getUrlFileAttribute(): string
    {
        return asset($this->file_karya);
    }

    /** Gambar untuk kartu: thumbnail jika ada, kalau tidak file karya itu sendiri (kecuali video). */
    public function getUrlGambarAttribute(): ?string
    {
        if ($this->thumbnail) {
            return asset($this->thumbnail);
        }

        return $this->is_video ? null : $this->url_file;
    }

    public function bisaDihapusOleh(?Akun $akun): bool
    {
        return $akun && ($akun->isAdmin() || (int) $akun->id_akun === (int) $this->id_akun);
    }

    /** Akun yang mengupload karya */
    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    /** Akun-akun yang menjadi kolaborator karya ini */
    public function kolaborator(): BelongsToMany
    {
        return $this->belongsToMany(Akun::class, 'kolaborator', 'id_karya', 'id_akun')
            ->using(Kolaborator::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(LikeKarya::class, 'id_karya', 'id_karya');
    }

    public function komentar(): HasMany
    {
        return $this->hasMany(Komentar::class, 'id_karya', 'id_karya');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'id_karya', 'id_karya');
    }
}
