<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Stiker Label Tag Aset - Tom & Jerry 121</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm 6mm;
        }

        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f4f6f8;
            padding: 15px;
            display: flex;
            justify-content: center;
        }

        .no-print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1e293b;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            z-index: 9999;
        }

        .btn-print {
            background: #2563eb;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-print:hover {
            background: #1d4ed8;
        }

        .sheet-container {
            width: 162mm;
            background: white;
            padding: 4mm 3mm;
            border: 1px dashed #cbd5e1;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        /* 2 Columns x 5 Rows Grid for Tom & Jerry 121 */
        .grid-121 {
            display: grid;
            grid-template-columns: 76mm 76mm;
            grid-template-rows: repeat(5, 38mm);
            column-gap: 4mm;
            row-gap: 2.5mm;
        }

        .sticker-card {
            width: 76mm;
            height: 38mm;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 3mm 4mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            position: relative;
            background: #ffffff;
            overflow: hidden;
        }

        .sticker-blank {
            width: 76mm;
            height: 38mm;
            border: 1px dashed #e2e8f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 11px;
            font-weight: bold;
            background: #fafafa;
        }

        .company-header {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
            border-bottom: 1.5px solid #0f172a;
            padding-bottom: 1.5px;
            width: 100%;
            text-align: center;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 1.5mm;
            margin-bottom: 1mm;
        }

        .qr-code-box {
            width: 17mm;
            height: 17mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code-box svg {
            width: 100%;
            height: 100%;
        }

        /* Asset Tag placed directly underneath QR Code as requested */
        .asset-tag-label {
            font-size: 11px;
            font-weight: 900;
            color: #000000;
            margin-top: 1mm;
            letter-spacing: 0.6px;
            text-align: center;
        }

        .asset-info {
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #334155;
            line-height: 1.25;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .asset-model {
            font-weight: bold;
            color: #0f172a;
        }

        .asset-location {
            font-size: 7.5px;
            color: #64748b;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }

            body {
                background: white;
                padding: 0;
            }

            .sheet-container {
                border: none;
                box-shadow: none;
                margin-top: 0;
                padding: 0;
                width: 100%;
            }

            .sticker-card {
                border: none !important;
            }

            .sticker-blank {
                border: none !important;
                background: transparent !important;
                color: transparent !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div>
            <strong>🖨️ Cetak Stiker Tag Aset (Tom & Jerry No. 121)</strong>
            <span style="font-size: 12px; color: #94a3b8; margin-left: 10px;">Format: 76mm x 38mm (2 Kolom x 5 Baris)</span>
        </div>
        <div>
            <button class="btn-print" onclick="window.print()">Print / Simpan PDF</button>
        </div>
    </div>

    <div class="sheet-container">
        <div class="grid-121">
            @for ($slotNum = 1; $slotNum <= 10; $slotNum++)
                @if (isset($mappedSlots[$slotNum]) && $mappedSlots[$slotNum])
                    @php $asset = $mappedSlots[$slotNum]; @endphp
                    <div class="sticker-card">
                        <div class="company-header">PT GONDOWANGI KOSMETIKA</div>
                        
                        <div class="qr-section">
                            <div class="qr-code-box">
                                {!! \App\Helpers\QrCodeHelper::generateSvg($asset->asset_tag, 90) !!}
                            </div>
                            <!-- ID Inventaris / Tag TEPAT DI BAWAH QR CODE -->
                            <div class="asset-tag-label">{{ $asset->asset_tag }}</div>
                        </div>

                        <div class="asset-info">
                            <div class="asset-model">{{ Str::limit(($asset->assetModel?->manufacturer ? $asset->assetModel->manufacturer . ' ' : '') . ($asset->assetModel?->name ?? 'Aset IT'), 30) }}</div>
                            <div class="asset-location">
                                {{ $asset->location?->name ?? 'HQ' }} 
                                @if($asset->department) | {{ $asset->department }} @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="sticker-blank">
                        Slot {{ $slotNum }} (Kosong)
                    </div>
                @endif
            @endfor
        </div>
    </div>

</body>
</html>
