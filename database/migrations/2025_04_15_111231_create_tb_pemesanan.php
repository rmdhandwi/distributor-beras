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
        Schema::create('tb_pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->foreignId('id_produsen')->references('id_produsen')->on('tb_produsen')->onDelete('cascade');
            $table->foreignId('id_beras')->references('id_beras')->on('tb_beras')->onDelete('cascade');
            $table->integer('jmlh');
            $table->date('tgl_pemesanan');
            $table->string('status_pesanan');
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
        Schema::dropIfExists('tb_pemesanan');
    }
};
