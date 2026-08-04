<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN BIAYA SETTLEMENT (LBS) - {{ $pengajuanAset->request_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000; margin: 0; padding: 15px; background: #fff; line-height: 1.3; }
        .container { max-width: 800px; margin: 0 auto; border: 1.5px solid #000; padding: 20px; }
        
        .header-box { border: 2px double #000; padding: 8px; text-align: center; margin-bottom: 15px; }
        .header-title { font-weight: bold; font-size: 15px; font-family: Arial, Helvetica, sans-serif; margin: 0 0 4px; text-transform: uppercase; line-height: 1.3; }
        .header-sub { font-weight: bold; font-size: 15px; font-family: Arial, Helvetica, sans-serif; margin: 0; text-transform: uppercase; line-height: 1.3; letter-spacing: 0.5px; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px; line-height: 1.2; }
        .meta-table td { padding: 2px 4px; vertical-align: bottom; border: none; }
        .meta-label-left { width: 9%; }
        .meta-colon { width: 2%; text-align: center; }
        .meta-value-left { width: 39%; }
        .meta-label-right { width: 10%; padding-left: 40px !important; }
        .meta-value-right { width: 38%; }
        .border-bottom { border-bottom: 1px solid #000 !important; }

        .lbs-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .lbs-table th, .lbs-table td { border: 1px solid #000; padding: 4px 8px; font-size: 10.5px; vertical-align: middle; }
        .lbs-table th { background: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }
        
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .sig-table th, .sig-table td { border: 1px solid #000; padding: 5px 4px; font-size: 10px; text-align: center; }
        .sig-table th { background: #f3f4f6; font-weight: bold; }
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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD LBS (PDF)</button>
        </div>

        <div class="header-box">
            <div class="header-title">PT. GONDOWANGI TRADISIONAL KOSMETIKA</div>
            <div class="header-sub">LAPORAN BIAYA SETTLEMENT ( L B S )</div>
        </div>

        @php
            $date = $pengajuanAset->request_date ? \Carbon\Carbon::parse($pengajuanAset->request_date) : \Carbon\Carbon::now();
            $period = $date->format('m/y');
            $formattedDate = $date->format('d/m/Y');
            $qty = (int) ($pengajuanAset->quantity ?? 1);
            if ($qty < 1) {
                $qty = 1;
            }
            $unitCost = (float) ($pengajuanAset->estimated_cost ?? 0);
            $totalCost = $unitCost * $qty;
        @endphp

        <table class="meta-table">
            <tr>
                <td class="meta-label-left"><strong>Nama</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom">{{ $pengajuanAset->requester_name }}</td>
                <td class="meta-label-right"><strong>Nomor</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-right border-bottom"></td>
            </tr>
            <tr>
                <td class="meta-label-left"><strong>Area</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-left border-bottom">{{ $pengajuanAset->area ?? ($pengajuanAset->requester_department . ' - Head Office') }}</td>
                <td class="meta-label-right"><strong>Period</strong></td>
                <td class="meta-colon">:</td>
                <td class="meta-value-right border-bottom">{{ $period }}</td>
            </tr>
        </table>

        <table class="lbs-table">
            <thead>
                <tr>
                    <th style="width: 6%;">NO.</th>
                    <th style="width: 68%;">URAIAN</th>
                    <th style="width: 26%;">JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-bold">1</td>
                    <td>INVENTARIS</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">2</td>
                    <td>ALAT TULIS KANTOR</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">3</td>
                    <td>LISTRIK & AIR</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">4</td>
                    <td>TELEPHONE, TELEGRAM, TELEX & FAX</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">5</td>
                    <td>PERBAIKAN & PEMELIHARAAN</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">6</td>
                    <td>PERJALANAN DINAS & PERWAKILAN</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">7</td>
                    <td>SEWA</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">8</td>
                    <td>ONGKOS ANGKUT</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">9</td>
                    <td>SALES PROMOTION</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">10</td>
                    <td>GAJI / INCENTIVE SPG & MD</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">11</td>
                    <td class="font-bold">LAIN - LAIN :</td>
                    <td></td>
                </tr>

                <!-- Item Detail Row under LAIN-LAIN -->
                <tr>
                    <td class="text-center"></td>
                    <td style="padding-left: 20px;">
                        1. <strong>{{ $pengajuanAset->title }}</strong> ({{ $qty }} Unit{{ $unitCost > 0 && $qty > 1 ? ' @ Rp ' . number_format($unitCost, 0, ',', '.') : '' }})
                        @if($pengajuanAset->specification_requested)
                            <br><small style="color: #374151;">Spec: {{ $pengajuanAset->specification_requested }}</small>
                        @endif
                    </td>
                    <td class="text-right font-bold">
                        {{ $totalCost > 0 ? number_format($totalCost, 0, ',', '.') : '-' }}
                    </td>
                </tr>

                <!-- Extra empty rows for exact Excel spacing -->
                @for ($i = 1; $i <= 4; $i++)
                <tr>
                    <td style="height: 18px;"></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor

                <!-- Summary Calculation Section -->
                <tr>
                    <td colspan="2" class="text-right font-bold">GRAND TOTAL</td>
                    <td class="text-right font-bold">
                        {{ $totalCost > 0 ? number_format($totalCost, 0, ',', '.') : '0' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right font-bold">UANG MUKA EX PPB NO : </td>
                    <td class="text-right font-bold">-</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right font-bold">BALANCE YANG AKAN DITRANSFER / DIKEMBALIKAN</td>
                    <td class="text-right font-bold">
                        {{ $totalCost > 0 ? number_format($totalCost, 0, ',', '.') : '0' }}
                    </td>
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
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
