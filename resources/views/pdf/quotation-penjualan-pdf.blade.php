<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUOTATION PENJUALAN DISPOSAL - {{ $disposeAset->disposal_number }}</title>
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

        .quot-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .quot-table th, .quot-table td { border: 1px solid #000; padding: 6px 8px; font-size: 11px; vertical-align: top; }
        .quot-table th { background: #f3f4f6; font-weight: bold; text-align: center; text-transform: uppercase; }
        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }

        .terms-box { border: 1px solid #9ca3af; padding: 8px 12px; margin: 10px 0; font-size: 10.5px; background: #f9fafb; border-radius: 4px; line-height: 1.45; }
        
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
            <button onclick="window.print()" class="btn-print">🖨️ CETAK / DOWNLOAD QUOTATION PENJUALAN (PDF)</button>
        </div>

        <table class="header-table">
            <tr>
                <td style="width: 25%;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
                </td>
                <td style="width: 75%; text-align: center; padding-right: 25%;">
                    <div class="doc-title">PENAWARAN HARGA PENJUALAN ASET BEKAS & SCRAP (QUOTATION)</div>
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
                'sale' => 'Penjualan Aset Bekas',
                'scrap' => 'Penjualan Scrap / Afkir',
                'trade_in' => 'Penawaran Trade-In',
                default => 'Penjualan Disposal',
            };

            $price = ($disposeAset->selling_price && $disposeAset->selling_price > 0)
                ? (float) $disposeAset->selling_price
                : (float) ($disposeAset->estimated_salvage_value ?? 0);
        @endphp

        <table class="meta-table">
            <tr>
                <td style="width: 15%;"><strong>No. Quotation</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 38%;" class="border-bottom"><strong>QUOT/{{ $disposeAset->disposal_number }}</strong></td>
                <td style="width: 15%;"><strong>Kepada Yth.</strong></td>
                <td style="width: 2%;">:</td>
                <td style="width: 28%;" class="border-bottom"><strong>{{ $disposeAset->buyer_name ?? 'Calon Pembeli / Vendor Scrap' }}</strong></td>
            </tr>
            <tr>
                <td><strong>Merujuk No. Disposal</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ $disposeAset->disposal_number }}</td>
                <td><strong>Kontak / Alamat</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ $disposeAset->buyer_contact ?? ($disposeAset->buyer_address ?? '-') }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Penawaran</strong></td>
                <td>:</td>
                <td class="border-bottom">{{ $dayNum }} {{ $monthName }} {{ $yearNum }}</td>
                <td><strong>Kategori Penjualan</strong></td>
                <td>:</td>
                <td class="border-bottom"><span style="font-weight: bold; text-transform: uppercase;">{{ $categoryLabel }}</span></td>
            </tr>
        </table>

        <div style="font-weight: bold; font-size: 11px; margin: 10px 0 5px;">RINCIAN BARANG & PENAWARAN HARGA:</div>
        <table class="quot-table">
            <thead>
                <tr>
                    <th style="width: 6%;">NO</th>
                    <th style="width: 18%;">ID INVENTARIS</th>
                    <th style="width: 38%;">DESKRIPSI BARANG & KATEGORI</th>
                    <th style="width: 16%;">KONDISI</th>
                    <th style="width: 22%;">PENAWARAN HARGA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center font-bold">1</td>
                    <td class="text-center font-bold">{{ $disposeAset->asset_tag }}</td>
                    <td>
                        <div class="font-bold">{{ $disposeAset->asset_name }}</div>
                        <div style="font-size: 10px; color: #374151; margin-top: 2px;">
                            <strong>Kategori:</strong> {{ $categoryLabel }} ({{ $disposeAset->asset?->assetModel?->category?->name ?? 'Aset IT' }})<br>
                            <strong>Serial Number:</strong> {{ $disposeAset->asset?->serial ?? '-' }}<br>
                            <strong>Spesifikasi:</strong> {{ $disposeAset->asset?->processor ? $disposeAset->asset->processor . ', RAM ' . $disposeAset->asset->ram . 'GB' : '-' }}
                        </div>
                    </td>
                    <td>
                        <div>{!! nl2br(e($disposeAset->disposal_reason)) !!}</div>
                        <div style="font-size: 10px; color: #b91c1c; font-weight: bold; margin-top: 2px;">Status: As-is / Afkir</div>
                    </td>
                    <td class="text-right font-bold" style="font-size: 12px;">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="background: #f9fafb;">
                    <td colspan="4" class="text-right font-bold">TOTAL HARGA PENAWARAN:</td>
                    <td class="text-right font-bold" style="font-size: 12px; color: #065f46;">
                        Rp {{ number_format($price, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="terms-box">
            <strong>Syarat & Ketentuan Penjualan (Terms & Conditions):</strong>
            <ol style="margin: 3px 0 0 16px; padding: 0;">
                <li>Harga di atas adalah penawaran resmi untuk penjualan unit aset bekas / scrap dalam kondisi apa adanya (<em>as-is</em>).</li>
                <li>Pembayaran dilakukan secara penuh (Tunai / Transfer Bank ke rekening resmi perusahaan) sebelum unit diambil / diserahkan.</li>
                <li>Pengangkutan dan pemindahan barang dari lokasi PT Gondowangi Tradisional Kosmetika menjadi tanggung jawab pembeli.</li>
                <li>Barang yang sudah dibeli dan diserahterimakan tidak dapat dikembalikan.</li>
            </ol>
        </div>

        <table class="sig-table">
            <thead>
                <tr>
                    <th style="width: 50%;">DIAJUKAN OLEH (IT DEPARTMENT)</th>
                    <th style="width: 50%;">MENYETUJUI (FINANCE / OPERATIONS)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->created_by_name ?? 'Bambang Yulianto' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">IT Staff / PIC Aset</div>
                    </td>
                    <td>
                        <div class="sig-space"></div>
                        <div class="font-bold"><u>{{ $disposeAset->manager_name ?? 'SETYADI CANDRAWINATA' }}</u></div>
                        <div style="font-size: 9px; margin-top: 2px;">GM Finance & Operations</div>
                    </td>
                </tr>
                <tr>
                    <td>Tgl: {{ date('d/m/Y') }}</td>
                    <td>Tgl: {{ date('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
