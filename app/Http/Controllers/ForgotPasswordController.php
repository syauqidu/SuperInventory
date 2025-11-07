<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        // generate random token
        $token = Str::random(60);
        
        // save ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // TODO: nanti kirim email beneran kalo udah production
        // sekarang cuma redirect ke login dulu
        return redirect()->route('login')->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek email Anda.');
    }

    public function showResetPasswordForm($token, Request $request)
    {
        $email = $request->query('email');
        
        // cek apakah token ada di database
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('login')->with('error', 'Token reset password tidak valid.');
        }

        // verify token hash
        if (!Hash::check($token, $resetRecord->token)) {
            return redirect()->route('login')->with('error', 'Token reset password tidak valid.');
        }

        // cek kadaluarsa (1 jam)
        if (now()->diffInHours($resetRecord->created_at) > 1) {
            return redirect()->route('login')->with('error', 'Token reset password sudah kadaluarsa.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // cek expired atau belum (1 jam)
        $createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
        if ($createdAt->addHour()->isPast()) {
            // hapus token yang udah expired
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();
            return redirect()->route('login')->with('error', 'Token reset password sudah kadaluarsa. Silakan minta link reset baru.');
        }

        // update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // hapus token setelah berhasil reset
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
