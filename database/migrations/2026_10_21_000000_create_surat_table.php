<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel surat: data surat masuk & arsip digital.
     *
     * - file_dokumen      : path relatif file arsip pada disk "local"
     *                       (storage/app/surat/...), disimpan private
     *                       sehingga hanya bisa diakses lewat controller.
     * - nama_file_asli    : nama file asli saat diunggah (dipakai saat
     *                       mengunduh arsip).
     */
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat')->index();
            $table->string('perihal');
            $table->string('nama_penerima_lembaga');
            $table->string('jenis_surat')->index();
            $table->string('file_dokumen')->nullable();
            $table->string('nama_file_asli')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
