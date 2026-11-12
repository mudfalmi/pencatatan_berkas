<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Dashboard admin: ringkasan surat masuk, arsip digital & hak akses pengguna.
     */
    public function dashboard(): View
    {
        // Dua query agregat (pengguna & surat) untuk seluruh kartu statistik.
        $stats = array_merge(User::ringkasan(), Surat::ringkasan());

        // Ringkasan akun pengguna: 5 data terbaru saja (tanpa kolom aksi).
        $penggunaTerbaru = User::query()
            ->select(['id', 'nama_lengkap', 'username', 'hak_akses', 'status'])
            ->latest('id')
            ->limit(5)
            ->get();

        // Fokus utama dasbor: 5 surat terakhir (tanpa kolom tanda_tangan
        // yang berukuran besar).
        $suratTerbaru = Surat::query()
            ->ringkas()
            ->latest('tanggal_surat')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'penggunaTerbaru', 'suratTerbaru'));
    }

    /**
     * Daftar akun & hak akses pengguna.
     */
    public function index(Request $request): View
    {
        $users = User::query()
            // Kolom minimal: hash password & remember_token tidak perlu dimuat.
            ->select(['id', 'nama_lengkap', 'username', 'hak_akses', 'status'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $cari = $request->string('q')->toString();

                $query->where(function ($sub) use ($cari) {
                    $sub->where('nama_lengkap', 'like', "%{$cari}%")
                        ->orWhere('username', 'like', "%{$cari}%");
                });
            })
            ->when($request->filled('hak_akses'), function ($query) use ($request) {
                $query->where('hak_akses', $request->string('hak_akses')->toString());
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Form tambah akun.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Simpan akun baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z0-9._]+$/', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'hak_akses' => ['required', Rule::in([User::HAK_AKSES_ADMIN, User::HAK_AKSES_PETUGAS])],
            'status' => ['required', Rule::in([User::STATUS_AKTIF, User::STATUS_NONAKTIF])],
        ], [
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, atau garis bawah.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create($data);
        Cache::forget(User::CACHE_KEY_NONAKTIF);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun \"{$data['nama_lengkap']}\" berhasil ditambahkan.");
    }

    /**
     * Form edit akun.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Perbarui akun.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'min:3', 'max:255', 'regex:/^[a-zA-Z0-9._]+$/',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'hak_akses' => ['required', Rule::in([User::HAK_AKSES_ADMIN, User::HAK_AKSES_PETUGAS])],
            'status' => ['required', Rule::in([User::STATUS_AKTIF, User::STATUS_NONAKTIF])],
        ], [
            'username.regex' => 'Username hanya boleh berisi huruf, angka, titik, atau garis bawah.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Jika password dikosongkan, pertahankan password lama
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Cegah admin menurunkan hak aksesnya sendiri / menonaktifkan akunnya sendiri
        if ($user->id === $request->user()->id) {
            if ($data['hak_akses'] !== User::HAK_AKSES_ADMIN) {
                return back()->with('error', 'Anda tidak dapat mengubah hak akses akun Anda sendiri menjadi Petugas.');
            }

            if ($data['status'] !== User::STATUS_AKTIF) {
                return back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
            }
        }

        $user->update($data);
        Cache::forget(User::CACHE_KEY_NONAKTIF);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun \"{$user->nama_lengkap}\" berhasil diperbarui.");
    }

    /**
     * Hapus akun.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $nama = $user->nama_lengkap;
        $user->delete();
        Cache::forget(User::CACHE_KEY_NONAKTIF);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Akun \"{$nama}\" berhasil dihapus.");
    }
}
