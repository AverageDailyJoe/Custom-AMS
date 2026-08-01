<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class OtpResetPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        $num1 = random_int(2, 9);
        $num2 = random_int(1, 9);

        session([
            'reset_captcha_answer' => $num1 + $num2,
            'reset_captcha_question' => "{$num1} + {$num2}",
            'reset_form_time' => time(),
        ]);

        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        // 🍯 LAYER 1: Honeypot Trap (Anti-Bot)
        if ($request->filled('hp_website')) {
            return redirect()->route('password.reset.verify.show')->with('success', 'Kode OTP reset password telah dikirim ke email Anda.');
        }

        // ⏱️ LAYER 2: Form Submission Speed Control (< 2.5s is automated bot script)
        $formTime = session('reset_form_time', 0);
        if ($formTime > 0 && (time() - $formTime) < 2) {
            return back()->withInput()->with('error', 'Permintaan terlalu cepat. Silakan isi form dengan teliti.');
        }

        // 🧩 LAYER 5: Math Captcha Validation
        $expectedCaptcha = session('reset_captcha_answer');
        if ($expectedCaptcha !== null && (int) $request->input('captcha_answer') !== (int) $expectedCaptcha) {
            return back()->withInput()->with('error', 'Jawaban verifikasi matematika salah. Silakan coba lagi.');
        }

        $request->validate(['email' => 'required|email']);
        $email = strtolower(trim($request->email));

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar di sistem GTK Portal.');
        }

        // ⏳ LAYER 4: OTP Email Cooldown Lock (2 Minutes)
        $cooldownCheck = OtpService::canSendOtp($email, 'reset_password');
        if (!$cooldownCheck['can_send']) {
            return back()->withInput()->with('error', $cooldownCheck['message']);
        }

        session(['reset_email' => $email]);

        $otp = OtpService::generateOtp($email, 'reset_password');
        Notification::route('mail', $email)
            ->notify(new SendOtpNotification($otp, 'Reset Password GTK Portal'));

        return redirect()->route('password.reset.verify.show')->with('success', 'Kode OTP reset password telah dikirim ke email Anda.');
    }

    public function showResetForm()
    {
        if (!session()->has('reset_email') && !session()->has('reset_success_modal')) {
            return redirect()->route('password.request')->with('error', 'Sesi reset password kadaluarsa.');
        }

        return view('auth.reset-password-otp');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Sesi reset password kadaluarsa.');
        }

        $isValid = OtpService::verifyOtp($email, $request->otp, 'reset_password');

        if (!$isValid) {
            return back()->with('error', 'Kode OTP salah atau telah kadaluarsa.');
        }

        $user = User::where('email', $email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        session()->forget('reset_email');

        // Tampilkan Modal Sukses sebelum Redirect ke Login
        return redirect()->route('password.reset.verify.show')->with('reset_success_modal', true);
    }
}
