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
        Schema::create('presences', function (Blueprint $table) {
            $table->id('user_id');
            $table->integer('role')->nullable();
            $table->string('nama_pegawai');
            $table->string('bidang_kerja');
            $table->string('lokasi');
            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->time('jam_pulang');
            $table->integer('status')->nulllable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
