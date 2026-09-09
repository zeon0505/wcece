<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpPasswordMail;
use App\Models\PasswordOtpCode;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class OtpPasswordController extends Controller
{
    /**
     * Step 1: Show form to enter email.
     */
    public function showEmailForm(): View
    {
        return view('auth.otp-forgot-password');
    }

    /**
     * Step 1: Send OTP to email.
     */
    public function sendOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Rate limit: max 3 attempts per 10 minutes per IP
        $key = 'otp-send:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
        }
        RateLimiter::hit($key, 600);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        // If email not registered, show error and stay on form
        if (!$user) {
            return back()->withErrors(['email' => 'Maaf, email tidak terdaftar. Silakan periksa kembali atau daftar akun baru.'])->withInput();
        }

        // Invalidate old OTPs for this email
        PasswordOtpCode::where('email', $email)->where('used', false)->update(['used' => true]);

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        PasswordOtpCode::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(10),
            'used' => false,
        ]);

        try {
            Mail::to($email)->send(new OtpPasswordMail($otpCode, $email));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP mail failed: ' . $e->getMessage());
        }

        session()->put('otp_email', $email);
        session()->put('otp_sent_at', now()->timestamp);

        return redirect()->route('otp.verify.form');
    }

    /**
     * Step 2: Show form to enter OTP.
     */
    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('otp.email.form');
        }
        return view('auth.otp-verify', compact('email'));
    }

    /**
     * Resend OTP to the same email.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('otp.email.form')->withErrors(['email' => 'Sesi habis, ulangi dari awal.']);
        }

        // Enforce 60-second cooldown per IP
        $key = 'otp-resend:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return redirect()->route('otp.verify.form')->withErrors(['otp_code' => "Tunggu {$seconds} detik sebelum meminta kode baru."]);
        }
        RateLimiter::hit($key, 600);

        $user = User::where('email', $email)->first();
        if (!$user) {
            session()->put('otp_sent_at', now()->timestamp);
            return redirect()->route('otp.verify.form')->with('status', 'Kode OTP baru telah dikirim (jika email terdaftar).');
        }

        // Invalidate old OTPs
        PasswordOtpCode::where('email', $email)->where('used', false)->update(['used' => true]);

        // New OTP
        $otpCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        PasswordOtpCode::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'expires_at' => now()->addMinutes(10),
            'used' => false,
        ]);

        try {
            Mail::to($email)->send(new OtpPasswordMail($otpCode, $email));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP resend mail failed: ' . $e->getMessage());
        }

        session()->put('otp_sent_at', now()->timestamp);

        return redirect()->route('otp.verify.form')->with('status', '✓ Kode OTP baru telah dikirim ke email Anda.');
    }


    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $email = strtolower(trim($request->email));

        // Rate limit: max 5 OTP verify attempts per 5 minutes per IP
        $key = 'otp-verify:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['otp_code' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."]);
        }
        RateLimiter::hit($key, 300);

        $otp = PasswordOtpCode::where('email', $email)
            ->where('otp_code', $request->otp_code)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otp || $otp->isExpired()) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kadaluarsa.'])->withInput();
        }

        // Mark OTP as verified (not used yet, just verified)
        session(['otp_verified_email' => $email, 'otp_verified_id' => $otp->id]);

        return redirect()->route('otp.reset.form');
    }

    /**
     * Step 3: Show form to set new password.
     */
    public function showResetForm(Request $request): View|RedirectResponse
    {
        if (!session('otp_verified_email')) {
            return redirect()->route('otp.email.form');
        }
        $email = session('otp_verified_email');
        return view('auth.otp-reset-password', compact('email'));
    }

    /**
     * Step 3: Update password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $email = session('otp_verified_email');
        $otpId = session('otp_verified_id');

        if (!$email || !$otpId) {
            return redirect()->route('otp.email.form')->withErrors(['email' => 'Sesi tidak valid, ulangi dari awal.']);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('otp.email.form')->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        // Mark OTP as used
        PasswordOtpCode::where('id', $otpId)->update(['used' => true]);

        // Update password
        $user->update(['password' => Hash::make($request->password)]);

        // Clear session
        session()->forget(['otp_email', 'otp_verified_email', 'otp_verified_id']);

        return redirect()->route('login')->with('status', '✓ Password berhasil diperbarui. Silakan masuk dengan password baru Anda.');
    }
}
