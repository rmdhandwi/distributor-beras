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
        Schema::table('tb_transaksi', function (Blueprint $table){
            $table->dropColumn('harga_satuan');
            $table->renameColumn('total_harga','total_bayar');
        });

        Schema::create('tb_detail_pemesanan', function (Blueprint $table) {
            $table->id('id_detail_pemesanan');
            $table->foreignId('id_pemesanan')->constrained('tb_pemesanan', 'id_pemesanan')->onDelete('cascade');
            $table->string('berat');
            $table->integer('jumlah')->default(0);
            $table->integer('harga_satuan');
            $table->integer('total_harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_detail_pemesanan');
    }
};
