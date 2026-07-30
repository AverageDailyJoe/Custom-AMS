<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - GTK Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#09090b] text-zinc-100 flex items-center justify-center min-h-screen p-4 antialiased">
    <div class="w-full max-w-md bg-[#18181b] border border-zinc-800 rounded-2xl p-8 shadow-2xl text-center">
        <!-- Logo Header -->
        <div class="flex flex-col items-center mb-6">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Gondowangi" class="h-12">
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Verifikasi OTP</h1>
            <p class="text-xs text-zinc-400 mt-1">Masukkan 6-digit kode OTP yang dikirim ke email Anda</p>
        </div>

        @if(session('error'))
            <div class="bg-red-950/50 border border-red-800 text-red-300 p-3 rounded-lg text-xs mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('otp.verify') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <input type="text" name="otp" maxlength="6" autofocus required placeholder="123456" class="w-full bg-[#27272a] border border-emerald-600 text-center font-mono text-3xl tracking-[0.4em] py-3.5 rounded-xl text-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <button type="submit" class="w-full bg-[#0d630d] hover:bg-[#0a4d0a] text-white font-medium py-2.5 rounded-lg text-sm transition duration-200">Verifikasi & Aktifkan Akun</button>
        </form>

        <div class="mt-6 pt-4 border-t border-zinc-800">
            <a href="{{ route('register') }}" class="text-xs text-zinc-400 hover:text-emerald-400 transition">Kembali ke Registrasi</a>
        </div>
    </div>

    @if(session('success_modal'))
    <script>
        Swal.fire({
            title: 'Registrasi Berhasil!',
            text: 'Akun Anda telah aktif. Anda akan di-redirect ke halaman Sign In.',
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
