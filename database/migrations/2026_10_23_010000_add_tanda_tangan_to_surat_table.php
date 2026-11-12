<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom tambahan untuk fitur Digital Signature (tanda tangan langsung
 * di layar) pada form input surat masuk:
 *
 * - tanda_tangan : data URI gambar PNG dalam format base64
 *                  ("data:image/png;base64,...") hasil gambar canvas,
 *                  dibersihkan langsung bersama data surat.
 *
 * Bersifat opsional (nullable) agar data lama tetap valid dan surat
 * yang tidak ditandatangani digital tetap dapat disimpan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->longText('tanda_tangan')->nullable()->after('bukti_penerimaan');
        });
    }

    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropColumn('tanda_tangan');
        });
    }
};
