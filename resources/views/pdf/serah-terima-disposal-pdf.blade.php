<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SERAH TERIMA DISPOSAL - {{ $disposeAset->disposal_number }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11.5px; color: #000; margin: 0; padding: 15px; background: #fff; line-height: 1.35; }
        .container { max-width: 800px; margin: 0 auto; border: 1.5px solid #000; padding: 20px; }
        
        .header-table { width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; border-collapse: collapse; }
        .header-table td { border: none; vertical-align: middle; }
        .logo-img { height: 50px; width: auto; }
        .doc-title { font-size: 15px; font-weight: bold; text-align: center; text-transform: uppercase; margin: 0; }
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

        .statement-box { border: 1px solid #9ca3af; padding: 10px 14px; margin: 12px 0; font-size: 11px; background: #f9fafb; border-radius: 4px; line-height: 1.5; }
        
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .sig-table th, .sig-table td { border: 1px solid #000; padding: 6px 4px; font-size: 10.5px; text-align: center; }
        .sig-table th { background: #f3f4f6; font-weight: bold; text-transform: uppercase; }
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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD BERITA ACARA SERAH TERIMA (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 25%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 75%; text-align: center; padding-right: 25%;">
                    <div class="doc-title">BERITA ACARA SERAH TERIMA DISPOSAL / PENJUALAN ASET</div>
                    <div class="company-sub">PT GONDOWANGI TRADISIONAL KOSMETIKA</div>
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

            $categoryLabel = match($disposeAset->disposal_type) {
                'sale' => 'Penjualan Aset',
                'scrap' => 'Scrap / Afkir',
                'trade_in' => 'Trade-In / Tukar Tambah',
                default => 'Disposal Aset',
            };
        @endphp

        <table class="meta-table">
            <tr>
                <td style="width: 15%;"><strong>Merujuk No. Disposal</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;" class="border-bottom"><strong>{{ $disposeAset->disposal_number }}</strong></td>
                <td style="width: 15%;"><strong>Kategori Disposal</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 28%;" class="border-bottom"><span style="font-weight: bold; text-transform: uppercase;">{{ $categoryLabel }}</span></td>
            </tr>
            <tr>
                <td><strong>Tanggal Penyerahan</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ $dayNum }} {{ $monthName }} {{ $yearNum }}</td>
                <td><strong>Status Transaksi</strong></td>
                <td>:</td>
                <td class="border-bottom"><span style="font-weight: bold; text-transform: uppercase;">{{ $disposeAset->status === 'completed' ? 'SELESAI (COMPLETED)' : 'PROSES SERAH TERIMA' }}</span></td>
            </tr>
        </table>

        <div class="statement-box">
            Pada hari ini, <strong>{{ $dayNum }} {{ $monthName }} {{ $yearNum }}</strong>, bertempat di lingkungan PT Gondowangi Tradisional Kosmetika, telah dilaksanakan serah terima fisik barang/unit aset IT berstatus afkir/disposal sebagai berikut:
        </div>

        <div style="font-weight: bold; font-size: 11px; margin: 10px 0 5px;">RINCIAN BARANG / UNIT DISPOSAL YANG DISERAHKAN:</div>
        <table class="disp-table">
            <thead>
                <tr>
                    <th style="width: 6%;">NO</th>
                    <th style="width: 18%;">ID INVENTARIS</th>
                    <th style="width: 34%;">DESKRIPSI & SPESIFIKASI BARANG</th>
                    <th style="width: 22%;">KONDISI FISIK / KETERANGAN</th>
                    <th style="width: 20%;">NILAI / HARGA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-bold">1</td>
                    <td class="text-center font-bold">{{ $disposeAset->asset_tag }}</td>
                    <td>
                        <div class="font-bold">{{ $disposeAset->asset_name }}</div>
                        <div style="font-size: 10px; color: #374151; margin-top: 2px;">
                            <strong>Serial Number:</strong> {{ $disposeAset->asset?->serial ?? '-' }}<br>
                            <strong>Kategori:</strong> {{ $disposeAset->asset?->assetModel?->category?->name ?? 'Aset IT' }}<br>
                            <strong>Spesifikasi:</strong> {{ $disposeAset->asset?->processor ? $disposeAset->asset->processor . ', RAM ' . $disposeAset->asset->ram . 'GB' : '-' }}
                        </div>
                    </td>
                    <td>
                        <div>{!! nl2br(e($disposeAset->disposal_reason)) !!}</div>
                        <div style="font-size: 10px; color: #b91c1c; font-weight: bold; margin-top: 3px;">Kondisi: Afkir / As-Is</div>
                    </td>
                    <td class="text-right font-bold">
                        @if($disposeAset->selling_price && $disposeAset->selling_price > 0)
                            Rp {{ number_format($disposeAset->selling_price, 0, ',', '.') }}
                        @elseif($disposeAset->estimated_salvage_value && $disposeAset->estimated_salvage_value > 0)
                            Rp {{ number_format($disposeAset->estimated_salvage_value, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="font-size: 10.5px; line-height: 1.45; margin: 10px 0;">
            <strong>Ketentuan Serah Terima:</strong>
            <ol style="margin: 3px 0 10px 18px; padding: 0;">
                <li>Barang/unit aset di atas diserahkan dalam kondisi apa adanya (<em>as-is condition</em>) sesuai hasil evaluasi dan verifikasi teknis Tim IT.</li>
                <li>Setelah penandatanganan berita acara serah terima ini, seluruh tanggung jawab pengangkutan, penyimpanan, dan pemanfaatan barang beralih sepenuhnya kepada PIHAK KEDUA.</li>
            </ol>
        </div>

        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 33.33%;">PIHAK PERTAMA<br>(YANG MENYERAHKAN / IT & GA)</th>
                    <th style="width: 33.33%;">PIHAK KEDUA<br>(PENERIMA / PEMBELI / VENDOR)</th>
                    <th style="width: 33.33%;">MENGETAHUI<br>(HEAD OF IT / FINANCE)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->created_by_name ?? 'Bambang Yulianto' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">IT / GA Department</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->buyer_name ?? ($disposeAset->ga_recipient_name ?? 'Pihak Penerima / Pembeli') }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">{{ $disposeAset->buyer_contact ?? 'Pembeli / Vendor Scrap' }}</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->manager_name ?? 'SETYADI CANDRAWINATA' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">Manager IT / GM Finance & Ops</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl: {{ date('d/m/Y') }}</td>
                    <td>Tgl: {{ date('d/m/Y') }}</td>
                    <td>Tgl: {{ date('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
