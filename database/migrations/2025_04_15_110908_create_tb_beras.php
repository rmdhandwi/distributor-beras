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
        Schema::create('tb_beras', function (Blueprint $table) {
            $table->id('id_beras')->primary();
            $table->string('nama_beras')->unique();
            $table->foreignId('id_produsen')->references('id_produsen')->on('tb_produsen')->onDelete('cascade');
            $table->string('jenis_beras');
            $table->integer('harga_jual');
            $table->integer('stok_awal');
            $table->integer('stok_tersedia');
            $table->date('tgl_produksi');
            $table->date('tgl_kadaluarsa');
            $table->string('kualitas_beras');
            $table->string('sertifikat_beras');
            $table->string('status_beras');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_beras');
    }
};
