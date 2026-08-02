<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SendOtpNotification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class OtpRegisterController extends Controller
{
    public function showRegisterForm()
    {
        $num1 = random_int(2, 9);
        $num2 = random_int(1, 9);

        session([
            'captcha_answer' => $num1 + $num2,
            'captcha_question' => "{$num1} + {$num2}",
            'register_form_time' => time(),
        ]);

        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 🍯 LAYER 1: Honeypot Trap (Anti-Bot)
        if ($request->filled('hp_website')) {
            // Fake success response to trick bot into stopping attempts (ZERO emails sent!)
            return redirect()->route('otp.verify.show')->with('success', 'Kode OTP telah dikirimkan ke email Anda.');
        }

        // ⏱️ LAYER 2: Form Submission Speed Control (< 2.5s is automated bot script)
        $formTime = session('register_form_time', 0);
        if ($formTime > 0 && (time() - $formTime) < 2) {
            return back()->withInput()->with('error', 'Permintaan terlalu cepat. Silakan isi form dengan teliti.');
        }

        // 🧩 LAYER 5: Math Captcha Validation
        $expectedCaptcha = session('captcha_answer');
        if ($expectedCaptcha !== null && (int) $request->input('captcha_answer') !== (int) $expectedCaptcha) {
            return back()->withInput()->with('error', 'Jawaban verifikasi matematika salah. Silakan coba lagi.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $cleanEmail = strtolower(trim($request->email));
        $emailDomain = strtolower(substr(strrchr($cleanEmail, "@"), 1));

        // 🏢 Corporate Domain Restriction (Backend Silent Check - Generic Error to Prevent Info Leak)
        $allowedDomains = ['gondowangi.com', 'gondowangi.co.id'];
        if (!in_array($emailDomain, $allowedDomains, true)) {
            return back()->withInput()->with('error', 'Alamat email tidak valid atau tidak memiliki otoritas untuk pendaftaran.');
        }

        // ⏳ LAYER 4: OTP Email Cooldown Lock (120 Minutes)
        $cooldownCheck = OtpService::canSendOtp($cleanEmail, 'registration');
        if (!$cooldownCheck['can_send']) {
            return back()->withInput()->with('error', $cooldownCheck['message']);
        }

        session([
            'register_data' => [
                'name' => $request->name,
                'email' => $cleanEmail,
                'password' => Hash::make($request->password),
            ]
        ]);

        $otp = OtpService::generateOtp($cleanEmail, 'registration');
        Notification::route('mail', $cleanEmail)
            ->notify(new SendOtpNotification($otp, 'Registrasi Akun GTK Portal'));

        return redirect()->route('otp.verify.show')->with('success', 'Kode OTP telah dikirimkan ke email Anda.');
    }

    public function showVerifyForm()
    {
        if (!session()->has('register_data') && !session()->has('success_modal')) {
            return redirect()->route('register')->with('error', 'Sesi registrasi berakhir. Silakan isi form kembali.');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $registerData = session('register_data');

        if (!$registerData) {
            return redirect()->route('register')->with('error', 'Sesi registrasi tidak ditemukan.');
        }

        $isValid = OtpService::verifyOtp($registerData['email'], $request->otp, 'registration');

        if (!$isValid) {
            return back()->with('error', 'Kode OTP salah atau telah kadaluarsa (berlaku 5 menit).');
        }

        $user = User::create([
            'name' => $registerData['name'],
            'email' => $registerData['email'],
            'password' => $registerData['password'],
            'email_verified_at' => now(),
        ]);

        session()->forget('register_data');

        // Tampilkan Modal Sukses sebelum Redirect ke Login
        return redirect()->route('otp.verify.show')->with('success_modal', true);
    }
}
