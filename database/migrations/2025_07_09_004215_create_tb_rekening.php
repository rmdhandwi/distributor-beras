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
        Schema::create('tb_rekening', function (Blueprint $table) {
            $table->id('id_rekening');
            $table->string('no_rekening');
            $table->string('nama_rekening')->unique();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
        });

        Schema::table('tb_transaksi', function (Blueprint $table) {
            $table->foreignId('rekening')->nullable()->after('total_bayar')->constrained('tb_rekening', 'id_rekening')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_rekening');
    }
};
