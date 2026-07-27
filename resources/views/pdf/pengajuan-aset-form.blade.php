<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PERMOHONAN PENGELUARAN BIAYA (PPB) - {{ $pengajuanAset->request_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11.5px; color: #000; margin: 0; padding: 15px; background: #fff; line-height: 1.3; }
        .container { max-width: 800px; margin: 0 auto; border: 1.5px solid #000; padding: 20px; }
        
        .header-title { text-align: center; font-weight: bold; font-size: 15px; font-family: Arial, Helvetica, sans-serif; margin: 0 0 4px; text-transform: uppercase; line-height: 1.3; }
        .header-sub { text-align: center; font-weight: bold; font-size: 15px; font-family: Arial, Helvetica, sans-serif; margin: 0 0 15px; text-transform: uppercase; line-height: 1.3; letter-spacing: 0.5px; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 11.5px; line-height: 1.25; }
        .meta-table td { padding: 2px 2px; vertical-align: bottom; border: none; }
        .meta-label-left { width: 55px; font-weight: bold; }
        .meta-colon { width: 10px; font-weight: bold; text-align: center; }
        .meta-value-left { width: 330px; padding-left: 4px; border-bottom: 1px solid #000; }
        .meta-gap { width: 50px; }
        .meta-label-right { width: 55px; font-weight: bold; }
        .meta-value-right { width: 220px; padding-left: 4px; border-bottom: 1px solid #000; }
        .border-bottom { border-bottom: 1px solid #000 !important; }

        .ppb-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .ppb-table th, .ppb-table td { border: 1px solid #000; padding: 5px 8px; font-size: 11px; vertical-align: top; }
        .ppb-table th { background: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }
        
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .sig-table th, .sig-table td { border: 1px solid #000; padding: 4px 2px; font-size: 10px; text-align: center; }
        .sig-table th { background: #f3f4f6; font-weight: bold; text-transform: uppercase; }
        .sig-space { height: 50px; }

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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD PPB (PDF)</button>
        </div>

        <div class="header-title">PT. GONDOWANGI TRADISIONAL KOSMETIKA</div>
        <div class="header-sub">PERMOHONAN PENGELUARAN BIAYA ( P P B )</div>

        @php
            $date = $pengajuanAset->request_date ? \Carbon\Carbon::parse($pengajuanAset->request_date) : \Carbon\Carbon::now();
            $months = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
                'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
                'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember',
            ];
            $monthName = $months[$date->format('F')] ?? $date->format('F');
            $dayNum = $date->format('d');
            $yearNum = $date->format('Y');
            $formattedDate = $date->format('d/m/Y');
        @endphp

        <table class="meta-table">
            <tr>
                <td class="meta-label-left">Nomor</td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom"><strong></strong></td>
                <td class="meta-gap"></td>
                <td class="meta-label-right">Jabatan</td>
                <td class="meta-colon">:</td>
                <td class="meta-value-right border-bottom">{{ $pengajuanAset->requester_department }}</td>
            </tr>
            <tr>
                <td class="meta-label-left">Tanggal</td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom">{{ $dayNum }} {{ $monthName }} {{ $yearNum }}</td>
                <td class="meta-gap"></td>
                <td class="meta-label-right">Area</td>
                <td class="meta-colon">:</td>
                <td class="meta-value-right border-bottom">{{ $pengajuanAset->area ?? '' }}</td>
            </tr>
            <tr>
                <td class="meta-label-left">Nama</td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom"><strong>{{ $pengajuanAset->requester_name }}</strong></td>
                <td class="meta-gap"></td>
                <td class="meta-label-right"></td>
                <td class="meta-colon"></td>
                <td class="meta-value-right" style="border-bottom: none;"></td>
            </tr>
        </table>

        @php
            $unitCost = (float) ($pengajuanAset->estimated_cost ?? 0);
            $qty = (int) ($pengajuanAset->quantity ?? 1);
            $totalCost = $unitCost * $qty;
        @endphp

        <table class="ppb-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 50%;">Uraian</th>
                    <th style="width: 20%;">Jumlah</th>
                    <th style="width: 25%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <!-- Main Item Row -->
                <tr>
                    <td class="text-center font-bold">1</td>
                    <td>
                        <div class="font-bold">{{ $pengajuanAset->title }}</div>
                        <div style="font-size: 10px; color: #374151; margin-top: 3px;">
                            <strong>Jenis Item:</strong> {{ $pengajuanAset->item_type }} ({{ $pengajuanAset->quantity }} Unit{{ $unitCost > 0 && $qty > 1 ? ' @ Rp ' . number_format($unitCost, 0, ',', '.') : '' }})<br>
                            @if($pengajuanAset->specification_requested)
                                <strong>Spesifikasi:</strong> {{ $pengajuanAset->specification_requested }}
                            @endif
                        </div>
                    </td>
                    <td class="text-right font-bold">
                        {{ $totalCost > 0 ? 'Rp ' . number_format($totalCost, 0, ',', '.') : '-' }}
                    </td>
                    <td>
                        <div style="font-size: 10.5px;">{{ $pengajuanAset->reason }}</div>
                    </td>
                </tr>

                <!-- Empty Grid Rows matching official Excel PPB layout -->
                @for ($i = 2; $i <= 10; $i++)
                <tr>
                    <td class="text-center" style="height: 22px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor

                <!-- Total Row -->
                <tr>
                    <td colspan="2" class="text-center font-bold" style="font-size: 12px; letter-spacing: 2px;">
                        J U M L A H
                    </td>
                    <td class="text-right font-bold" style="font-size: 12px;">
                        {{ $totalCost > 0 ? 'Rp ' . number_format($totalCost, 0, ',', '.') : 'Rp 0' }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 14.28%;">DIBUAT</th>
                    <th style="width: 14.28%;">DIPERIKSA</th>
                    <th colspan="4" style="width: 57.12%;">DISETUJUI</th>
                    <th style="width: 14.28%;">DIPERIKSA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="vertical-align: bottom; padding-bottom: 4px;">
                        <div class="sig-space"></div>
                        <div class="font-bold" style="font-size: 9.5px;"><u>{{ $pengajuanAset->requester_name }}</u></div>
                    </td>
                    <td><div class="sig-space"></div></td>
                    <td><div class="sig-space"></div></td>
                    <td><div class="sig-space"></div></td>
                    <td><div class="sig-space"></div></td>
                    <td><div class="sig-space"></div></td>
                    <td><div class="sig-space"></div></td>
                </tr>
                <tr>
                    <td>Tgl. {{ $formattedDate }}</td>
                    <td>Tgl. {{ $formattedDate }}</td>
                    <td>Tgl. {{ $formattedDate }}</td>
                    <td>Tgl. {{ $formattedDate }}</td>
                    <td>Tgl. {{ $formattedDate }}</td>
                    <td>Tgl. {{ $formattedDate }}</td>
                    <td>Tgl. {{ $formattedDate }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
