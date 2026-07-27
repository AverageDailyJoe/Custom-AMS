<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM PENGAJUAN ASET RUSAK - {{ $disposeAset->disposal_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11.5px; color: #000; margin: 0; padding: 15px; background: #fff; line-height: 1.3; }
        .container { max-width: 800px; margin: 0 auto; border: 1.5px solid #000; padding: 20px; }
        
        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; border-collapse: collapse; }
        .header-table td { border: none; vertical-align: middle; }
        .logo-img { height: 50px; width: auto; }
        .doc-title { font-size: 16px; font-weight: bold; text-align: center; text-transform: uppercase; margin: 0; }
        .company-sub { font-size: 11px; font-weight: bold; text-align: center; color: #374151; margin-top: 2px; }

        .meta-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; font-size: 11px; }
        .meta-table td { padding: 3px 4px; vertical-align: middle; border: none; }
        .border-bottom { border-bottom: 1px solid #000 !important; }

        .disp-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .disp-table th, .disp-table td { border: 1px solid #000; padding: 6px 8px; font-size: 11px; vertical-align: top; }
        .disp-table th { background: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }

        .statement-box { border: 1.5px solid #000; padding: 8px 12px; margin: 15px 0; text-align: center; font-weight: bold; font-size: 11px; background: #fafafa; }
        
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .sig-table th, .sig-table td { border: 1px solid #000; padding: 6px 4px; font-size: 10.5px; text-align: center; }
        .sig-table th { background: #f3f4f6; font-weight: bold; }
        .sig-space { height: 50px; }

        .img-grid { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .img-grid td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
        .attach-img { max-height: 140px; max-width: 100%; border-radius: 2px; }

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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD DISPOSAL (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 25%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 75%; text-align: center; padding-right: 25%;">
                    <div class="doc-title">FORM PENGAJUAN ASET RUSAK</div>
                    <div class="company-sub">PT GONDOWANGI TRADISIONAL KOSMETIKA (DISPOSE ASET IT)</div>
                </td>
            </tr>
        </table>

        @php
            $date = $disposeAset->disposal_date ? \Carbon\Carbon::parse($disposeAset->disposal_date) : \Carbon\Carbon::now();
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
                <td style="width: 12%;"><strong>No. Disposal</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 40%;" class="border-bottom"><strong>{{ $disposeAset->disposal_number }}</strong></td>
                <td style="width: 14%;"><strong>Metode Disposal</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 30%;" class="border-bottom"><span style="text-transform: uppercase; font-weight: bold;">{{ $disposeAset->disposal_type }}</span></td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ $dayNum }} {{ $monthName }} {{ $yearNum }}</td>
                <td><strong>Status</strong></td>
                <td>:</td>
                <td class="border-bottom"><span style="text-transform: uppercase; font-weight: bold;">{{ $disposeAset->status }}</span></td>
            </tr>
        </table>

        <div style="font-weight: bold; font-size: 11px; margin: 10px 0 5px;">RINCIAN ASET / UNIT YANG DIAJUKAN DISPOSAL:</div>
        <table class="disp-table">
            <thead>
                <tr>
                    <th style="width: 18%;">ID INVENTARIS</th>
                    <th style="width: 32%;">DESKRIPSI ASET</th>
                    <th style="width: 32%;">KETERANGAN / BUKTI KERUSAKAN</th>
                    <th style="width: 18%;">PROSES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-bold">{{ $disposeAset->asset_tag }}</td>
                    <td>
                        <div class="font-bold">{{ $disposeAset->asset_name }}</div>
                        <div style="font-size: 10px; color: #374151; margin-top: 2px;">
                            <strong>Serial Number:</strong> {{ $disposeAset->asset?->serial ?? '-' }}<br>
                            <strong>Kategori:</strong> {{ $disposeAset->asset?->assetModel?->category?->name ?? 'Aset IT' }}
                        </div>
                    </td>
                    <td>
                        <div>{!! nl2br(e($disposeAset->disposal_reason)) !!}</div>
                        @if($disposeAset->estimated_salvage_value)
                            <div style="font-size: 10px; margin-top: 4px;"><strong>Est. Nilai Jual:</strong> Rp {{ number_format($disposeAset->estimated_salvage_value, 0, ',', '.') }}</div>
                        @endif
                    </td>
                    <td class="text-center font-bold">
                        @if($disposeAset->disposal_type === 'sale')
                            PENJUALAN
                        @elseif($disposeAset->disposal_type === 'destruction')
                            PEMUSNAHAN
                        @elseif($disposeAset->disposal_type === 'trade_in')
                            TRADE-IN
                        @else
                            SCRAP
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        @if(!empty($disposeAset->attachments))
        <div style="font-weight: bold; font-size: 11px; margin: 10px 0 5px;">FOTO ASSET & BUKTI KERUSAKAN:</div>
        <table class="img-grid">
            <tr>
                @foreach($disposeAset->attachments as $img)
                    <td>
                        <img src="{{ asset('storage/' . $img) }}" alt="Foto Asset" class="attach-img">
                    </td>
                @endforeach
            </tr>
        </table>
        @endif

        <div style="font-weight: bold; font-size: 10.5px; margin-top: 15px;">PERSETUJUAN PERBAIKAN / PENGAJUAN RUSAK:</div>
        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 33.33%;">DIBUAT OLEH (PIC IT)</th>
                    <th style="width: 33.33%;">SUPERVISOR IT</th>
                    <th style="width: 33.33%;">MANAGER IT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->created_by_name ?? 'Bambang Yulianto' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">PIC Aset / IT Staff</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->spv_name ?? 'Supervisor IT' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">Supervisor IT</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->manager_name ?? 'SETYADI CANDRAWINATA' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">Manager IT / GM Finance & Ops</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="statement-box">
            Aset diserahkan ke GA (General Affairs), untuk selanjutnya dilakukan penjualan / pemusnahan
        </div>

        <div style="font-weight: bold; font-size: 10.5px; margin-top: 10px;">SERAH TERIMA ASET KE GENERAL AFFAIRS (GA):</div>
        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 50%;">DISERAHKAN (PIC ASET / IT)</th>
                    <th style="width: 50%;">PENERIMA (GA - GENERAL AFFAIRS)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->created_by_name ?? 'Bambang Yulianto' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">IT Department</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->ga_recipient_name ?? 'General Affairs (GA)' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">General Affairs Dept</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                    <td>Tgl {{ date('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
