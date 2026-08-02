<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#09090b] text-zinc-100 flex items-center justify-center min-h-screen p-4 antialiased">
    <div class="w-full max-w-md bg-[#18181b] border border-zinc-800 rounded-2xl p-8 shadow-2xl">
        <!-- Logo Gondowangi Header -->
        <div class="flex flex-col items-center mb-6">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Gondowangi" class="h-12">
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Register</h1>
            <p class="text-xs text-zinc-400 mt-1">GTK Portal (AMS & IT Helpdesk System)</p>
        </div>

        @if(session('error'))
            <div class="bg-red-950/50 border border-red-800 text-red-300 p-3 rounded-lg text-xs mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- 🍯 Honeypot Trap Field (Invisible for humans, traps automated bots) -->
            <div style="display:none !important; visibility:hidden !important; position:absolute; left:-9999px;" aria-hidden="true">
                <input type="text" name="hp_website" tabindex="-1" autocomplete="off">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Email Perusahaan <span class="text-xs text-emerald-400 font-normal">(@gondowangi.com / @gondowangi.co.id)</span> <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@gondowangi.com" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <!-- 🧩 Lightweight Math Captcha -->
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Verifikasi Keamanan: Berapa <span class="font-bold text-emerald-400">{{ session('captcha_question', '5 + 3') }}</span> = ? <span class="text-red-500">*</span></label>
                <input type="number" name="captcha_answer" placeholder="Masukkan hasil penjumlahan" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none transition">
            </div>

            <button type="submit" class="w-full bg-[#0d630d] hover:bg-[#0a4d0a] text-white font-medium py-2.5 rounded-lg text-sm transition duration-200 mt-2 shadow-lg shadow-emerald-950/40">Daftar & Kirim Kode OTP</button>
        </form>

        <div class="mt-6 pt-4 border-t border-zinc-800 text-center">
            <p class="text-xs text-zinc-400">Sudah memiliki akun? <a href="/admin/login" class="text-emerald-400 hover:underline font-medium">Sign in</a></p>
        </div>
    </div>
</body>
</html>
