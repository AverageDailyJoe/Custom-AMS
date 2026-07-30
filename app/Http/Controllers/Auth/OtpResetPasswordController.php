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
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = strtolower(trim($request->email));

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak terdaftar di sistem GTK Portal.');
        }

        session(['reset_email' => $email]);

        $otp = OtpService::generateOtp($email, 'reset_password');
        Notification::route('mail', $email)
            ->notify(new SendOtpNotification($otp, 'Reset Password GTK Portal'));

        return redirect()->route('password.reset.verify.show')->with('success', 'Kode OTP reset password telah dikirim ke email Anda.');
    }

    public function showResetForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request');
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

        return redirect('/admin/login')->with('success', 'Password berhasil diperbarui. Silakan login.');
    }
}

