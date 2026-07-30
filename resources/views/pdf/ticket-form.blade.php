<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT SERVICE WORK ORDER - {{ $ticket->ticket_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11.5px; color: #000; margin: 0; padding: 15px; background: #fff; line-height: 1.3; }
        .container { max-width: 800px; margin: 0 auto; border: 1.5px solid #000; padding: 20px; }
        
        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; border-collapse: collapse; }
        .header-table td { border: none; vertical-align: middle; }
        .logo-img { height: 50px; width: auto; }
        .doc-title { font-size: 16px; font-weight: bold; text-align: center; text-transform: uppercase; margin: 0; }
        .company-sub { font-size: 11px; font-weight: bold; text-align: center; color: #374151; margin-top: 2px; }

        .meta-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; font-size: 11px; }
        .meta-table td { padding: 3.5px 4px; vertical-align: middle; border: none; }
        .border-bottom { border-bottom: 1px solid #000 !important; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table th, .info-table td { border: 1px solid #000; padding: 6px 10px; font-size: 11px; text-align: left; vertical-align: top; }
        .info-table th { background: #f3f4f6; width: 28%; font-weight: bold; }

        .box-section { border: 1px solid #000; background: #fafafa; padding: 10px 12px; margin-bottom: 15px; font-size: 11px; }
        .box-title { font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }

        .sig-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .sig-table th, .sig-table td { border: 1px solid #000; padding: 6px 4px; font-size: 10.5px; text-align: center; }
        .sig-table th { background: #f3f4f6; font-weight: bold; }
        .sig-space { height: 55px; }

        .no-print { margin-bottom: 15px; text-align: right; }
        .btn-print { background: #111827; color: white; border: none; padding: 8px 18px; font-size: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; }
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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD TIKET (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 25%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 75%; text-align: center; padding-right: 25%;">
                    <div class="doc-title">IT SERVICE WORK ORDER / TIKET LAYANAN</div>
                    <div class="company-sub">PT GONDOWANGI TRADISIONAL KOSMETIKA</div>
                </td>
            </tr>
        </table>

        @php
            $schedDate = $ticket->scheduled_date ? \Carbon\Carbon::parse($ticket->scheduled_date) : \Carbon\Carbon::now();
            $months = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
                'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
                'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember',
            ];
            $monthName = $months[$schedDate->format('F')] ?? $schedDate->format('F');
            $dayNum = $schedDate->format('d');
            $yearNum = $schedDate->format('Y');
        @endphp

        <table class="meta-table">
            <tr>
                <td style="width: 12%;"><strong>No. Tiket</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;" class="border-bottom"><strong>{{ $ticket->ticket_number }}</strong></td>
                <td style="width: 14%;"><strong>Tgl. Penjadwalan</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 32%;" class="border-bottom"><strong>{{ $dayNum }} {{ $monthName }} {{ $yearNum }}</strong> ({{ $ticket->scheduled_time_slot }})</td>
            </tr>
            <tr>
                <td><strong>Prioritas</strong></td>
                <td>:</td>
                <td class="border-bottom"><span style="text-transform: uppercase; font-weight: bold;">{{ $ticket->priority }}</span></td>
                <td><strong>Status Tiket</strong></td>
                <td>:</td>
                <td class="border-bottom"><span style="text-transform: uppercase; font-weight: bold;">{{ $ticket->status }}</span></td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <th>NAMA PELAPOR / KARYAWAN</th>
                <td><strong>{{ $ticket->reporter_name }}</strong> (Dept: {{ $ticket->reporter_department }})</td>
            </tr>
            <tr>
                <th>LOKASI & RUANGAN</th>
                <td>
                    {{ $ticket->location?->name ?? 'HEAD OFFICE' }} - {{ $ticket->room ?? 'Ruangan Kerja' }}
                    @if($ticket->room_notes)
                        <div style="font-size: 10px; color: #047857; margin-top: 2px;">
                            <strong>Catatan Lokasi Pengerjaan:</strong> {{ $ticket->room_notes }}
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <th>UNIT ASSET IT (AMS)</th>
                <td>
                    @if($ticket->asset_tag)
                        <strong>{{ $ticket->asset_tag }}</strong> - {{ $ticket->asset_name }}
                    @else
                        <em>Tidak Terikat Ke Asset Tertentu</em>
                    @endif
                </td>
            </tr>
            <tr>
                <th>PETUGAS IT / TEKNISI</th>
                <td><strong>{{ $ticket->assigned_to_name ?? $ticket->assignedToUser?->name ?? 'Bambang Yulianto' }}</strong></td>
            </tr>
        </table>

        <div class="box-section">
            <div class="box-title">JUDUL & DESKRIPSI KENDALA:</div>
            <div style="font-weight: bold; margin-bottom: 4px;">{{ $ticket->subject }}</div>
            <div>{!! nl2br(e($ticket->description)) !!}</div>
        </div>

        <div class="box-section">
            <div class="box-title">CATATAN PENGERJAAN & SOLUSI IT:</div>
            <div>{!! nl2br(e($ticket->resolution_notes ?? 'Dalam proses pengerjaan oleh tim IT.')) !!}</div>
        </div>

        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 33.33%;">PELAPOR / KARYAWAN</th>
                    <th style="width: 33.33%;">TEKNISI / PETUGAS IT</th>
                    <th style="width: 33.33%;">MENGETAHUI (IT SPV / SPV DEPT)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="sig-space"></div>
                        <div style="font-weight: bold;"><u>{{ $ticket->reporter_name }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">{{ $ticket->reporter_department }}</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div style="font-weight: bold;"><u>{{ $ticket->assigned_to_name ?? $ticket->assignedToUser?->name ?? 'Bambang Yulianto' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">IT Department</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div style="font-weight: bold;"><u>( ..................................... )</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">Supervisor</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
