<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM SERAH TERIMA LAPTOP / ASET IT - {{ $checkout->asset->asset_tag }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 15px; background: #fff; line-height: 1.4; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #9ca3af; padding: 25px; border-radius: 4px; }
        
        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; border-collapse: collapse; }
        .header-table td { border: none; vertical-align: middle; }
        .logo-img { height: 55px; width: auto; }
        .company-title { font-size: 16px; font-weight: bold; text-transform: uppercase; color: #000; margin: 0; }
        .company-sub { font-size: 12px; font-weight: bold; color: #374151; margin: 2px 0 0; }
        .doc-title { font-size: 15px; font-weight: bold; text-align: center; text-transform: uppercase; text-decoration: underline; margin: 15px 0 10px; }
        
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table th, .info-table td { border: 1px solid #4b5563; padding: 6px 10px; font-size: 11px; text-align: left; }
        .info-table th { background: #f3f4f6; width: 30%; font-weight: bold; color: #1f2937; }
        
        .check-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .check-table th, .check-table td { border: 1px solid #4b5563; padding: 5px 8px; font-size: 11px; text-align: center; }
        .check-table th { background: #e5e7eb; font-weight: bold; }
        .text-left { text-align: left !important; }
        
        .terms-box { font-size: 10.5px; text-align: justify; margin-bottom: 20px; border: 1px solid #d1d5db; padding: 10px 15px; background: #fafafa; border-radius: 4px; }
        .terms-box ol { margin: 4px 0 0; padding-left: 18px; }
        .terms-box li { margin-bottom: 6px; }

        .sig-table { width: 100%; border-collapse: collapse; margin-top: 25px; border: none; }
        .sig-table td { border: none; text-align: center; vertical-align: top; width: 33.33%; padding: 0; }
        .sig-space { height: 65px; }
        
        .no-print { margin-bottom: 15px; text-align: right; }
        .btn-print { background: #166534; color: white; border: none; padding: 8px 18px; font-size: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .btn-print:hover { background: #14532d; }
        
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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD FORM SERAH TERIMA (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 80%; text-align: left; padding-left: 15px;">
                    <div class="company-title">PT GONDOWANGI TRADISIONAL KOSMETIKA</div>
                    <div class="company-sub">DEPARTEMEN INFORMATION & TECHNOLOGY (IT)</div>
                </td>
            </tr>
        </table>

        <div class="doc-title">FORM SERAH TERIMA PERANGKAT IT</div>

        <div style="margin-bottom: 8px; font-size: 11px;">
            Pada hari ini tanggal <strong>{{ $checkout->checked_out_at ? $checkout->checked_out_at->translatedFormat('d F Y') : date('d F Y') }}</strong>, yang bertandatangan di bawah ini:
        </div>

        <table class="info-table">
            <tr>
                <th>PIHAK PERTAMA (PENGAWAS IT)</th>
                <td><strong>{{ $checkout->checkedOutByUser?->name ?? 'IT Staff' }}</strong> (Departemen Information & Technology)</td>
            </tr>
            <tr>
                <th>PIHAK KEDUA (PENGGUNA UTAMA)</th>
                <td><strong>{{ $checkout->primary_user ?? '-' }}</strong> {{ $checkout->secondary_user ? ' / ' . $checkout->secondary_user : '' }}</td>
            </tr>
            <tr>
                <th>DEPARTEMEN / LOKASI</th>
                <td>{{ $checkout->asset->department ?? '-' }} - {{ $checkout->asset->location?->name ?? '-' }} ({{ $checkout->asset->room ?? '-' }})</td>
            </tr>
            <tr>
                <th>ID INVENTARIS (TAG)</th>
                <td><strong>{{ $checkout->asset->asset_tag }}</strong></td>
            </tr>
            <tr>
                <th>TIPE / MODEL UNIT</th>
                <td>{{ $checkout->asset->assetModel?->manufacturer }} {{ $checkout->asset->assetModel?->name }} ({{ $checkout->asset->assetModel?->category?->name }})</td>
            </tr>
            <tr>
                <th>SERIAL NUMBER (SN)</th>
                <td>{{ $checkout->asset->serial ?? '-' }}</td>
            </tr>
        </table>

        <div style="font-weight: bold; font-size: 11px; margin: 10px 0 5px;">PENGECEKAN KONDISI KOMPONEN & SPESIFIKASI:</div>
        <table class="check-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;" class="text-left">Komponen / Aksesoris</th>
                    <th style="width: 15%;">Baik</th>
                    <th style="width: 15%;">Rusak</th>
                    <th style="width: 30%;" class="text-left">Keterangan / Spesifikasi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td class="text-left">Layar / Display</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">Normal</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td class="text-left">Keyboard</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">Normal</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td class="text-left">RAM / Memory</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">{{ $checkout->asset->ram ?? 'Standard' }}</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td class="text-left">SSD / Storage</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">SSD: {{ $checkout->asset->storage_ssd ?? '-' }} | HDD: {{ $checkout->asset->storage_hdd ?? '-' }}</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td class="text-left">Trackpad / Mouse</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">Normal</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td class="text-left">Baterai</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">Berfungsi baik</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td class="text-left">Hardware & CPU</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">{{ $checkout->asset->processor ?? 'Processor Unit' }}</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td class="text-left">Charger / Adaptor Power</td>
                    <td>✔</td>
                    <td>-</td>
                    <td class="text-left">Lengkap dengan kabel power</td>
                </tr>
            </tbody>
        </table>

        <div class="terms-box">
            <strong>KETENTUAN DAN SYARAT PENYERAHAN FASILITAS IT:</strong>
            <ol>
                <li>PIHAK PERTAMA telah menyerahkan 1 (satu) unit perangkat beserta aksesorisnya kepada PIHAK KEDUA dan PIHAK KEDUA telah menerima perangkat tersebut dalam keadaan baik dengan spesifikasi di atas.</li>
                <li>Perangkat diserahkan kepada PIHAK KEDUA untuk dipergunakan sebagai fasilitas kerja guna mendukung operasional PIHAK KEDUA di lingkungan <strong>PT Gondowangi Tradisional Kosmetika</strong>.</li>
                <li>Dalam hal terjadi kerusakan yang diakibatkan oleh kelalaian PIHAK KEDUA, maka kerusakan sepenuhnya menjadi tanggung jawab PIHAK KEDUA dan wajib melaporkan kepada PIHAK PERTAMA. Nilai kerugian akan disampaikan setelah diperhitungkan oleh PIHAK PERTAMA.</li>
                <li>Dalam hal terjadi kehilangan unit, maka kehilangan tersebut sepenuhnya menjadi tanggung jawab PIHAK KEDUA.</li>
            </ol>
        </div>

        <table class="sig-table">
            <tr>
                <td>
                    <p>Diserahkan Oleh,<br><strong>PIHAK PERTAMA (IT)</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->checkedOutByUser?->name ?? 'IT Staff' }} )</u><br><small>Information Technology</small></p>
                </td>
                <td>
                    <p>Diterima Oleh,<br><strong>PIHAK KEDUA (PENGGUNA)</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->primary_user ?? 'Pengguna Unit' }} )</u><br><small>{{ $checkout->asset->department ?? 'User' }}</small></p>
                </td>
                <td>
                    <p>Mengetahui,<br><strong>ATASAN / SPV</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( ..................................... )</u><br><small>Manager / SPV</small></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
