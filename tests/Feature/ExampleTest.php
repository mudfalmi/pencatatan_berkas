<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tamu yang membuka halaman utama diarahkan ke halaman login.
     */
    public function test_guest_is_redirected_to_login_from_root(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    /**
     * Admin yang sudah login dan membuka halaman login diarahkan ke dashboard.
     *
     * Middleware "guest" memakai redirectUsersTo() pada bootstrap/app.php.
     */
    public function test_authenticated_admin_is_redirected_to_dashboard_from_login(): void
    {
        $admin = User::factory()->create([
            'hak_akses' => User::HAK_AKSES_ADMIN,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->actingAs($admin)
            ->get(route('login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Petugas yang sudah login dan membuka halaman login diarahkan ke dashboard petugas.
     */
    public function test_authenticated_petugas_is_redirected_to_petugas_dashboard_from_login(): void
    {
        $petugas = User::factory()->create([
            'hak_akses' => User::HAK_AKSES_PETUGAS,
            'status' => User::STATUS_AKTIF,
        ]);

        $this->actingAs($petugas)
            ->get(route('login'))
            ->assertRedirect(route('petugas.dashboard'));
    }
}
