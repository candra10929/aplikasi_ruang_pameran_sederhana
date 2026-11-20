<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('like_karya', function (Blueprint $table) {
            $table->integer('id_like', true);
            $table->integer('id_akun');
            $table->integer('id_karya');
            $table->date('tanggal_like')->default($this->hariIni());

            // satu akun hanya bisa menyukai satu karya satu kali
            $table->unique(['id_akun', 'id_karya'], 'uq_like_akun_karya');
            $table->index('id_karya', 'idx_like_karya');

            $table->foreign('id_akun', 'fk_like_akun')
                ->references('id_akun')->on('akun')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id_karya', 'fk_like_karya')
                ->references('id_karya')->on('karya')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('like_karya');
    }

    private function hariIni()
    {
        return DB::connection()->getDriverName() === 'sqlite'
            ? DB::raw("(date('now'))")
            : DB::raw('(CURDATE())');
    }
};
