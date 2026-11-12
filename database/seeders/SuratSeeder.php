<?php

namespace Database\Seeders;

use App\Models\Surat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuratSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Data contoh surat dengan berbagai jenis.
     *
     * Seeder ini aman dijalankan berkali-kali: data contoh diperbarui
     * (updateOrCreate) berdasarkan nomor surat, sehingga tidak duplikat.
     */
    public function run(): void
    {
        $data = [
            [
                'nomor_surat' => '005/KEMENAG-KWG/04/2026',
                'tanggal_surat' => '2026-04-06',
                'perihal' => 'Undangan Rapat Koordinasi Program Pendidikan Diniyah',
                'nama_penerima_lembaga' => 'Kantor Kemenag Karawang',
                'jenis_surat' => Surat::JENIS_SURAT_MASUK,
            ],
            [
                'nomor_surat' => 'B-127/Kemenag.02/PP.00.7/10/2026',
                'tanggal_surat' => '2026-10-12',
                'perihal' => 'Permohonan Data Emis Pondok Pesantren',
                'nama_penerima_lembaga' => 'Pimpinan Pondok Pesantren',
                'jenis_surat' => Surat::JENIS_SURAT_KELUAR,
            ],
            [
                'nomor_surat' => 'SE/03/PD.PONTREN/XI/2026',
                'tanggal_surat' => '2026-11-03',
                'perihal' => 'Pelaksanaan Peringatan Hari Santri Nasional',
                'nama_penerima_lembaga' => 'Pengurus Pondok Pesantren',
                'jenis_surat' => Surat::JENIS_SURAT_EDARAN,
            ],
            [
                'nomor_surat' => 'UND.45/Kemenag-Karawang/IX/2026',
                'tanggal_surat' => '2026-09-15',
                'perihal' => 'Sosialisasi Bantuan Operasional Pesantren',
                'nama_penerima_lembaga' => 'Pontren NU Al-Muttaqin',
                'jenis_surat' => Surat::JENIS_SURAT_UNDANGAN,
            ],
            [
                'nomor_surat' => 'ND-12/PD-Pontren/VIII/2026',
                'tanggal_surat' => '2026-08-12',
                'perihal' => 'Pengajuan Peminjaman Ruang Aula',
                'nama_penerima_lembaga' => 'Kasubbag Tata Usaha',
                'jenis_surat' => Surat::JENIS_SURAT_NOTA_DINAS,
            ],
        ];

        foreach ($data as $item) {
            Surat::updateOrCreate(
                ['nomor_surat' => $item['nomor_surat']],
                $item
            );
        }
    }
}
