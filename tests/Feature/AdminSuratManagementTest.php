<?php

namespace Tests\Feature;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSuratManagementTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::create([
            'nama_lengkap' => 'Administrator Sistem',
            'username' => 'admin',
            'password' => 'password',
            'hak_akses' => User::HAK_AKSES_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);
    }

    private function makePetugas(): User
    {
        return User::create([
            'nama_lengkap' => 'Petugas Berkas',
            'username' => 'petugas1',
            'password' => 'password',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_AKTIF,
        ]);
    }

    private function makeSurat(array $attributes = []): Surat
    {
        return Surat::create(array_merge([
            'nomor_surat' => '001/KEMENAG/KAR/2026',
            'tanggal_surat' => '2026-10-21',
            'perihal' => 'Undangan Rapat Koordinasi Pontren',
            'nama_penerima_lembaga' => 'Kantor PD Pontren',
            'jenis_surat' => Surat::JENIS_SURAT_MASUK,
        ], $attributes));
    }

    private function dummyPdf(string $nama = 'surat.pdf'): UploadedFile
    {
        return UploadedFile::fake()->create($nama, 120, 'application/pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Akses &_Otorisasi
    |--------------------------------------------------------------------------
    */

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/surat')->assertRedirect(route('login'));
        $this->get('/admin/surat/create')->assertRedirect(route('login'));
    }

    public function test_petugas_cannot_access_the_surat_module(): void
    {
        $petugas = $this->makePetugas();

        $response = $this->actingAs($petugas)->get(route('admin.surat.index'));

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        $this->actingAs($petugas)
            ->get(route('admin.surat.create'))
            ->assertRedirect(route('admin.dashboard'));
    }

    /*
    |--------------------------------------------------------------------------
    | Daftar surat
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_view_surat_index(): void
    {
        $this->actingAs($this->makeAdmin());
        $this->makeSurat();

        $this->get(route('admin.surat.index'))
            ->assertOk()
            ->assertSee('Kelola Surat & Arsip')
            ->assertSee('001/KEMENAG/KAR/2026')
            ->assertSee('Kantor PD Pontren')
            ->assertSee('Tambah Surat');
    }

    public function test_sidebar_shows_surat_menu_for_admin(): void
    {
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.surat.index'))
            ->assertOk()
            ->assertSee('Kelola Surat &amp; Arsip', false)
            ->assertSee(route('admin.surat.index'), false);
    }

    public function test_ringkasan_cache_is_invalidated_when_surat_changes(): void
    {
        $this->assertSame(0, Surat::ringkasan()['total_surat']);

        $surat = $this->makeSurat();
        $this->assertSame(1, Surat::ringkasan()['total_surat']);

        $surat->delete();
        $this->assertSame(0, Surat::ringkasan()['total_surat']);
    }

    public function test_surat_index_can_be_filtered(): void
    {
        $this->actingAs($this->makeAdmin());

        $this->makeSurat([
            'nomor_surat' => '002/SEK/2026',
            'perihal' => 'Undanganmusik',
            'jenis_surat' => Surat::JENIS_SURAT_EDARAN,
        ]);
        $this->makeSurat([
            'nomor_surat' => '003/SKD/2026',
            'perihal' => 'Surat Keputusan',
            'nama_penerima_lembaga' => 'Pontren Al-Irsyad',
            'jenis_surat' => Surat::JENIS_SURAT_KELUAR,
        ]);

        // Pencarian pada kolom nomor surat / perihal / penerima.
        $this->get(route('admin.surat.index', ['q' => 'Undanganmusik']))
            ->assertOk()
            ->assertSee('002/SEK/2026')
            ->assertDontSee('003/SKD/2026');

        // Filter berdasarkan jenis surat.
        $this->get(route('admin.surat.index', ['jenis_surat' => Surat::JENIS_SURAT_KELUAR]))
            ->assertOk()
            ->assertSee('003/SKD/2026')
            ->assertDontSee('002/SEK/2026');
    }

    /*
    |--------------------------------------------------------------------------
    | Tambah surat
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_open_the_create_form(): void
    {
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.surat.create'))
            ->assertOk()
            ->assertSee('Tambah Surat')
            ->assertSee('Nomor Surat');
    }

    public function test_admin_can_create_a_surat_with_digital_archive(): void
    {
        Storage::fake('local');

        $this->actingAs($this->makeAdmin());

        $response = $this->post(route('admin.surat.store'), [
            'nomor_surat' => '010/KEMENAG/KAR/2026',
            'tanggal_surat' => '2026-10-21',
            'perihal' => 'Permohonan Penetapan NOMAD',
            'nama_penerima_lembaga' => 'Pontren Modern',
            'jenis_surat' => Surat::JENIS_SURAT_MASUK,
            'file_dokumen' => $this->dummyPdf('surat-masuk.pdf'),
        ]);

        $response->assertRedirect(route('admin.surat.index'));
        $response->assertSessionHas('success');

        $surat = Surat::where('nomor_surat', '010/KEMENAG/KAR/2026')->first();

        $this->assertNotNull($surat);
        $this->assertSame('Permohonan Penetapan NOMAD', $surat->perihal);
        $this->assertSame('surat-masuk.pdf', $surat->nama_file_asli);

        // Berkas arsip benar-benar tersimpan pada disk privat "local".
        $this->assertNotNull($surat->file_dokumen);
        Storage::disk('local')->assertExists($surat->file_dokumen);
        $this->assertTrue($surat->hasDokumen());
    }

    public function test_surat_can_be_created_without_an_archive(): void
    {
        Storage::fake('local');

        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.surat.store'), [
            'nomor_surat' => '011/KEMENAG/KAR/2026',
            'tanggal_surat' => '2026-10-21',
            'perihal' => 'Surat tugas petugas',
            'nama_penerima_lembaga' => 'Kantor PD Pontren',
            'jenis_surat' => Surat::JENIS_SURAT_MASUK,
        ])->assertRedirect(route('admin.surat.index'));

        $surat = Surat::where('nomor_surat', '011/KEMENAG/KAR/2026')->first();

        $this->assertNotNull($surat);
        $this->assertNull($surat->file_dokumen);
        $this->assertFalse($surat->hasDokumen());
    }

    public function test_create_surat_validates_required_fields(): void
    {
        Storage::fake('local');

        $this->actingAs($this->makeAdmin());

        $response = $this->from(route('admin.surat.create'))->post(route('admin.surat.store'), []);

        $response->assertRedirect(route('admin.surat.create'));
        $response->assertSessionHasErrors(['nomor_surat', 'tanggal_surat', 'perihal', 'nama_penerima_lembaga', 'jenis_surat']);

        $this->assertSame(0, Surat::count());
    }

    public function test_duplicate_nomor_surat_is_rejected(): void
    {
        Storage::fake('local');

        $this->makeSurat(['nomor_surat' => '001/KEMENAG/KAR/2026']);
        $this->actingAs($this->makeAdmin());

        $response = $this->from(route('admin.surat.create'))->post(route('admin.surat.store'), [
            'nomor_surat' => '001/KEMENAG/KAR/2026',
            'tanggal_surat' => '2026-10-21',
            'perihal' => 'Duplikat',
            'nama_penerima_lembaga' => 'Kantor PD Pontren',
            'jenis_surat' => Surat::JENIS_SURAT_MASUK,
        ]);

        $response->assertRedirect(route('admin.surat.create'));
        $response->assertSessionHasErrors('nomor_surat');
        $this->assertSame(1, Surat::count());
    }

    public function test_invalid_jenis_surat_is_rejected(): void
    {
        Storage::fake('local');

        $this->actingAs($this->makeAdmin());

        $response = $this->from(route('admin.surat.create'))->post(route('admin.surat.store'), [
            'nomor_surat' => '012/KEMENAG/KAR/2026',
            'tanggal_surat' => '2026-10-21',
            'perihal' => 'Jenis tidak valid',
            'nama_penerima_lembaga' => 'Kantor PD Pontren',
            'jenis_surat' => 'Surat Sembelit',
        ]);

        $response->assertRedirect(route('admin.surat.create'));
        $response->assertSessionHasErrors('jenis_surat');
        $this->assertSame(0, Surat::count());
    }

    public function test_uploaded_file_must_have_an_allowed_extension(): void
    {
        Storage::fake('local');

        $this->actingAs($this->makeAdmin());

        $response = $this->from(route('admin.surat.create'))->post(route('admin.surat.store'), [
            'nomor_surat' => '013/KEMENAG/KAR/2026',
            'tanggal_surat' => '2026-10-21',
            'perihal' => 'Dokumen berbahaya',
            'nama_penerima_lembaga' => 'Kantor PD Pontren',
            'jenis_surat' => Surat::JENIS_SURAT_MASUK,
            'file_dokumen' => UploadedFile::fake()->create('virus.exe', 10, 'application/octet-stream'),
        ]);

        $response->assertRedirect(route('admin.surat.create'));
        $response->assertSessionHasErrors('file_dokumen');
        $this->assertSame(0, Surat::count());
    }

    /*
    |--------------------------------------------------------------------------
    | Ubah surat
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_open_the_edit_form(): void
    {
        $this->actingAs($this->makeAdmin());
        $surat = $this->makeSurat();

        $this->get(route('admin.surat.edit', $surat))
            ->assertOk()
            ->assertSee('Ubah Data Surat')
            ->assertSee('001/KEMENAG/KAR/2026');
    }

    public function test_admin_can_update_surat_data(): void
    {
        Storage::fake('local');

        $surat = $this->makeSurat();
        $this->actingAs($this->makeAdmin());

        $response = $this->put(route('admin.surat.update', $surat), [
            'nomor_surat' => '001/KEMENAG/KAR/2026',
            'tanggal_surat' => '2026-10-22',
            'perihal' => 'Perihal telah diperbarui',
            'nama_penerima_lembaga' => 'Pontren Al-Irsyad',
            'jenis_surat' => Surat::JENIS_SURAT_DISPOSISI,
        ]);

        $response->assertRedirect(route('admin.surat.index'));

        $surat->refresh();

        $this->assertSame('Perihal telah diperbarui', $surat->perihal);
        $this->assertSame('Pontren Al-Irsyad', $surat->nama_penerima_lembaga);
        $this->assertSame(Surat::JENIS_SURAT_DISPOSISI, $surat->jenis_surat);
        $this->assertSame('2026-10-22', $surat->tanggal_surat->toDateString());
    }

    public function test_updating_surat_can_replace_the_digital_archive(): void
    {
        Storage::fake('local');

        $surat = $this->makeSurat();

        // Simpan arsip lama.
        $lama = $this->dummyPdf('arsip-lama.pdf');
        $pathLama = $lama->store('surat', 'local');
        $surat->update(['file_dokumen' => $pathLama, 'nama_file_asli' => 'arsip-lama.pdf']);
        Storage::disk('local')->assertExists($pathLama);

        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.surat.update', $surat), [
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => '2026-10-21',
            'perihal' => $surat->perihal,
            'nama_penerima_lembaga' => $surat->nama_penerima_lembaga,
            'jenis_surat' => $surat->jenis_surat,
            'file_dokumen' => $this->dummyPdf('arsip-baru.pdf'),
        ])->assertRedirect(route('admin.surat.index'));

        $surat->refresh();

        // Arsip lama dihapus, arsip baru tersimpan.
        Storage::disk('local')->assertMissing($pathLama);
        Storage::disk('local')->assertExists($surat->file_dokumen);
        $this->assertSame('arsip-baru.pdf', $surat->nama_file_asli);
    }

    public function test_updating_surat_can_remove_the_digital_archive(): void
    {
        Storage::fake('local');

        $surat = $this->makeSurat();

        $path = $this->dummyPdf('arsip.pdf')->store('surat', 'local');
        $surat->update(['file_dokumen' => $path, 'nama_file_asli' => 'arsip.pdf']);

        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.surat.update', $surat), [
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => '2026-10-21',
            'perihal' => $surat->perihal,
            'nama_penerima_lembaga' => $surat->nama_penerima_lembaga,
            'jenis_surat' => $surat->jenis_surat,
            'hapus_dokumen' => '1',
        ])->assertRedirect(route('admin.surat.index'));

        $surat->refresh();

        Storage::disk('local')->assertMissing($path);
        $this->assertNull($surat->file_dokumen);
        $this->assertNull($surat->nama_file_asli);
    }

    /*
    |--------------------------------------------------------------------------
    | Hapus surat
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_delete_a_surat_and_its_archive(): void
    {
        Storage::fake('local');

        $surat = $this->makeSurat();

        $path = $this->dummyPdf('arsip.pdf')->store('surat', 'local');
        $surat->update(['file_dokumen' => $path, 'nama_file_asli' => 'arsip.pdf']);

        $this->actingAs($this->makeAdmin());

        $response = $this->delete(route('admin.surat.destroy', $surat));

        $response->assertRedirect(route('admin.surat.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('surat', ['id' => $surat->id]);

        // Berkas arsip ikut hilang dari penyimpanan server.
        Storage::disk('local')->assertMissing($path);
    }

    public function test_delete_surat_confirmation_modal_is_rendered(): void
    {
        $this->actingAs($this->makeAdmin());
        $surat = $this->makeSurat();

        $this->get(route('admin.surat.index'))
            ->assertOk()
            ->assertSee('data-delete-modal="deleteSuratModal"', false)
            ->assertSee('id="deleteSuratForm"', false)
            ->assertSee(route('admin.surat.destroy', $surat), false);
    }

    /*
    |--------------------------------------------------------------------------
    | Lihat & unduh arsip
    |--------------------------------------------------------------------------
    */

    public function test_archive_can_be_viewed_and_downloaded(): void
    {
        Storage::fake('local');

        $surat = $this->makeSurat();

        $path = $this->dummyPdf('arsip.pdf')->store('surat', 'local');
        $surat->update(['file_dokumen' => $path, 'nama_file_asli' => 'arsip.pdf']);

        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.surat.file', $surat))->assertOk();
        $this->get(route('admin.surat.download', $surat))->assertOk();
    }

    public function test_archive_actions_return_404_when_document_is_missing(): void
    {
        Storage::fake('local');

        $surat = $this->makeSurat();

        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.surat.file', $surat))->assertNotFound();
        $this->get(route('admin.surat.download', $surat))->assertNotFound();
    }

    public function test_archive_is_not_reachable_by_guest(): void
    {
        Storage::fake('local');

        $surat = $this->makeSurat();

        $this->get(route('admin.surat.file', $surat))->assertRedirect(route('login'));
        $this->get(route('admin.surat.download', $surat))->assertRedirect(route('login'));
    }
}
