<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    public const JENIS_SURAT_MASUK = 'Surat Masuk';
    public const JENIS_SURAT_KELUAR = 'Surat Keluar';
    public const JENIS_SURAT_EDARAN = 'Surat Edaran';
    public const JENIS_SURAT_DISPOSISI = 'Disposisi';
    public const JENIS_SURAT_UNDANGAN = 'Undangan';
    public const JENIS_SURAT_NOTA_DINAS = 'Nota Dinas';
    public const JENIS_SURAT_LAINNYA = 'Lainnya';

    /**
     * Kolom minimal untuk daftar/dasbor.
     *
     * Kolom "tanda_tangan" sengaja tidak diambil karena berisi data
     * gambar base64 yang besar dan hanya dibutuhkan pada form edit.
     *
     * @var list<string>
     */
    public const KOLOM_RINGKAS = [
        'id',
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'nama_penerima_lembaga',
        'jenis_surat',
        'file_dokumen',
    ];

    /**
     * Nama tabel pada database "pencatatan_berkas".
     *
     * App\Models\Surat secara default akan menebak nama tabel
     * menjadi "surats" (jamak), sedangkan tabel yang dibuat oleh
     * migrasi bernama "surat" (tunggal). Karena itu nama tabel
     * ditetapkan secara eksplisit di sini.
     */
    protected $table = 'surat';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tanggal_penerimaan',
        'perihal',
        'nama_penerima_lembaga',
        'bukti_penerimaan',
        'tanda_tangan',
        'jenis_surat',
        'file_dokumen',
        'nama_file_asli',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_penerimaan' => 'date',
        ];
    }

    /**
     * Daftar pilihan jenis surat (dipakai pada form & filter).
     *
     * @return array<string, string>
     */
    public static function jenisSuratOptions(): array
    {
        return [
            self::JENIS_SURAT_MASUK => 'Surat Masuk',
            self::JENIS_SURAT_KELUAR => 'Surat Keluar',
            self::JENIS_SURAT_EDARAN => 'Surat Edaran',
            self::JENIS_SURAT_DISPOSISI => 'Disposisi',
            self::JENIS_SURAT_UNDANGAN => 'Undangan',
            self::JENIS_SURAT_NOTA_DINAS => 'Nota Dinas',
            self::JENIS_SURAT_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Batasi query hanya ke kolom yang dipakai halaman daftar/dasbor.
     */
    public function scopeRingkas(Builder $query): Builder
    {
        return $query->select(self::KOLOM_RINGKAS);
    }

    /**
     * Ringkasan surat & arsip dalam satu query agregat.
     *
     * Sebelumnya halaman memanggil count() dan whereNotNull()->count()
     * secara terpisah. COUNT(file_dokumen) hanya menghitung nilai non-null,
     * sehingga hasilnya sama dengan total arsip dengan satu kali query.
     *
     * @return array{total_surat: int, total_arsip: int}
     */
    public static function ringkasan(): array
    {
        $data = self::query()
            ->selectRaw('COUNT(*) as total_surat, COUNT(file_dokumen) as total_arsip')
            ->first();

        return [
            'total_surat' => (int) ($data->total_surat ?? 0),
            'total_arsip' => (int) ($data->total_arsip ?? 0),
        ];
    }

    /**
     * Apakah surat ini sudah punya arsip digital?
     *
     * Cukup memeriksa kolom file_dokumen (tanpa Storage::exists()).
     * Sebelumnya setiap baris pada halaman daftar melakukan pengecekan
     * langsung ke disk, sehingga 10-15 baris = 10-15 operasi I/O.
     * Validasi keberadaan file tetap dilakukan di controller saat
     * file dibuka/diunduh (route file & download).
     */
    public function hasDokumen(): bool
    {
        return filled($this->file_dokumen);
    }

    /**
     * URL untuk melihat arsip digital di browser.
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->file_dokumen ? route('admin.surat.file', $this) : null;
    }

    /**
     * URL untuk mengunduh arsip digital.
     */
    public function getDownloadUrlAttribute(): ?string
    {
        return $this->file_dokumen ? route('admin.surat.download', $this) : null;
    }

    /**
     * Nama file arsip yang ditampilkan ke pengguna.
     */
    public function getNamaDokumenAttribute(): ?string
    {
        return $this->nama_file_asli ?: ($this->file_dokumen ? basename($this->file_dokumen) : null);
    }

    /**
     * Ekstensi file arsip (pdf, jpg, ...).
     */
    public function ekstensiDokumen(): string
    {
        return $this->file_dokumen
            ? strtolower(pathinfo($this->file_dokumen, PATHINFO_EXTENSION))
            : '';
    }

    /**
     * Apakah file arsip bisa ditampilkan langsung di browser?
     */
    public function bisaDipratinjau(): bool
    {
        return in_array($this->ekstensiDokumen(), ['pdf', 'jpg', 'jpeg', 'png', 'webp'], true);
    }
}
