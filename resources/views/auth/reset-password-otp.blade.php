<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password Baru - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#09090b] text-zinc-100 flex items-center justify-center min-h-screen p-4 antialiased">
    <div class="w-full max-w-md bg-[#18181b] border border-zinc-800 rounded-2xl p-8 shadow-2xl">
        <div class="flex flex-col items-center mb-6">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Gondowangi" class="h-12">
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Password Baru</h1>
            <p class="text-xs text-zinc-400 mt-1">Masukkan kode OTP dan kata sandi baru Anda</p>
        </div>

        @if(session('error'))
            <div class="bg-red-950/50 border border-red-800 text-red-300 p-3 rounded-lg text-xs mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Kode OTP 6-Digit <span class="text-red-500">*</span></label>
                <input type="text" name="otp" maxlength="6" required placeholder="123456" class="w-full bg-[#27272a] border border-emerald-600 text-center font-mono text-xl tracking-widest py-2.5 rounded-lg text-emerald-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-zinc-300 mb-1.5">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required class="w-full bg-[#27272a] border border-zinc-700 text-white rounded-lg px-3.5 py-2.5 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-[#0d630d] hover:bg-[#0a4d0a] text-white font-medium py-2.5 rounded-lg text-sm transition duration-200 shadow-lg shadow-emerald-950/40">Perbarui Password</button>
        </form>
    </div>

    @if(session('reset_success_modal'))
    <script>
        Swal.fire({
            title: 'Password Berhasil Diperbarui!',
            text: 'Kata sandi akun Anda telah diperbarui. Silakan login dengan password baru.',
            icon: 'success',
            background: '#18181b',
            color: '#fff',
            confirmButtonColor: '#0d630d',
            confirmButtonText: 'Lanjut Sign In',
            allowOutsideClick: false
        }).then((result) => {
            window.location.href = "/admin/login";
        });
    </script>
    @endif
</body>
</html>
