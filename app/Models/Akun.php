<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Akun extends Authenticatable
{
    public const LEVEL = [
        'user' => 'Pengguna',
        'mahasiswa' => 'Mahasiswa',
        'admin' => 'Admin',
    ];

    protected $table = 'akun';
    protected $primaryKey = 'id_akun';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d';

    protected $fillable = [
        'name',
        'username',
        'password',
        'level',
        'bio',
        'foto_profil',
        'tanggal_daftar',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tanggal_daftar' => 'date',
        ];
    }

    protected static function booted(): void
    {
        // tanggal_daftar tidak punya default di database, jadi diisi otomatis di sini
        static::creating(function (Akun $akun) {
            $akun->tanggal_daftar ??= now();
            $akun->level ??= 'user';
        });
    }

    // Tabel akun tidak punya kolom remember_token
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function isAdmin(): bool
    {
        return $this->level === 'admin';
    }

    public function isMahasiswa(): bool
    {
        return $this->level === 'mahasiswa';
    }

    public function getLabelLevelAttribute(): string
    {
        return self::LEVEL[$this->level] ?? ucfirst((string) $this->level);
    }

    /** Inisial untuk avatar, mis. "Diva Maulana" -> "DM" */
    public function getInisialAttribute(): string
    {
        $kata = preg_split('/\s+/', trim((string) $this->name)) ?: [];
        $inisial = mb_strtoupper(mb_substr($kata[0] ?? '?', 0, 1));

        if (count($kata) > 1) {
            $inisial .= mb_strtoupper(mb_substr(end($kata), 0, 1));
        }

        return $inisial;
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_profil ? asset($this->foto_profil) : null;
    }

    public function getWarnaAvatarAttribute(): string
    {
        $warna = ['av-blue', 'av-pink', 'av-yellow', 'av-green', 'av-indigo', 'av-purple'];

        return $warna[((int) $this->id_akun) % count($warna)];
    }

    /** Karya yang diupload akun ini */
    public function karya(): HasMany
    {
        return $this->hasMany(Karya::class, 'id_akun', 'id_akun');
    }

    /** Karya yang diikuti akun ini sebagai kolaborator */
    public function karyaKolaborasi(): BelongsToMany
    {
        return $this->belongsToMany(Karya::class, 'kolaborator', 'id_akun', 'id_karya')
            ->using(Kolaborator::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(LikeKarya::class, 'id_akun', 'id_akun');
    }

    public function komentar(): HasMany
    {
        return $this->hasMany(Komentar::class, 'id_akun', 'id_akun');
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'id_akun', 'id_akun');
    }
}
