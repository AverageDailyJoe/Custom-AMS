<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-2xl">
        <h2 class="text-2xl font-bold text-center text-sky-400 mb-1">GTK Portal</h2>
        <p class="text-xs text-slate-400 text-center mb-6">PT Gondowangi Kosmetika</p>

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500 text-red-300 p-3 rounded-lg text-sm mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Email Perusahaan</label>
                <input type="email" name="email" placeholder="nama@gondowangi.com" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase text-slate-300 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm focus:border-sky-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-sky-600 hover:bg-sky-500 text-white font-semibold py-2.5 rounded-lg text-sm transition">Daftar & Kirim OTP</button>
        </form>
    </div>
</body>
</html>
