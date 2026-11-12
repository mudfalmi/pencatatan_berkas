<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom tambahan untuk modul Petugas (input surat masuk):
 *
 * - tanggal_penerimaan : tanggal fisik surat diterima petugas (TTD).
 * - bukti_penerimaan   : catatan tanda serah terima / bukti penerimaan
 *                        (mis. nama pihak yang menandatangani, no. register).
 *
 * Kedua kolom bersifat opsional (nullable) agar data lama tetap valid
 * dan form Admin (yang tidak memakai kolom ini) tidak terganggu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->date('tanggal_penerimaan')->nullable()->after('tanggal_surat');
            $table->string('bukti_penerimaan')->nullable()->after('nama_penerima_lembaga');
        });
    }

    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn(['tanggal_penerimaan', 'bukti_penerimaan']);
        });
    }
};
