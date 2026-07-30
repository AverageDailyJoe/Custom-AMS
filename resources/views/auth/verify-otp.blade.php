<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-2xl text-center">
        <h2 class="text-xl font-bold text-sky-400 mb-2">Verifikasi Kode OTP</h2>
        <p class="text-xs text-slate-300 mb-6">Kode OTP 6-digit telah dikirimkan ke email Anda. Masukkan kode tersebut di bawah ini:</p>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-3 rounded-lg text-sm mb-4">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-300 p-3 rounded-lg text-sm mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('otp.verify') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="otp" maxlength="6" autofocus required placeholder="123456" class="w-full bg-slate-900 border border-sky-500 text-center font-mono text-3xl tracking-widest py-3 rounded-lg text-sky-400 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-semibold py-2.5 rounded-lg text-sm transition">Verifikasi & Aktifkan Akun</button>
        </form>
    </div>
</body>
</html>

