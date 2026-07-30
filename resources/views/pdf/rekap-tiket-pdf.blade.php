<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN HISTORI LAYANAN & PERBAIKAN IT - PT GONDOWANGI</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #000; margin: 0; padding: 15px; background: #fff; line-height: 1.25; }
        .container { max-width: 1000px; margin: 0 auto; border: 1.5px solid #000; padding: 15px; }
        
        .header-title { text-align: center; font-weight: bold; font-size: 15px; font-family: Arial, Helvetica, sans-serif; margin: 0 0 3px; text-transform: uppercase; line-height: 1.2; }
        .header-sub { text-align: center; font-weight: bold; font-size: 14px; font-family: Arial, Helvetica, sans-serif; margin: 0 0 12px; text-transform: uppercase; line-height: 1.2; letter-spacing: 0.5px; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 10.5px; line-height: 1.2; }
        .meta-table td { padding: 2px 4px; vertical-align: bottom; border: none; }
        .border-bottom { border-bottom: 1px solid #000 !important; }

        .stat-grid { width: 100%; border-collapse: collapse; margin-bottom: 12px; text-align: center; }
        .stat-grid td { border: 1px solid #000; padding: 4px 6px; font-size: 10px; background: #f9fafb; }
        .stat-grid td strong { font-size: 11px; display: block; margin-top: 1px; color: #000; }

        .report-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .report-table th, .report-table td { border: 1px solid #000; padding: 4px 5px; font-size: 9.5px; vertical-align: top; }
        .report-table th { background: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }
        
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 15px; page-break-inside: avoid; }
        .sig-table th, .sig-table td { border: 1px solid #000; padding: 4px 2px; font-size: 9.5px; text-align: center; }
        .sig-table th { background: #f3f4f6; font-weight: bold; text-transform: uppercase; }
        .sig-space { height: 45px; }

        .no-print { margin-bottom: 12px; text-align: right; }
        .btn-print { background: #111827; color: white; border: none; padding: 8px 18px; font-size: 11px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .btn-print:hover { background: #1f2937; }
        
        @media print {
            .no-print { display: none; }
            .container { border: none; padding: 0; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD PDF REKAP TIKET IT</button>
        </div>

        <div class="header-title">PT. GONDOWANGI TRADISIONAL KOSMETIKA</div>
        <div class="header-sub">LAPORAN HISTORI LAYANAN & PERBAIKAN IT</div>

        <table class="meta-table">
            <tr>
                <td style="width: 12%;"><strong>Periode Laporan</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 46%;" class="border-bottom">{{ $periodLabel }}</td>
                <td style="width: 12%;"><strong>Filter Status</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 26%;" class="border-bottom">{{ $statusLabel }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ now()->translatedFormat('d F Y H:i') }} WIB</td>
                <td><strong>Dicetak Oleh</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ auth()->user()?->name ?? 'Staf IT' }}</td>
            </tr>
        </table>

        <!-- Summary SLA Statistics -->
        <table class="stat-grid">
            <tr>
                <td>Total Tiket Service<strong>{{ $stats['total'] }} Tiket</strong></td>
                <td>Selesai (Resolved/Closed)<strong>{{ $stats['resolved'] }} Tiket</strong></td>
                <td>Proses / Terjadwal<strong>{{ $stats['in_progress'] }} Tiket</strong></td>
                <td>Pending Sparepart (PPB)<strong>{{ $stats['pending_part'] }} Tiket</strong></td>
                <td>Tingkat SLA Tepat Waktu<strong>{{ $stats['sla_compliance'] }}%</strong></td>
            </tr>
        </table>

        <!-- Ticket Detail Table -->
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 12%;">No. Tiket</th>
                    <th style="width: 9%;">Jadwal / Tgl</th>
                    <th style="width: 16%;">Pelapor & Dept</th>
                    <th style="width: 10%;">ID Aset</th>
                    <th style="width: 22%;">Judul Kendala & Solusi</th>
                    <th style="width: 8%;">Prioritas</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">Teknisi IT</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $index => $ticket)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $ticket->ticket_number }}</td>
                    <td class="text-center">{{ $ticket->scheduled_date ? $ticket->scheduled_date->format('d/m/Y') : '-' }}</td>
                    <td>
                        <div class="font-bold">{{ $ticket->reporter_name }}</div>
                        <div style="font-size: 8.5px; color: #4b5563;">{{ $ticket->reporter_department }}</div>
                    </td>
                    <td class="text-center font-bold">{{ $ticket->asset_tag ?? '-' }}</td>
                    <td>
                        <div class="font-bold">{{ $ticket->subject }}</div>
                        @if($ticket->resolution_notes)
                        <div style="font-size: 8.5px; color: #047857; margin-top: 2px;">
                            <strong>Solusi:</strong> {{ $ticket->resolution_notes }}
                        </div>
                        @endif
                    </td>
                    <td class="text-center uppercase font-bold">{{ $ticket->priority }}</td>
                    <td class="text-center font-bold">
                        @switch($ticket->status)
                            @case('open') Open @break
                            @case('scheduled') Terjadwal @break
                            @case('in_progress') Progress @break
                            @case('pending_sparepart') Pending Part @break
                            @case('rescheduled') Rescheduled @break
                            @case('resolved') Resolved @break
                            @case('closed') Closed @break
                            @default {{ $ticket->status }}
                        @endswitch
                    </td>
                    <td>{{ $ticket->assignedToUser?->name ?? 'Tim IT' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px;">Tidak ada data tiket maintenance IT untuk periode/filter ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signature Matrix -->
        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 25%;">DIBUAT OLEH</th>
                    <th style="width: 25%;">DIPERIKSA OLEH</th>
                    <th style="width: 25%;">DISETUJUI OLEH</th>
                    <th style="width: 25%;">MENGETAHUI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: bottom;">
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ auth()->user()?->name ?? 'Staf IT' }}</u></div>
                        <div style="font-size: 8.5px;">IT Operations</div>
                    </td>
                    <td style="vertical-align: bottom;">
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>IT Manager / SPV</u></div>
                        <div style="font-size: 8.5px;">Information & Technology</div>
                    </td>
                    <td style="vertical-align: bottom;">
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>SETYADI CANDRAWINATA</u></div>
                        <div style="font-size: 8.5px;">GM Finance & Operations</div>
                    </td>
                    <td style="vertical-align: bottom;">
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>( ..................................... )</u></div>
                        <div style="font-size: 8.5px;">Management / General Affairs</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl. {{ date('d/m/Y') }}</td>
                    <td>Tgl. {{ date('d/m/Y') }}</td>
                    <td>Tgl. {{ date('d/m/Y') }}</td>
                    <td>Tgl. {{ date('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
