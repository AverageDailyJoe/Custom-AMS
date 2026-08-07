<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-2">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-500/10 text-primary-600 dark:text-primary-400 flex items-center justify-center font-bold text-2xl">
                    ⚡
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Selamat Datang di Portal Service User IT
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Laporkan kendala perangkat IT, jaringan, printer, atau sistem aplikasi secara cepat & terpantau.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ url('/user/user-tickets/create') }}" 
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-500 focus:outline-none transition shadow">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Tiket Kendala IT Baru
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
