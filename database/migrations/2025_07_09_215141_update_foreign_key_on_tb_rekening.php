<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_rekening', function (Blueprint $table) {
            //
            // Hapus foreign key lama
            $table->dropForeign(['id_produsen']);

            // Tambahkan foreign key baru ke tb_user
            $table->foreign('id_produsen')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_rekening', function (Blueprint $table) {
            // Rollback: hapus foreign key ke tb_user
            $table->dropForeign(['id_produsen']);

            // Tambah lagi foreign key ke tb_produsen
            $table->foreign('id_produsen')
                ->references('id_produsen')
                ->on('tb_produsen')
                ->onDelete('cascade');
        });
    }
};
