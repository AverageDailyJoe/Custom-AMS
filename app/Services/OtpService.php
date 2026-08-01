<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class OtpService
{
    /**
     * Cooldown time in seconds between OTP requests for the same email (2 minutes)
     */
    public const COOLDOWN_SECONDS = 120;

    /**
     * Check if an OTP can be sent to the given email address.
     * Enforces a 2-minute cooldown per email to prevent spam.
     */
    public static function canSendOtp(string $email, string $type = 'registration'): array
    {
        $cooldownKey = "otp_cooldown_{$type}_" . md5(strtolower(trim($email)));
        $lastSentAt = Cache::get($cooldownKey);

        if ($lastSentAt) {
            $elapsed = time() - $lastSentAt;
            $remaining = static::COOLDOWN_SECONDS - $elapsed;

            if ($remaining > 0) {
                return [
                    'can_send' => false,
                    'wait_seconds' => $remaining,
                    'message' => "Harap tunggu {$remaining} detik sebelum meminta kode OTP baru ke email yang sama.",
                ];
            }
        }

        return ['can_send' => true, 'wait_seconds' => 0, 'message' => ''];
    }

    /**
     * Generate 6-digit OTP & simpan di Cache (berlaku 5 menit).
     */
    public static function generateOtp(string $email, string $type = 'registration'): string
    {
        $cleanEmail = strtolower(trim($email));
        $otp = (string) random_int(100000, 999999);
        $key = "otp_{$type}_" . md5($cleanEmail);
        $cooldownKey = "otp_cooldown_{$type}_" . md5($cleanEmail);

        // Record cooldown timestamp (120 seconds)
        Cache::put($cooldownKey, time(), now()->addSeconds(static::COOLDOWN_SECONDS));

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
