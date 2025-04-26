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
        Schema::create('tb_gudang', function (Blueprint $table) {
            $table->id('id_gudang');
            $table->foreignId('id_beras')->constrained('tb_beras', 'id_beras')->onDelete('cascade');
            $table->foreignId('id_produsen')->constrained('tb_produsen', 'id_produsen')->onDelete('cascade');
            $table->integer('stok_awal')->default(0);
            $table->integer('rusak')->default(0);
            $table->integer('hilang')->default(0);
            $table->integer('stok_sisa')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_gudang');
    }
};
