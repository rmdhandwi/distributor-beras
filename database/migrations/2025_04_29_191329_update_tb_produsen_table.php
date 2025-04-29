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
        Schema::table('tb_produsen', function (Blueprint $table) {
            //
            $table->date('tgl_pendaftaran')->nullable()->change();
            $table->boolean('status')->default(false)->after('tgl_pendaftaran');
            // $table->foreignId('user_id')->after('id_produsen')->constrained('users')->onDelete('cascade');
            $table->string('user_id')->after('id_produsen');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_produsen', function (Blueprint $table) {
            //
        });
    }
};
