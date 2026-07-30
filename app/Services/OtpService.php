<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class OtpService
{
    /**
     * Generate 6-digit OTP & simpan di Cache (berlaku 5 menit).
     */
    public static function generateOtp(string $email, string $type = 'registration'): string
    {
        $otp = (string) random_int(100000, 999999);
        $key = "otp_{$type}_" . md5(strtolower(trim($email)));

        // Simpan ke Cache selama 300 detik (5 menit)
        Cache::put($key, [
            'code' => $otp,
            'attempts' => 0
        ], now()->addMinutes(5));

        return $otp;
    }

    /**
     * Verifikasi kode OTP dari user.
     */
    public static function verifyOtp(string $email, string $otpInput, string $type = 'registration'): bool
    {
        $key = "otp_{$type}_" . md5(strtolower(trim($email)));
        $cached = Cache::get($key);

        if (!$cached) {
            return false; // OTP kadaluarsa / tidak ditemukan
        }

        // Batasi percobaan salah max 3x
        if ($cached['attempts'] >= 3) {
            Cache::forget($key);
            return false;
        }

        if ($cached['code'] === trim($otpInput)) {
            Cache::forget($key); // Hapus OTP setelah sukses
            return true;
        }

        // Tambah counter percobaan gagal
        $cached['attempts']++;
        Cache::put($key, $cached, now()->addMinutes(5));

        return false;
    }
}

