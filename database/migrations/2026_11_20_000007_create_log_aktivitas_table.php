<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->integer('id_log', true);
            $table->integer('id_akun')->nullable();
            $table->integer('id_karya')->nullable();
            $table->string('aksi', 50);
            $table->string('keterangan', 50)->nullable();
            $table->date('waktu')->default($this->hariIni());

            $table->index('id_akun', 'idx_log_akun');
            $table->index('id_karya', 'idx_log_karya');

            // log tetap disimpan walau akun/karya terkait dihapus
            $table->foreign('id_akun', 'fk_log_akun')
                ->references('id_akun')->on('akun')
                ->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('id_karya', 'fk_log_karya')
                ->references('id_karya')->on('karya')
                ->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }

    private function hariIni()
    {
        return DB::connection()->getDriverName() === 'sqlite'
            ? DB::raw("(date('now'))")
            : DB::raw('(CURDATE())');
    }
};
