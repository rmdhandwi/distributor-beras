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
        Schema::create('tb_transaksi', function (Blueprint $table) {
            $table->id('id_transaksi')->primary();
            $table->foreignId('id_pemesanan')->references('id_pemesanan')->on('tb_pemesanan')->onDelete('cascade');
            $table->date('tgl_transaksi');
            $table->integer('jmlh');
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->string('metode_pembayaran');
            $table->string('status_pembayaran');
            $table->string('status_pengiriman');
            $table->date('tgl_pengiriman');
            $table->text('catatan');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_transaksi');
    }
};
