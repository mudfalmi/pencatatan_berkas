<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index tambahan untuk mempercepat query yang paling sering dipakai:
 *
 * - surat(tanggal_surat, id) : mendukung ORDER BY tanggal_surat DESC, id DESC
 *   pada halaman daftar surat (admin & petugas) sekaligus pagination.
 * - users(status)            : mendukung COUNT akun nonaktif untuk badge
 *   notifikasi pada topbar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->index(['tanggal_surat', 'id'], 'surat_tanggal_surat_id_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('status', 'users_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('surat', function (Blueprint $table) {
            $table->dropIndex('surat_tanggal_surat_id_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_status_index');
        });
    }
};
