<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification
{
    use Queueable;

    public string $otpCode;
    public string $typeLabel;

    public function __construct(string $otpCode, string $typeLabel = 'Registrasi Akun')
    {
        $this->otpCode = $otpCode;
        $this->typeLabel = $typeLabel;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("[GTK Portal] Kode OTP {$this->typeLabel}: {$this->otpCode}")
            ->view('emails.otp', [
                'otpCode' => $this->otpCode,
                'typeLabel' => $this->typeLabel,
            ]);
    }
}

