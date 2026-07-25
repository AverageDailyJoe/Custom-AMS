<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM PENGAJUAN ASET IT BARU - {{ $pengajuanAset->request_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #111827; margin: 0; padding: 15px; background: #fff; line-height: 1.4; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #9ca3af; padding: 25px; border-radius: 4px; }
        
        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; border-collapse: collapse; }
        .header-table td { border: none; vertical-align: middle; }
        .logo-img { height: 50px; width: auto; }
        .doc-title { font-size: 17px; font-weight: bold; text-align: center; text-transform: uppercase; margin: 0; }
        .company-sub { font-size: 11px; font-weight: bold; text-align: center; color: #374151; margin-top: 2px; }

        .meta-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; font-size: 11.5px; }
        .meta-table td { border: none; padding: 3px 0; }

        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table th, .info-table td { border: 1px solid #4b5563; padding: 6px 10px; font-size: 11px; text-align: left; }
        .info-table th { background: #f3f4f6; width: 30%; font-weight: bold; color: #1f2937; }

        .detail-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .detail-table th, .detail-table td { border: 1px solid #000; padding: 6px 10px; font-size: 11px; text-align: center; }
        .detail-table th { background: #e5e7eb; font-weight: bold; text-transform: uppercase; }

        .box-section { border: 1px solid #d1d5db; background: #fafafa; padding: 10px 12px; border-radius: 4px; margin-bottom: 15px; font-size: 11.5px; }
        .box-title { font-weight: bold; margin-bottom: 5px; color: #1f2937; text-transform: uppercase; }

        .sig-table { width: 100%; border-collapse: collapse; margin-top: 35px; border: none; }
        .sig-table td { border: none; text-align: center; vertical-align: top; width: 33.33%; padding: 0; font-size: 11.5px; }
        .sig-space { height: 65px; }
        
        .no-print { margin-bottom: 15px; text-align: right; }
        .btn-print { background: #2563eb; color: white; border: none; padding: 8px 18px; font-size: 12px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .btn-print:hover { background: #1d4ed8; }
        
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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD PENGAJUAN (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 25%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 75%; text-align: center; padding-right: 25%;">
                    <div class="doc-title">FORM PENGAJUAN ASET IT BARU</div>
                    <div class="company-sub">PT GONDOWANGI TRADISIONAL KOSMETIKA</div>
                </td>
            </tr>
        </table>

        @php
            $date = $pengajuanAset->request_date ? \Carbon\Carbon::parse($pengajuanAset->request_date) : \Carbon\Carbon::now();
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
                <td style="width: 15%;"><strong>No. Pengajuan</strong></td>
                <td style="width: 2%;">:</td>
                <td>{{ $pengajuanAset->request_number }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>:</td>
                <td>{{ $dayNum }}/{{ substr($monthName, 0, 3) }}/{{ $yearNum }}</td>
            </tr>
            <tr>
                <td><strong>Prioritas</strong></td>
                <td>:</td>
                <td><span style="text-transform: uppercase; font-weight: bold; color: #2563eb;">{{ $pengajuanAset->priority }}</span></td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>:</td>
                <td><span style="text-transform: uppercase; font-weight: bold; color: #059669;">{{ $pengajuanAset->status }}</span></td>
            </tr>
        </table>

        <div style="margin-bottom: 12px; font-size: 11.5px;">
            Pada hari ini, {{ $dayName }}, tanggal {{ $dayNum }}, bulan {{ $monthName }}, tahun {{ $yearNum }}, mengajukan permohonan pengadaan aset IT baru sebagai berikut:
        </div>

        <table class="info-table">
            <tr>
                <th>NAMA PEMOHON</th>
                <td><strong>{{ $pengajuanAset->requester_name }}</strong></td>
            </tr>
            <tr>
                <th>DEPARTEMEN / DIVISI</th>
                <td>{{ $pengajuanAset->requester_department }}</td>
            </tr>
            <tr>
                <th>JUDUL PENGAJUAN</th>
                <td><strong>{{ $pengajuanAset->title }}</strong></td>
            </tr>
        </table>

        <div style="font-weight: bold; font-size: 11.5px; margin: 15px 0 5px;">RINCIAN BARANG YANG DIAJUKAN:</div>
        <table class="detail-table">
            <thead>
                <tr>
                    <th>JENIS BARANG / PERANGKAT</th>
                    <th>JUMLAH</th>
                    <th>PRIORITAS</th>
                    <th>ESTIMASI BIAYA (PER UNIT / TOTAL)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ $pengajuanAset->item_type }}</strong></td>
                    <td>{{ $pengajuanAset->quantity }} Unit</td>
                    <td><span style="text-transform: uppercase;">{{ $pengajuanAset->priority }}</span></td>
                    <td>{{ $pengajuanAset->estimated_cost ? 'Rp ' . number_format($pengajuanAset->estimated_cost, 0, ',', '.') : '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="box-section">
            <div class="box-title">SPESIFIKASI TEKNIS YANG DIHARAPKAN:</div>
            <div>{!! nl2br(e($pengajuanAset->specification_requested ?? 'Sesuai standar operasional spesifikasi IT perusahaan.')) !!}</div>
        </div>

        <div class="box-section">
            <div class="box-title">ALASAN & KEPERLUAN PENGAJUAN:</div>
            <div>{!! nl2br(e($pengajuanAset->reason)) !!}</div>
        </div>

        <table class="sig-table">
            <tr>
                <td>
                    <p>PEMOHON,<br>&nbsp;</p>
                    <div class="sig-space"></div>
                    <p><strong><u>{{ $pengajuanAset->requester_name }}</u></strong><br><small>{{ $pengajuanAset->requester_department }}</small></p>
                </td>
                <td>
                    <p>MENGETAHUI,<br><strong>ATASAN / SPV</strong></p>
                    <div class="sig-space"></div>
                    <p><strong><u>{{ $pengajuanAset->approver_name ?? 'SETYADI CANDRAWINATA' }}</u></strong><br><small>{{ $pengajuanAset->approver_title ?? 'GM Finance & Operations' }}</small></p>
                </td>
                <td>
                    <p>DISETUJUI,<br><strong>IT MANAGER / MANAGEMENT</strong></p>
                    <div class="sig-space"></div>
                    <p><strong><u>( ..................................... )</u></strong><br><small>IT Department</small></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
