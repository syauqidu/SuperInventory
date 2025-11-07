<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\TwoFactorCode;
use App\Models\User;

class TwoFactorController extends Controller
{
    public function showSettings()
    {
        return view('auth.two-factor-settings');
    }

    public function enableTwoFactor()
    {
        $user = Auth::user();
        $user->two_factor_enabled = true;
        $user->save();

        return redirect()->back()->with('success', 'Autentikasi 2 Faktor berhasil diaktifkan.');
    }

    public function disableTwoFactor()
    {
        $user = Auth::user();
        $user->two_factor_enabled = false;
        $user->save();

        TwoFactorCode::where('user_id', $user->id)->delete();

        return redirect()->back()->with('success', 'Autentikasi 2 Faktor berhasil dinonaktifkan.');
    }

    public function generateTwoFactorCode($userId)
    {
        // invalidate semua kode lama yang belum dipake
        TwoFactorCode::where('user_id', $userId)
            ->where('used', false)
            ->update(['used' => true]);

        // bikin kode random 6 digit
        $code = sprintf('%06d', random_int(0, 999999));

        $twoFactorCode = TwoFactorCode::create([
            'user_id' => $userId,
            'code' => $code,
            'expires_at' => now()->addMinutes(5), // expired dalam 5 menit
            'used' => false,
        ]);

        // TODO: kirim via SMS atau email
        // untuk sekarang just return aja
        return $twoFactorCode;
    }

    public function showVerifyForm()
    {
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-verify');
    }

    public function verifyTwoFactorCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ], [
            'code.required' => 'Kode verifikasi harus diisi.',
            'code.digits' => 'Kode verifikasi harus 6 digit.',
        ]);

        $userId = session('2fa_user_id');
        
        if (!$userId) {
            return back()->withErrors(['code' => 'Sesi login tidak valid. Silakan login kembali.']);
        }

        // cari kode yang sesuai
        $twoFactorCode = TwoFactorCode::where('user_id', $userId)
            ->where('code', $request->code)
            ->where('used', false)
            ->first();

        if (!$twoFactorCode) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        // validasi expiry
        if ($twoFactorCode->isExpired()) {
            return back()->withErrors(['code' => 'Kode verifikasi sudah kadaluarsa. Silakan minta kode baru.']);
        }

        // mark sebagai sudah dipakai
        $twoFactorCode->used = true;
        $twoFactorCode->save();

        // login user
        $user = User::find($userId);
        Auth::login($user, session('2fa_remember', false));

        // clear session
        session()->forget(['2fa_user_id', '2fa_remember']);

        return redirect()->route('dashboard')->with('success', 'Login berhasil! Selamat datang di SuperInventory.');
    }

    public function resendCode(Request $request)
    {
        $userId = session('2fa_user_id');
        
        if (!$userId) {
            return redirect()->route('two-factor.verify')->withErrors(['code' => 'Sesi login tidak valid.']);
        }

        // rate limiting - cegah spam request
        $cacheKey = '2fa_resend_' . $userId;
        
        if (Cache::has($cacheKey)) {
            return redirect()->route('two-factor.verify')->withErrors(['rate_limit' => 'Silakan tunggu 60 detik sebelum meminta kode baru.']);
        }

        Cache::put($cacheKey, true, 60); // cache selama 60 detik

        $this->generateTwoFactorCode($userId);

        return redirect()->route('two-factor.verify')->with('success', 'Kode verifikasi baru telah dikirim.');
    }
}
