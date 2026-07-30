<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-2xl">
        <h2 class="text-xl font-bold text-sky-400 mb-1 text-center">Reset Password</h2>
        <p class="text-xs text-slate-400 text-center mb-6">Masukkan email terdaftar Anda untuk menerima OTP</p>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-3 rounded-lg text-sm mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Email</label>
                <input type="email" name="email" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-semibold py-2.5 rounded-lg text-sm transition">Kirim Kode OTP Reset</button>
        </form>
    </div>
</body>
</html>
