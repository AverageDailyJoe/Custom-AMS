<div class="p-2 space-y-4 text-xs">
    <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 dark:bg-gray-800/60 rounded-lg border border-gray-200 dark:border-gray-700">
        <div>
            <span class="text-gray-500 dark:text-gray-400 block font-semibold">No. Tiket IT</span>
            <span class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $ticket->ticket_number }}</span>
        </div>
        <div>
            <span class="text-gray-500 dark:text-gray-400 block font-semibold">Pelapor / Karyawan</span>
            <span class="font-bold text-gray-900 dark:text-gray-100">{{ $ticket->reporter_name }} ({{ $ticket->reporter_department ?? '-' }})</span>
        </div>
        <div>
            <span class="text-gray-500 dark:text-gray-400 block font-semibold">Tgl Terdaftar / Jadwal</span>
            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($ticket->scheduled_date)->format('d M Y') }}</span>
        </div>
        <div>
            <span class="text-gray-500 dark:text-gray-400 block font-semibold">Teknisi IT Penanggung Jawab</span>
            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $ticket->assignedToUser?->name ?? '-' }}</span>
        </div>
    </div>

    <div class="p-3 bg-amber-50/60 dark:bg-amber-950/30 rounded-lg border border-amber-200 dark:border-amber-800/50">
        <span class="text-amber-800 dark:text-amber-300 font-bold block mb-1">🚨 Subjek & Kendala Yang Dilaporkan:</span>
        <p class="font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $ticket->subject }}</p>
        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $ticket->description ?? 'Tidak ada rincian kendala tambahan.' }}</p>
    </div>

    <div class="p-3 bg-emerald-50/60 dark:bg-emerald-950/30 rounded-lg border border-emerald-200 dark:border-emerald-800/50">
        <span class="text-emerald-800 dark:text-emerald-300 font-bold block mb-1">🛠️ Catatan Hasil Pengerjaan IT / Solusi Terpasang:</span>
        <p class="text-gray-800 dark:text-gray-200 font-medium whitespace-pre-line">{{ $ticket->resolution_notes ?: 'Belum ada catatan solusi / Tiket masih dalam penanganan.' }}</p>
    </div>

    @if($ticket->room_notes)
        <div class="p-2 bg-blue-50 dark:bg-blue-950/30 rounded border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-200">
            <strong>📍 Catatan Lokasi Sementara / Meeting:</strong> {{ $ticket->room_notes }}
        </div>
    @endif
</div>
