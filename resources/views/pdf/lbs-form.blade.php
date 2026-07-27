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
        .header-title { font-weight: bold; font-size: 16px; margin: 0 0 3px; text-transform: uppercase; }
        .header-sub { font-weight: bold; font-size: 13.5px; margin: 0; letter-spacing: 1px; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px; }
        .meta-table td { padding: 3px 4px; vertical-align: middle; border: none; }
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
            <div class="header-title">PT. Gondowangi Tradisional Kosmetika</div>
            <div class="header-sub">LAPORAN BIAYA SETTLEMENT ( L B S )</div>
        </div>

        @php
            $date = $pengajuanAset->request_date ? \Carbon\Carbon::parse($pengajuanAset->request_date) : \Carbon\Carbon::now();
            $period = $date->format('m/y');
        @endphp

        <table class="meta-table">
            <tr>
                <td style="width: 8%;"><strong>Nama</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 45%;" class="border-bottom"><strong>{{ $pengajuanAset->requester_name }}</strong></td>
                <td style="width: 10%;"><strong>Nomor</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 33%;" class="border-bottom"><strong>{{ $pengajuanAset->request_number }}</strong></td>
            </tr>
            <tr>
                <td><strong>Area</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ $pengajuanAset->requester_department }} - Head Office</td>
                <td><strong>Period</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ $period }}</td>
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
                    <td>FOTOCOPY & CETAKAN</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">6</td>
                    <td>EXPEDISI</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">7</td>
                    <td>MATERIAL PROMOSI</td>
                    <td class="text-right">-</td>
                </tr>
                <tr>
                    <td class="text-center font-bold">8</td>
                    <td>SPONSORSHIP</td>
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
                        1. <strong>{{ $pengajuanAset->title }}</strong>
                        @if($pengajuanAset->specification_requested)
                            <br><small style="color: #374151;">Spec: {{ $pengajuanAset->specification_requested }}</small>
                        @endif
                    </td>
                    <td class="text-right font-bold">
                        {{ $pengajuanAset->estimated_cost ? number_format($pengajuanAset->estimated_cost, 0, ',', '.') : '-' }}
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
                        {{ $pengajuanAset->estimated_cost ? number_format($pengajuanAset->estimated_cost, 0, ',', '.') : '0' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right font-bold">UANG MUKA EX PPB NO : {{ $pengajuanAset->request_number }}</td>
                    <td class="text-right font-bold">
                        {{ $pengajuanAset->estimated_cost ? number_format($pengajuanAset->estimated_cost, 0, ',', '.') : '0' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right font-bold">BALANCE YANG AKAN DITRANSFER / DIKEMBALIKAN</td>
                    <td class="text-right font-bold">0</td>
                </tr>
            </tbody>
        </table>

        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 25%;">DIBUAT</th>
                    <th style="width: 25%;">DIPERIKSA</th>
                    <th style="width: 25%;">DISETUJUI</th>
                    <th style="width: 25%;">DIPERIKSA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $pengajuanAset->requester_name }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">Pemohon</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>IT Manager / SPV</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">Information Technology</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $pengajuanAset->approver_name ?? 'SETYADI CANDRAWINATA' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">{{ $pengajuanAset->approver_title ?? 'GM Finance & Operations' }}</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>( ..................................... )</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">Finance / Accounting</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
