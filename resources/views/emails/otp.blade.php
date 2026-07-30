<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Verifikasi GTK Portal</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #eaeff5; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { color: #1e293b; margin: 0; font-size: 22px; }
        .otp-box { background: #f1f5f9; border-radius: 8px; text-align: center; padding: 20px; margin: 25px 0; border: 1px dashed #cbd5e1; }
        .otp-code { font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #0284c7; font-family: monospace; }
        .footer { font-size: 12px; color: #64748b; text-align: center; margin-top: 25px; border-top: 1px solid #eaeff5; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>GTK Portal System</h2>
            <p style="color: #64748b; font-size: 14px; margin-top: 5px;">PT Gondowangi Kosmetika</p>
        </div>
        
        <p style="color: #334155;">Halo,</p>
        <p style="color: #334155;">Gunakan kode OTP di bawah ini untuk memverifikasi permohonan <strong>{{ $typeLabel }}</strong> Anda:</p>
        
        <div class="otp-box">
            <div class="otp-code">{{ $otpCode }}</div>
            <p style="color: #ef4444; font-size: 12px; margin-bottom: 0; margin-top: 8px;">*Berlaku selama 5 menit. Jangan bagikan kode ini kepada siapapun.</p>
        </div>

        <p style="color: #64748b; font-size: 13px;">Jika Anda tidak merasa melakukan tindakan ini, silakan abaikan email ini.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} PT Gondowangi Kosmetika - AMS & IT Helpdesk System
        </div>
    </div>
</body>
</html>
