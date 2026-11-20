<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->integer('id_akun', true); // INT auto increment + primary key
            $table->string('name', 100);
            $table->string('username', 100);
            $table->string('password', 100);
            $table->enum('level', ['user', 'mahasiswa', 'admin'])->default('user');
            $table->string('bio', 200)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('program_studi', 100)->nullable();
            $table->string('foto_profil')->nullable();
            $table->date('tanggal_daftar');

            $table->unique('username', 'uq_akun_username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};
