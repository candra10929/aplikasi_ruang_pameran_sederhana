<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kolaborator', function (Blueprint $table) {
            $table->integer('id_karya');
            $table->integer('id_akun');

            $table->primary(['id_karya', 'id_akun']);
            $table->index('id_akun', 'idx_kolab_akun');

            $table->foreign('id_karya', 'fk_kolab_karya')
                ->references('id_karya')->on('karya')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id_akun', 'fk_kolab_akun')
                ->references('id_akun')->on('akun')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kolaborator');
    }
};
