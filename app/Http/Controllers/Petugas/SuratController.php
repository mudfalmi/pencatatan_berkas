<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Modul Surat Masuk & Arsip Digital untuk Petugas (PD Pontren).
 *
 * Mengelola data pada tabel "surat" yang sama dengan panel Admin.
 * Petugas dapat: menambah surat beserta arsip digitalnya, mencari &
 * memfilter daftar arsip, mengubah data, melihat serta mengunduh arsip.
 * Penghapusan data tetap menjadi wewenang Admin.
 */
class SuratController extends Controller
{
    /**
     * Maksimal ukuran arsip digital yang boleh diunggah (10 MB).
     */
    private const MAX_UPLOAD_KB = 10240;

    /**
     * Ekstensi arsip digital yang diperbolehkan.
     */
    private const EKSTENSI_DOKUMEN = 'pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx';

    /**
     * Daftar surat masuk & arsip digital (dengan pencarian & filter jenis).
     */
    public function index(Request $request): View
    {
        $surats = Surat::query()
            ->ringkas()
            ->when($request->filled('q'), function ($query) use ($request) {
                $cari = $request->string('q')->toString();

                $query->where(function ($sub) use ($cari) {
                    $sub->where('nomor_surat', 'like', "%{$cari}%")
                        ->orWhere('perihal', 'like', "%{$cari}%")
                        ->orWhere('nama_penerima_lembaga', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('jenis_surat'), function ($query) use ($request) {
                $query->where('jenis_surat', $request->string('jenis_surat')->toString());
            })
            ->orderByDesc('tanggal_surat')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Rekapitulasi data untuk kartu ringkasan di atas tabel.
        $statistik = Surat::ringkasan();

        return view('petugas.surat.index', [
            'surats' => $surats,
            'statistik' => $statistik,
            'jenisSurat' => Surat::jenisSuratOptions(),
        ]);
    }

    /**
     * Form tambah surat (termasuk unggah dokumen fisik ke bentuk digital).
     */
    public function create(): View
    {
        return view('petugas.surat.create', [
            'jenisSurat' => Surat::jenisSuratOptions(),
        ]);
    }

    /**
     * Simpan surat baru beserta arsip digitalnya.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(), $this->pesanValidasi());

        // Simpan arsip digital ke folder private storage/app/surat
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');

            $data['file_dokumen'] = $file->store('surat', 'local');
            $data['nama_file_asli'] = $file->getClientOriginalName();
        }

        Surat::create($data);

        return redirect()
            ->route('petugas.surat.index')
            ->with('success', "Surat \"{$data['nomor_surat']}\" berhasil ditambahkan.");
    }

    /**
     * Form ubah data surat.
     */
    public function edit(Surat $surat): View
    {
        return view('petugas.surat.edit', [
            'surat' => $surat,
            'jenisSurat' => Surat::jenisSuratOptions(),
        ]);
    }

    /**
     * Perbarui data surat (termasuk mengganti arsip digital).
     */
    public function update(Request $request, Surat $surat): RedirectResponse
    {
        $data = $request->validate($this->rules($surat), $this->pesanValidasi());

        // Hapus arsip lama bila tercentang "hapus arsip" atau diganti baru.
        if ($request->boolean('hapus_dokumen') && $surat->file_dokumen) {
            Storage::disk('local')->delete($surat->file_dokumen);

            $data['file_dokumen'] = null;
            $data['nama_file_asli'] = null;
        }

        if ($request->hasFile('file_dokumen')) {
            if ($surat->file_dokumen) {
                Storage::disk('local')->delete($surat->file_dokumen);
            }

            $file = $request->file('file_dokumen');

            $data['file_dokumen'] = $file->store('surat', 'local');
            $data['nama_file_asli'] = $file->getClientOriginalName();
        }

        unset($data['hapus_dokumen']);

        // Canvas dikosongkan / tidak tersentuh -> pertahankan tanda tangan lama.
        if (empty($data['tanda_tangan'])) {
            unset($data['tanda_tangan']);
        }

        $surat->update($data);

        return redirect()
            ->route('petugas.surat.index')
            ->with('success', "Surat \"{$surat->nomor_surat}\" berhasil diperbarui.");
    }

    /**
     * Lihat arsip digital langsung di browser.
     */
    public function file(Surat $surat): StreamedResponse
    {
        abort_if(! $surat->file_dokumen, 404);
        abort_unless(Storage::disk('local')->exists($surat->file_dokumen), 404);

        return Storage::disk('local')->response(
            $surat->file_dokumen,
            $surat->nama_dokumen,
            ['Content-Type' => $this->contentType($surat->ekstensiDokumen())],
            'inline'
        );
    }

    /**
     * Unduh arsip digital.
     */
    public function download(Surat $surat): StreamedResponse
    {
        abort_if(! $surat->file_dokumen, 404);
        abort_unless(Storage::disk('local')->exists($surat->file_dokumen), 404);

        return Storage::disk('local')->download(
            $surat->file_dokumen,
            $surat->nama_dokumen,
            ['Content-Type' => $this->contentType($surat->ekstensiDokumen())]
        );
    }

    /**
     * Aturan validasi (bersama untuk store & update).
     */
    private function rules(?Surat $surat = null): array
    {
        return [
            'nomor_surat' => [
                'required', 'string', 'max:100',
                Rule::unique('surat', 'nomor_surat')->ignore($surat?->id),
            ],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_penerimaan' => ['nullable', 'date'],
            'perihal' => ['required', 'string', 'max:255'],
            'nama_penerima_lembaga' => ['required', 'string', 'max:255'],
            'bukti_penerimaan' => ['nullable', 'string', 'max:255'],
            'tanda_tangan' => [
                'nullable', 'string',
                // Data URI PNG base64 hasil canvas tanda tangan digital.
                'regex:#^data:image/png;base64,[A-Za-z0-9+/=]+$#',
            ],
            'jenis_surat' => ['required', Rule::in(array_keys(Surat::jenisSuratOptions()))],
            'file_dokumen' => ['nullable', 'file', 'mimes:'.self::EKSTENSI_DOKUMEN, 'max:'.self::MAX_UPLOAD_KB],
            'hapus_dokumen' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Pesan validasi dalam bahasa Indonesia.
     */
    private function pesanValidasi(): array
    {
        return [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'nomor_surat.unique' => 'Nomor surat ini sudah terdaftar.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'tanggal_surat.date' => 'Tanggal surat tidak valid.',
            'tanggal_penerimaan.date' => 'Tanggal penerimaan tidak valid.',
            'perihal.required' => 'Perihal surat wajib diisi.',
            'nama_penerima_lembaga.required' => 'Nama pihak/lembaga penerima wajib diisi.',
            'bukti_penerimaan.max' => 'Bukti penerimaan maksimal 255 karakter.',
            'tanda_tangan.regex' => 'Data tanda tangan digital tidak valid.',
            'jenis_surat.required' => 'Jenis surat wajib dipilih.',
            'jenis_surat.in' => 'Jenis surat yang dipilih tidak valid.',
            'file_dokumen.mimes' => 'Arsip digital harus berupa PDF, gambar, atau dokumen (Word/Excel).',
            'file_dokumen.max' => 'Ukuran arsip digital maksimal 10 MB.',
        ];
    }

    /**
     * Content-Type berdasarkan ekstensi file.
     */
    private function contentType(string $ekstensi): string
    {
        return match ($ekstensi) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            default => 'application/octet-stream',
        };
    }
}
