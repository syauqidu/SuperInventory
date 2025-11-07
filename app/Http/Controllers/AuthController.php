<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     * Frontend by: Nijar
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // validasi dulu
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // cek credential valid atau nggak
        if (Auth::validate($credentials)) {
            $user = Auth::getProvider()->retrieveByCredentials($credentials);

            // cek apakah user aktifin 2FA
            if ($user->two_factor_enabled) {
                // generate kode 2FA
                $twoFactorController = new \App\Http\Controllers\TwoFactorController();
                $code = $twoFactorController->generateTwoFactorCode($user->id);

                // simpen user id di session buat verifikasi nanti
                session([
                    '2fa_user_id' => $user->id,
                    '2fa_remember' => $remember,
                ]);

                return redirect()->route('two-factor.verify')
                    ->with('success', 'Kode verifikasi 2FA telah dikirim. Silakan masukkan kode.');
            }

            // kalo ga pake 2FA langsung login aja
            Auth::login($user, $remember);
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        // kalo gagal login
        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah. Silakan coba lagi.');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        $name = Auth::user()->name;
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Sampai jumpa, ' . $name . '! Anda berhasil logout.');
    }
}
