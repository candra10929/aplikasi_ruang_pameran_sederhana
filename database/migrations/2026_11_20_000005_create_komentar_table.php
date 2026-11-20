<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komentar', function (Blueprint $table) {
            $table->integer('id_komentar', true);
            $table->integer('id_akun');
            $table->integer('id_karya');
            $table->string('isi_komentar', 200);
            $table->date('tanggal_komentar')->default($this->hariIni());

            $table->index('id_akun', 'idx_komentar_akun');
            $table->index('id_karya', 'idx_komentar_karya');

            $table->foreign('id_akun', 'fk_komentar_akun')
                ->references('id_akun')->on('akun')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id_karya', 'fk_komentar_karya')
                ->references('id_karya')->on('karya')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }

    private function hariIni()
    {
        return DB::connection()->getDriverName() === 'sqlite'
            ? DB::raw("(date('now'))")
            : DB::raw('(CURDATE())');
    }
};
