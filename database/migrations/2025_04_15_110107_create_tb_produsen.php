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
            $table->id('id_produsen');
            $table->foreignId('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->string('nama_produsen')->unique();
            $table->text('alamat');
            $table->string('no_telp');
            $table->string('email')->unique();
            $table->date('tgl_pendaftaran')->nullable();
            $table->boolean('status')->default(false);
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
