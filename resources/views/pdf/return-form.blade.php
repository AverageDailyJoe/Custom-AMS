<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pengembalian - {{ $checkout->asset->asset_tag }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #111827; margin: 0; padding: 20px; background: #fff; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #d1d5db; padding: 30px; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #dc2626; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; color: #991b1b; letter-spacing: 0.5px; }
        .header p { margin: 4px 0 0; color: #6b7280; font-size: 12px; }
        .section-title { font-weight: bold; font-size: 14px; background: #fef2f2; padding: 6px 12px; border-left: 4px solid #dc2626; margin: 20px 0 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th, table td { border: 1px solid #e5e7eb; padding: 8px 12px; text-align: left; font-size: 12px; }
        table th { background: #f9fafb; font-weight: 600; color: #374151; width: 30%; }
        .sig-table { margin-top: 40px; border: none; }
        .sig-table td { border: none; text-align: center; vertical-align: top; width: 33.33%; padding: 0; }
        .sig-space { height: 75px; }
        .no-print { margin-bottom: 20px; text-align: right; }
        .btn-print { background: #dc2626; color: white; border: none; padding: 8px 16px; font-size: 13px; font-weight: 600; border-radius: 6px; cursor: pointer; }
        .btn-print:hover { background: #b91c1c; }
        @media print {
            .no-print { display: none; }
            .container { border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print">
            <button onclick="window.print()" class="btn-print">🖨️ Cetak / Download PDF</button>
        </div>

        <div class="header">
            <h2>FORM PENGEMBALIAN PERANGKAT IT</h2>
            <p>Bukti Pengembalian & Penyerahan Kembali Inventaris Komputer / Laptop Ke IT Dept</p>
        </div>

        <div class="section-title">I. INFORMASI PENGEMBALIAN</div>
        <table>
            <tr>
                <th>Tanggal Pengembalian (Checkin)</th>
                <td>{{ $checkout->checked_in_at ? $checkout->checked_in_at->translatedFormat('d F Y H:i') : now()->translatedFormat('d F Y H:i') }} WIB</td>
            </tr>
            <tr>
                <th>Petugas Penerima (Admin IT)</th>
                <td>{{ $checkout->checkedInByUser?->name ?? ($checkout->checkedOutByUser?->name ?? 'Admin IT') }}</td>
            </tr>
            <tr>
                <th>Pengguna Yang Mengembalikan</th>
                <td><strong>{{ $checkout->primary_user ?? '-' }}</strong></td>
            </tr>
            <tr>
                <th>Pengguna Pendamping</th>
                <td>{{ $checkout->secondary_user ?? '-' }}</td>
            </tr>
            <tr>
                <th>Departemen / Divisi</th>
                <td>{{ $checkout->asset->department ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">II. DETAIL UNIT & KONDISI PENGEMBALIAN</div>
        <table>
            <tr>
                <th>ID Inventaris (Asset Tag)</th>
                <td><strong>{{ $checkout->asset->asset_tag }}</strong></td>
            </tr>
            <tr>
                <th>Model / Type Unit</th>
                <td>{{ $checkout->asset->assetModel?->manufacturer }} {{ $checkout->asset->assetModel?->name }} ({{ $checkout->asset->assetModel?->category?->name }})</td>
            </tr>
            <tr>
                <th>Serial Number (SN)</th>
                <td>{{ $checkout->asset->serial ?? '-' }}</td>
            </tr>
            <tr>
                <th>Kondisi Unit Saat Dikembalikan</th>
                <td><span style="text-transform: uppercase; font-weight: bold; color: #dc2626;">{{ $checkout->asset->condition ?? 'BAGUS' }}</span></td>
            </tr>
            <tr>
                <th>Catatan Pengembalian</th>
                <td>{{ $checkout->checkin_notes ?? 'Unit telah diterima kembali oleh tim IT dalam kondisi lengkap.' }}</td>
            </tr>
        </table>

        <div class="section-title">III. DOKUMENTASI TANDA TANGAN PENGEMBALIAN</div>
        <table class="sig-table">
            <tr>
                <td>
                    <p>Yang Mengembalikan,<br><strong>Pengguna (User)</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->primary_user ?? 'Pengguna Unit' }} )</u></p>
                </td>
                <td>
                    <p>Yang Menerima,<br><strong>Petugas IT Staff</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->checkedInByUser?->name ?? ($checkout->checkedOutByUser?->name ?? 'Admin IT') }} )</u></p>
                </td>
                <td>
                    <p>Mengetahui,<br><strong>Atasan / SPV</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( ..................................... )</u></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
