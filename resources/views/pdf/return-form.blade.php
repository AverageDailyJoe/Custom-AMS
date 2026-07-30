<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM PENGEMBALIAN LAPTOP / ASET IT - {{ $checkout->asset->asset_tag }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 0;
            padding: 15px;
            background: #fff;
            line-height: 1.4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #9ca3af;
            padding: 25px;
            border-radius: 4px;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo-img {
            height: 55px;
            width: auto;
        }

        .company-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
            margin: 0;
        }

        .company-sub {
            font-size: 12px;
            font-weight: bold;
            color: #374151;
            margin: 2px 0 0;
        }

        .doc-title {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 15px 0 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #4b5563;
            padding: 6px 10px;
            font-size: 11px;
            text-align: left;
        }

        .info-table th {
            background: #f3f4f6;
            width: 30%;
            font-weight: bold;
            color: #1f2937;
        }

        .check-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .check-table th,
        .check-table td {
            border: 1px solid #4b5563;
            padding: 5px 8px;
            font-size: 11px;
            text-align: center;
        }

        .check-table th {
            background: #f3f4f6;
            font-weight: bold;
            color: #111827;
        }

        .text-left {
            text-align: left !important;
        }

        .terms-box {
            font-size: 10.5px;
            text-align: justify;
            margin-bottom: 20px;
            border: 1px solid #d1d5db;
            padding: 10px 15px;
            background: #fafafa;
            border-radius: 4px;
        }

        .terms-box p {
            margin: 0 0 6px;
        }

        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            border: none;
        }

        .sig-table td {
            border: none;
            text-align: center;
            vertical-align: top;
            width: 33.33%;
            padding: 0;
        }

        .sig-space {
            height: 65px;
        }

        .no-print {
            margin-bottom: 15px;
            text-align: right;
        }

        .btn-print {
            background: #111827;
            color: white;
            border: none;
            padding: 8px 18px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #1f2937;
        }

        @media print {
            .no-print {
                display: none;
            }

            .container {
                border: none;
                padding: 0;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="no-print">
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD FORM PENGEMBALIAN (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 80%; text-align: left; padding-left: 15px;">
                    <div class="company-title">PT GONDOWANGI TRADISIONAL KOSMETIKA</div>
                    <div class="company-sub">DEPARTEMEN INFORMATION & TECHNOLOGY (IT)</div>
                </td>
            </tr>
        </table>

        <div class="doc-title">FORM PENGEMBALIAN PERANGKAT IT</div>

        @php
            $date = $checkout->checked_in_at ? \Carbon\Carbon::parse($checkout->checked_in_at) : \Carbon\Carbon::now();
            $days = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => "Jum'at",
                'Saturday' => 'Sabtu',
            ];
            $months = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember',
            ];
            $dayName = $days[$date->format('l')] ?? $date->format('l');
            $monthName = $months[$date->format('F')] ?? $date->format('F');
            $dayNum = $date->format('d');
            $yearNum = $date->format('Y');
        @endphp

        <div style="margin-bottom: 8px; font-size: 11px;">
            Pada hari ini, {{ $dayName }}, tanggal {{ $dayNum }}, bulan {{ $monthName }}, tahun {{ $yearNum }}, yang
            bertanda tangan di bawah ini:
        </div>

        <table class="info-table">
            <tr>
                <th>PENGGUNA (PIHAK PERTAMA)</th>
                <td><strong>{{ $checkout->primary_user ?? '-' }}</strong>
                    {{ $checkout->secondary_user ? ' / ' . $checkout->secondary_user : '' }}</td>
            </tr>
            <tr>
                <th>DEPARTEMEN / LOKASI</th>
                <td>{{ $checkout->asset->department ?? '-' }} - {{ $checkout->asset->location?->name ?? '-' }}
                    ({{ $checkout->asset->room ?? '-' }})</td>
            </tr>
            <tr>
                <th>PENERIMA (PIHAK KEDUA)</th>
                <td><strong>{{ $checkout->checkedInByUser?->name ?? ($checkout->checkedOutByUser?->name ?? 'IT Staff') }}</strong>
                    </td>
            </tr>
            <tr>
                <th>ID INVENTARIS (TAG)</th>
                <td><strong>{{ $checkout->asset->asset_tag }}</strong></td>
            </tr>
            <tr>
                <th>TIPE / MODEL UNIT</th>
                <td>{{ $checkout->asset->assetModel?->manufacturer }} {{ $checkout->asset->assetModel?->name }}
                    ({{ $checkout->asset->assetModel?->category?->name }})</td>
            </tr>
            <tr>
                <th>SERIAL NUMBER (SN)</th>
                <td>{{ $checkout->asset->serial ?? '-' }}</td>
            </tr>
        </table>

        <div style="font-weight: bold; font-size: 11px; margin: 10px 0 5px;">TABEL PENGECEKAN KONDISI FISIK & KOMPONEN
            SAAT PENGEMBALIAN:</div>
        @php
            $chk = $checkout->component_checklist ?? [];
        @endphp
        <table class="check-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;" class="text-left">Komponen</th>
                    <th style="width: 15%;">Baik</th>
                    <th style="width: 15%;">Rusak</th>
                    <th style="width: 30%;" class="text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td class="text-left">Layar / Display</td>
                    <td>{{ ($chk['layar_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['layar_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">{{ $chk['layar_notes'] ?? 'Normal' }}</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="text-left">Keyboard</td>
                    <td>{{ ($chk['keyboard_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['keyboard_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">{{ $chk['keyboard_notes'] ?? 'Normal' }}</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="text-left">RAM / Memory</td>
                    <td>{{ ($chk['ram_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['ram_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">{{ $chk['ram_notes'] ?? ($checkout->asset->ram ?? 'Normal') }}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td class="text-left">SSD / Storage</td>
                    <td>{{ ($chk['ssd_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['ssd_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">
                        {{ $chk['ssd_notes'] ?? ('SSD: ' . ($checkout->asset->storage_ssd ?? '-') . ' | HDD: ' . ($checkout->asset->storage_hdd ?? '-')) }}
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td class="text-left">Trackpad / Mouse</td>
                    <td>{{ ($chk['trackpad_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['trackpad_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">{{ $chk['trackpad_notes'] ?? 'Normal' }}</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td class="text-left">Baterai</td>
                    <td>{{ ($chk['baterai_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['baterai_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">{{ $chk['baterai_notes'] ?? 'Berfungsi baik' }}</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td class="text-left">Hardware & CPU</td>
                    <td>{{ ($chk['hardware_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['hardware_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">{{ $chk['hardware_notes'] ?? ($checkout->asset->processor ?? 'Normal') }}</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td class="text-left">Charger / Power Brick</td>
                    <td>{{ ($chk['charger_status'] ?? 'baik') === 'baik' ? '✔' : '-' }}</td>
                    <td>{{ ($chk['charger_status'] ?? 'baik') === 'rusak' ? '✔' : '-' }}</td>
                    <td class="text-left">{{ $chk['charger_notes'] ?? 'Lengkap dengan kabel power' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="terms-box">
            <p><strong>PERNYATAAN PENGEMBALIAN ASET:</strong></p>
            <p>1. PIHAK PERTAMA telah menyerahkan kembali 1 (satu) unit laptop/perangkat beserta aksesoris pendukungnya
                kepada PIHAK KEDUA dalam kondisi sebagaimana tercantum pada tabel pemeriksaan di atas.</p>
            <p>2. PIHAK KEDUA telah menerima dan melakukan pengecekan fisik serta pengujian fungsi teknis terhadap unit
                tersebut.</p>
            <p>3. Dalam hal terjadi kerusakan atau kekurangan komponen akibat kelalaian PIHAK PERTAMA selama masa
                penggunaan yang belum dilaporkan/diperbaiki, maka nilai kerugian akan diperhitungkan dan diselesaikan
                sesuai ketentuan <strong>PT Gondowangi Tradisional Kosmetika</strong>.</p>
            <p>4. PIHAK PERTAMA menyatakan bahwa seluruh data pribadi telah dibersihkan/di-backup dan data/aset
                perusahaan pada laptop telah diserahterimakan penuh.</p>
            <p>5. Dengan ditandatanganinya form ini, maka tanggung jawab atas penggunaan unit laptop resmi beralih
                kembali dari PIHAK PERTAMA kepada PIHAK KEDUA.</p>
        </div>

        <table class="sig-table">
            <tr>
                <td>
                    <p>Dibuat Oleh,<br><strong>IT STAFF (PIHAK KEDUA)</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->checkedInByUser?->name ?? ($checkout->checkedOutByUser?->name ?? 'IT Staff') }}
                            )</u><br><small>STAFF IT</small></p>
                </td>
                <td>
                    <p>Diterima Oleh,<br><strong>PENGGUNA (PIHAK PERTAMA)</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->primary_user ?? 'Pengguna Unit' }}
                            )</u><br><small>{{ $checkout->asset->department ?? 'User' }}</small></p>
                </td>
                <td>
                    <p>Mengetahui,<br><strong>ATASAN / SPV</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( SETYADI CANDRAWINATA )</u><br><small></small></p>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>