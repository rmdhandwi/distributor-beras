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
        Schema::table('tb_beras', function (Blueprint $table){
            $table->dropColumn('harga_jual');
            $table->dropColumn('tgl_kadaluarsa');
        });

        Schema::create('tb_detail_beras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_beras')->constrained('tb_beras', 'id_beras')->onDelete('cascade');
            $table->string('berat');
            $table->integer('jumlah');
            $table->integer('harga');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_detail_beras');
    }
};
