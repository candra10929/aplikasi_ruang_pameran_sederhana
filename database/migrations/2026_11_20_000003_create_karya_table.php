<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karya', function (Blueprint $table) {
            $table->integer('id_karya', true);
            $table->integer('id_akun');
            $table->integer('id_kategori')->nullable();
            $table->string('judul', 100);
            $table->string('deskripsi', 1000)->nullable();
            $table->string('tools', 200)->nullable();
            $table->string('file_karya');
            $table->date('tanggal_upload')->default($this->hariIni());
            $table->string('thumbnail')->nullable();

            $table->index('id_akun', 'idx_karya_akun');
            $table->index('id_kategori', 'idx_karya_kategori');

            $table->foreign('id_akun', 'fk_karya_akun')
                ->references('id_akun')->on('akun')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id_kategori', 'fk_karya_kategori')
                ->references('id_kategori')->on('kategori')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karya');
    }

    /** Default "tanggal hari ini" (CURDATE() di MySQL/MariaDB, date('now') di SQLite). */
    private function hariIni()
    {
        return DB::connection()->getDriverName() === 'sqlite'
            ? DB::raw("(date('now'))")
            : DB::raw('(CURDATE())');
    }
};
