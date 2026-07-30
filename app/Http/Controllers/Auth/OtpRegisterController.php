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
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        session([
            'register_data' => [
                'name' => $request->name,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
            ]
        ]);

        $otp = OtpService::generateOtp($request->email, 'registration');
        Notification::route('mail', $request->email)
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
