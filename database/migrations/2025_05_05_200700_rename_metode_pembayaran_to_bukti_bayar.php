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
        Schema::table('tb_transaksi', function (Blueprint $table) {
            //
            $table->renameColumn('metode_pembayaran', 'bukti_bayar');
        });

        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->string('bukti_bayar', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_transaksi', function (Blueprint $table) {
            //
            $table->renameColumn('bukti_bayar', 'metode_pembayaran');
        });
        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->string('bukti_bayar', 255)->nullable()->change();
        });
    }
};
