<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun admin bawaan. updateOrCreate membuat seeder aman dijalankan
        // berulang kali sekaligus mengembalikan password ke nilai awal.
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'nama_lengkap' => 'Administrator Sistem',
                'password' => Hash::make('12345678'),
                'hak_akses' => User::HAK_AKSES_ADMIN,
                'status' => User::STATUS_AKTIF,
            ]
        );

        // Data contoh surat masuk & arsip digital.
        $this->call(SuratSeeder::class);
    }
}
