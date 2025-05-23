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
        Schema::table('tb_gudang', function (Blueprint $table){
            $table->dropColumn('stok_awal');
            $table->dropColumn('rusak');
            $table->dropColumn('hilang');
            $table->dropColumn('stok_sisa');
        });

        Schema::create('tb_detail_gudang', function (Blueprint $table) {
            $table->id('id_detail_gudang');
            $table->foreignId('id_gudang')->constrained('tb_gudang', 'id_gudang')->onDelete('cascade');
            $table->string('berat');
            $table->integer('stok_awal')->default(0);
            $table->integer('rusak')->default(0);
            $table->integer('hilang')->default(0);
            $table->integer('stok_sisa')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_detail_gudang');
    }
};
