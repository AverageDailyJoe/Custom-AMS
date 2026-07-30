<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#09090b] text-zinc-100 flex items-center justify-center min-h-screen p-4 antialiased">
    <div class="w-full max-w-md bg-[#18181b] border border-zinc-800 rounded-2xl p-8 shadow-2xl">
        <div class="flex flex-col items-center mb-6">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-7 h-7 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                <span class="text-xs tracking-widest font-bold text-emerald-500 uppercase">GONDOWANGI</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Reset Password</h1>
            <p class="text-xs text-zinc-400 mt-1">Masukkan email terdaftar Anda untuk menerima kode OTP</p>
        </div>

        @if(session('error'))
            <div class="bg-red-950/50 border border-red-800 text-red-300 p-3 rounded-lg text-xs mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Email address <span class="text-red-500">*</span></label>
                <input type="email" name="email" placeholder="nama@gondowangi.com" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <button type="submit" class="w-full bg-[#15803d] hover:bg-[#166534] text-white font-medium py-2.5 rounded-lg text-sm transition duration-200 shadow-lg shadow-emerald-950/40">Kirim Kode OTP Reset</button>
        </form>

        <div class="mt-6 pt-4 border-t border-zinc-800 text-center">
            <a href="/admin/login" class="text-xs text-zinc-400 hover:text-emerald-400 transition">Kembali ke Sign in</a>
        </div>
    </div>
</body>
</html>
