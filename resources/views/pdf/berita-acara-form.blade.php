<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BERITA ACARA IT - {{ $beritaAcara->letter_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 15px; background: #fff; line-height: 1.4; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #9ca3af; padding: 25px; border-radius: 4px; }
        
        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; border-collapse: collapse; }
        .header-table td { border: none; vertical-align: middle; }
        .logo-img { height: 50px; width: auto; }
        .doc-title { font-size: 18px; font-weight: bold; text-align: center; text-transform: uppercase; margin: 0; }
        
        .meta-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; font-size: 11.5px; }
        .meta-table td { border: none; padding: 3px 0; }

        .party-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11.5px; }
        .party-table td { border: none; padding: 3px 6px; vertical-align: top; }
        .party-num { font-weight: bold; width: 25px; text-align: center; }

        .points-box { margin-bottom: 15px; font-size: 11.5px; line-height: 1.5; text-align: justify; }
        .points-box p { margin: 4px 0; }

        .detail-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .detail-table th, .detail-table td { border: 1px solid #000; padding: 6px 10px; font-size: 11px; text-align: center; }
        .detail-table th { background: #e5e7eb; font-weight: bold; text-transform: uppercase; }

        .sig-table { width: 100%; border-collapse: collapse; margin-top: 35px; border: none; }
        .sig-table td { border: none; text-align: center; vertical-align: top; width: 33.33%; padding: 0; font-size: 11.5px; }
        .sig-space { height: 65px; }
        
        .no-print { margin-bottom: 15px; text-align: right; }
        .btn-print { background: #1f2937; color: white; border: none; padding: 8px 18px; font-size: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .btn-print:hover { background: #111827; }
        
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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD BERITA ACARA (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 25%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 75%; text-align: center; padding-right: 25%;">
                    <div class="doc-title">BERITA ACARA</div>
                </td>
            </tr>
        </table>

        @php
            $date = $beritaAcara->letter_date ? \Carbon\Carbon::parse($beritaAcara->letter_date) : \Carbon\Carbon::now();
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

        <table class="meta-table">
            <tr>
                <td style="width: 12%;"><strong>Nomor</strong></td>
                <td style="width: 2%;">:</td>
                <td>{{ $beritaAcara->letter_number }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>:</td>
                <td>{{ $dayNum }}/{{ substr($monthName, 0, 3) }}/{{ $yearNum }}</td>
            </tr>
        </table>

        <div style="margin-bottom: 12px; font-size: 11.5px;">
            Pada hari ini, {{ $dayName }}, tanggal {{ $dayNum }}, bulan {{ $monthName }}, tahun {{ $yearNum }}, yang bertanda tangan dibawah ini:
        </div>

        <table class="party-table">
            <tr>
                <td class="party-num">1</td>
                <td style="width: 15%;">Nama</td>
                <td style="width: 2%;">:</td>
                <td><strong>{{ $beritaAcara->party1_name }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $beritaAcara->party1_title }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Departemen</td>
                <td>:</td>
                <td>{{ $beritaAcara->party1_department }}</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3" style="padding-top: 4px; font-style: italic; color: #374151;">
                    Dalam hal ini bertindak untuk dan atas nama perusahaan yang menyerahkan/mengelola unit aset IT, selanjutnya disebut <strong>PIHAK PERTAMA</strong>.
                </td>
            </tr>

            <tr style="height: 10px;"><td colspan="4"></td></tr>

            <tr>
                <td class="party-num">2</td>
                <td>Nama</td>
                <td>:</td>
                <td><strong>{{ $beritaAcara->party2_name }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $beritaAcara->party2_title ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td>Departemen</td>
                <td>:</td>
                <td>{{ $beritaAcara->party2_department ?? '-' }}</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3" style="padding-top: 4px; font-style: italic; color: #374151;">
                    Dalam hal ini bertindak untuk dan atas nama pribadi/divisi yang menerima/mengajukan unit aset IT, selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.
                </td>
            </tr>
        </table>

        <div style="font-weight: bold; font-size: 11.5px; margin: 15px 0 8px;">Menerangkan hal-hal sebagai berikut :</div>
        
        <div class="points-box">
            {!! nl2br(e($beritaAcara->description_points)) !!}
        </div>

        <div style="font-weight: bold; font-size: 11.5px; margin: 15px 0 5px;">Detail Unit Laptop / Aset</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>ID INVENTARIS</th>
                    <th>NAMA BARANG</th>
                    <th>JUMLAH</th>
                    <th>KELENGKAPAN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $beritaAcara->asset_tag ?? ($beritaAcara->asset?->asset_tag ?? '-') }}</strong></td>
                    <td>{{ $beritaAcara->asset_name ?? ($beritaAcara->asset?->assetModel?->manufacturer . ' ' . $beritaAcara->asset?->assetModel?->name) }}</td>
                    <td>{{ $beritaAcara->quantity ?? '1 Unit' }}</td>
                    <td>{{ $beritaAcara->completeness ?? '1 Unit Laptop + Charger' }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 15px; font-size: 11.5px;">
            Demikian Berita Acara ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagaimana mestinya.
        </div>

        <table class="sig-table">
            <tr>
                <td>
                    <p>YANG MENYERAHKAN,<br><strong>PIHAK PERTAMA</strong></p>
                    <div class="sig-space"></div>
                    <p><strong><u>{{ $beritaAcara->party1_name }}</u></strong></p>
                </td>
                <td>
                    <p>MENYETUJUI,<br>&nbsp;</p>
                    <div class="sig-space"></div>
                    <p><strong><u>{{ $beritaAcara->approver_name ?? 'SETYADI CANDRAWINATA' }}</u></strong></p>
                </td>
                <td>
                    <p>YANG MENERIMA,<br><strong>PIHAK KEDUA</strong></p>
                    <div class="sig-space"></div>
                    <p><strong><u>{{ $beritaAcara->party2_name }}</u></strong></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
