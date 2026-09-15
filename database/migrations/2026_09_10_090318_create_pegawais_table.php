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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('namaPegawai');
            $table->date('tanggalLahir');
            $table->string('jenisKelamin');
            $table->string('alamat');
            $table->string('agama');
            $table->string('statusPernikahan');
            $table->string('kewarganegaraan');
            $table->string('bidangPenempatan');
            $table->integer('usia');
            $table->integer('lamaBekerja');
            $table->decimal('gaji', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
