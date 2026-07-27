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

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px; line-height: 1.2; }
        .meta-table td { padding: 2px 4px; vertical-align: bottom; border: none; }
        .meta-label-left { width: 9%; }
        .meta-colon { width: 2%; text-align: center; }
        .meta-value-left { width: 39%; }
        .meta-label-right { width: 10%; padding-left: 40px !important; }
        .meta-value-right { width: 38%; }
        .border-bottom { border-bottom: 1px solid #000 !important; }

        .ppb-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .ppb-table th, .ppb-table td { border: 1px solid #000; padding: 5px 8px; font-size: 11px; vertical-align: top; }
        .ppb-table th { background: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }
        
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
        @endphp

        <table class="meta-table">
            <tr>
                <td class="meta-label-left"><strong>Nomor</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom"><strong>{{ $pengajuanAset->request_number }}</strong></td>
                <td class="meta-label-right"><strong>Jabatan</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-right border-bottom">{{ $pengajuanAset->requester_department }}</td>
            </tr>
            <tr>
                <td class="meta-label-left"><strong>Tanggal</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom">{{ $dayNum }} {{ $monthName }} {{ $yearNum }}</td>
                <td class="meta-label-right"><strong>Area</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-right border-bottom">HQ / Head Office</td>
            </tr>
            <tr>
                <td class="meta-label-left"><strong>Nama</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom"><strong>{{ $pengajuanAset->requester_name }}</strong></td>
                <td class="meta-label-right"></td>
                <td class="meta-colon"></td>
                <td class="meta-value-right"></td>
            </tr>
        </table>

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
                            <strong>Jenis Item:</strong> {{ $pengajuanAset->item_type }} ({{ $pengajuanAset->quantity }} Unit)<br>
                            @if($pengajuanAset->specification_requested)
                                <strong>Spesifikasi:</strong> {{ $pengajuanAset->specification_requested }}
                            @endif
                        </div>
                    </td>
                    <td class="text-right font-bold">
                        {{ $pengajuanAset->estimated_cost ? 'Rp ' . number_format($pengajuanAset->estimated_cost, 0, ',', '.') : '-' }}
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
                        {{ $pengajuanAset->estimated_cost ? 'Rp ' . number_format($pengajuanAset->estimated_cost, 0, ',', '.') : 'Rp 0' }}
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 25%;">DIBUAT</th>
                    <th style="width: 25%;">DIPERIKSA</th>
                    <th style="width: 25%;">DISETUJUI</th>
                    <th style="width: 25%;">MENGETAHUI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $pengajuanAset->requester_name }}</u></div>
                        <div style="font-size: 9.5px; margin-top: 2px;">Pemohon ({{ $pengajuanAset->requester_department }})</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>IT Manager / SPV</u></div>
                        <div style="font-size: 9.5px; margin-top: 2px;">Information & Technology</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $pengajuanAset->approver_name ?? 'SETYADI CANDRAWINATA' }}</u></div>
                        <div style="font-size: 9.5px; margin-top: 2px;">{{ $pengajuanAset->approver_title ?? 'GM Finance & Operations' }}</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>( ..................................... )</u></div>
                        <div style="font-size: 9.5px; margin-top: 2px;">Management / Director</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl. .........................</td>
                    <td>Tgl. .........................</td>
                    <td>Tgl. .........................</td>
                    <td>Tgl. .........................</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
