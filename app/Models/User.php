<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const HAK_AKSES_ADMIN = 'admin';
    public const HAK_AKSES_PETUGAS = 'petugas';

    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';

    /**
     * Key cache untuk jumlah akun nonaktif (badge notifikasi topbar).
     */
    public const CACHE_KEY_NONAKTIF = 'users.nonaktif.count';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'hak_akses',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Ringkasan akun (total, admin, petugas) dalam satu query agregat.
     *
     * @return array{total_pengguna: int, total_admin: int, total_petugas: int}
     */
    public static function ringkasan(): array
    {
        $data = self::query()
            ->selectRaw(
                'COUNT(*) as total_pengguna, '
                .'COALESCE(SUM(CASE WHEN hak_akses = ? THEN 1 ELSE 0 END), 0) as total_admin, '
                .'COALESCE(SUM(CASE WHEN hak_akses = ? THEN 1 ELSE 0 END), 0) as total_petugas',
                [self::HAK_AKSES_ADMIN, self::HAK_AKSES_PETUGAS]
            )
            ->first();

        return [
            'total_pengguna' => (int) ($data->total_pengguna ?? 0),
            'total_admin' => (int) ($data->total_admin ?? 0),
            'total_petugas' => (int) ($data->total_petugas ?? 0),
        ];
    }

    /**
     * Apakah pengguna ini seorang admin?
     */
    public function isAdmin(): bool
    {
        return $this->hak_akses === self::HAK_AKSES_ADMIN;
    }

    /**
     * Apakah akun ini aktif?
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_AKTIF;
    }
}
