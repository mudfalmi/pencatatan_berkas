<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
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

    private function makePetugas(string $username = 'petugas1'): User
    {
        return User::create([
            'nama_lengkap' => 'Petugas Berkas',
            'username' => $username,
            'password' => 'password',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_AKTIF,
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect(route('login'));
        $this->get('/admin/users')->assertRedirect(route('login'));
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $this->makeAdmin();

        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticated();

        $this->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Selamat Datang, Admin!')
            ->assertSee('Total Pengguna');
    }

    public function test_login_with_wrong_password_fails(): void
    {
        $this->makeAdmin();

        $response = $this->from(route('login'))->post('/login', [
            'username' => 'admin',
            'password' => 'salah',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = $this->makeAdmin();
        $user->update(['status' => User::STATUS_NONAKTIF]);

        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_users_index_lists_accounts(): void
    {
        $admin = $this->makeAdmin();
        $this->makePetugas();

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee('Kelola Akun')
            ->assertSee('petugas1')
            ->assertSee('Administrator Sistem');
    }

    public function test_admin_can_create_a_petugas_account(): void
    {
        $this->actingAs($this->makeAdmin());

        $response = $this->post(route('admin.users.store'), [
            'nama_lengkap' => 'Petugas Baru',
            'username' => 'petugas.baru',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'username' => 'petugas.baru',
            'nama_lengkap' => 'Petugas Baru',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
        ]);
    }

    public function test_duplicate_username_is_rejected(): void
    {
        $this->makeAdmin();
        $this->actingAs(User::where('username', 'admin')->first());

        $response = $this->from(route('admin.users.create'))->post(route('admin.users.store'), [
            'nama_lengkap' => 'Rekayasa',
            'username' => 'admin',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_AKTIF,
        ]);

        $response->assertRedirect(route('admin.users.create'));
        $response->assertSessionHasErrors('username');
        $this->assertSame(1, User::count());
    }

    public function test_admin_can_update_an_account(): void
    {
        $petugas = $this->makePetugas();
        $this->actingAs($this->makeAdmin());

        $response = $this->put(route('admin.users.update', $petugas), [
            'nama_lengkap' => 'Petugas Diubah',
            'username' => 'petugas.ubah',
            'password' => '',
            'password_confirmation' => '',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_NONAKTIF,
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $petugas->id,
            'nama_lengkap' => 'Petugas Diubah',
            'username' => 'petugas.ubah',
            'status' => User::STATUS_NONAKTIF,
        ]);
    }

    public function test_update_with_empty_password_keeps_old_password(): void
    {
        $petugas = $this->makePetugas();
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.users.update', $petugas), [
            'nama_lengkap' => 'Petugas Berkas',
            'username' => 'petugas1',
            'password' => '',
            'password_confirmation' => '',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->assertTrue(
            \Illuminate\Support\Facades\Hash::check('password', $petugas->fresh()->password)
        );
    }

    public function test_admin_can_delete_another_account(): void
    {
        $petugas = $this->makePetugas();
        $this->actingAs($this->makeAdmin());

        $response = $this->delete(route('admin.users.destroy', $petugas));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $petugas->id]);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $response = $this->delete(route('admin.users.destroy', $admin));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_demote_own_account(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        $response = $this->put(route('admin.users.update', $admin), [
            'nama_lengkap' => 'Administrator Sistem',
            'username' => 'admin',
            'password' => '',
            'password_confirmation' => '',
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_AKTIF,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id, 'hak_akses' => User::HAK_AKSES_ADMIN]);
    }

    public function test_logout_ends_the_session(): void
    {
        $this->actingAs($this->makeAdmin());

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
