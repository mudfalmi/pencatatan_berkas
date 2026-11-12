<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login berbasis username & password.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        try {
            $berhasil = Auth::attempt($credentials, $remember);
        } catch (\RuntimeException $e) {
            // Hash tersimpan tidak valid (mis. password plaintext lama di database)
            $berhasil = false;
        }

        if (! $berhasil) {
            // Kredensial salah -> kembali ke halaman login dengan pesan error.
            // Redirect eksplisit (bukan melempar ValidationException) supaya
            // user selalu mendarat di /login, tidak di halaman lain.
            return redirect()->route('login')
                ->withErrors(['username' => 'Username atau password salah.'])
                ->withInput($request->only('username'));
        }

        /** @var User $user */
        $user = Auth::user();

        if (! $user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['username' => 'Akun Anda berstatus nonaktif. Hubungi administrator.']);
        }

        $request->session()->regenerate();

        // Berhasil login -> ke dashboard sesuai hak akses (atau halaman
        // tujuan semula bila user sebelumnya diarahkan ke halaman tertaut).
        return redirect()->intended(
            $user->hak_akses === User::HAK_AKSES_PETUGAS
                ? route('petugas.dashboard')
                : route('admin.dashboard')
        );
    }

    /**
     * Proses logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
