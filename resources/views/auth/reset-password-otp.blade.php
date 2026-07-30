<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password Baru - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-2xl">
        <h2 class="text-xl font-bold text-sky-400 mb-4 text-center">Set Password Baru</h2>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-3 rounded-lg text-sm mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Kode OTP 6-Digit</label>
                <input type="text" name="otp" maxlength="6" required placeholder="123456" class="w-full bg-slate-900 border border-sky-500 text-center font-mono text-xl tracking-widest py-2 rounded-lg text-sky-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Password Baru</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-semibold py-2.5 rounded-lg text-sm transition">Perbarui Password</button>
        </form>
    </div>
</body>
</html>

