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
        Schema::create('tb_produsen', function (Blueprint $table) {
            $table->id('id_produsen')->primary();
            $table->string('nama_produsen')->unique();
            $table->text('alamat');
            $table->string('no_telp');
            $table->string('email')->unique();
            $table->string('jenis_beras');
            $table->integer('harga_beras');
            $table->integer('jml_stok');
            $table->string('status_stok');
            $table->date('tgl_pendaftaran');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_produsen');
    }
};
