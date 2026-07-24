<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Serah Terima - {{ $checkout->asset->asset_tag }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 13px; color: #111827; margin: 0; padding: 20px; background: #fff; }
        .container { max-width: 800px; margin: 0 auto; border: 1px solid #d1d5db; padding: 30px; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px solid #1f2937; padding-bottom: 15px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 20px; text-transform: uppercase; color: #1f2937; letter-spacing: 0.5px; }
        .header p { margin: 4px 0 0; color: #6b7280; font-size: 12px; }
        .section-title { font-weight: bold; font-size: 14px; background: #f3f4f6; padding: 6px 12px; border-left: 4px solid #2563eb; margin: 20px 0 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th, table td { border: 1px solid #e5e7eb; padding: 8px 12px; text-align: left; font-size: 12px; }
        table th { background: #f9fafb; font-weight: 600; color: #374151; width: 30%; }
        .sig-table { margin-top: 40px; border: none; }
        .sig-table td { border: none; text-align: center; vertical-align: top; width: 33.33%; padding: 0; }
        .sig-space { height: 75px; }
        .no-print { margin-bottom: 20px; text-align: right; }
        .btn-print { background: #2563eb; color: white; border: none; padding: 8px 16px; font-size: 13px; font-weight: 600; border-radius: 6px; cursor: pointer; }
        .btn-print:hover { background: #1d4ed8; }
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
            <h2>FORM SERAH TERIMA PERANGKAT IT</h2>
            <p>Bukti Penyerahan & Serah Terima Inventaris Komputer / Laptop Perusahaan</p>
        </div>

        <div class="section-title">I. INFORMASI PENYERAHAN</div>
        <table>
            <tr>
                <th>Tanggal Penyerahan</th>
                <td>{{ $checkout->checked_out_at ? $checkout->checked_out_at->translatedFormat('d F Y H:i') : '-' }} WIB</td>
            </tr>
            <tr>
                <th>Petugas IT (Admin)</th>
                <td>{{ $checkout->checkedOutByUser?->name ?? 'Admin' }}</td>
            </tr>
            <tr>
                <th>Pengguna 1 (Utama)</th>
                <td><strong>{{ $checkout->primary_user ?? '-' }}</strong></td>
            </tr>
            <tr>
                <th>Pengguna 2 (Pendamping)</th>
                <td>{{ $checkout->secondary_user ?? '-' }}</td>
            </tr>
            <tr>
                <th>Departemen / Divisi</th>
                <td>{{ $checkout->asset->department ?? '-' }}</td>
            </tr>
            <tr>
                <th>Lokasi Unit</th>
                <td>{{ $checkout->asset->location?->name ?? '-' }} ({{ $checkout->asset->room ?? '-' }})</td>
            </tr>
        </table>

        <div class="section-title">II. SPESIFIKASI & DETAIL UNIT</div>
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
                <th>Processor & Memory (RAM)</th>
                <td>{{ $checkout->asset->processor ?? '-' }} | RAM: {{ $checkout->asset->ram ?? '-' }}</td>
            </tr>
            <tr>
                <th>Penyimpanan (HDD / SSD)</th>
                <td>SSD: {{ $checkout->asset->storage_ssd ?? '-' }} | HDD: {{ $checkout->asset->storage_hdd ?? '-' }}</td>
            </tr>
            <tr>
                <th>Monitor Unit</th>
                <td>ID: {{ $checkout->asset->monitor_id ?? '-' }} ({{ $checkout->asset->monitor_spec ?? '-' }})</td>
            </tr>
            <tr>
                <th>Kondisi Fisik Saat Serah Terima</th>
                <td><span style="text-transform: uppercase; font-weight: bold; color: #059669;">{{ $checkout->asset->condition ?? 'BAGUS' }}</span></td>
            </tr>
            <tr>
                <th>Catatan Checkout</th>
                <td>{{ $checkout->checkout_notes ?? 'Penyerahan unit inventaris IT baru/pengganti.' }}</td>
            </tr>
        </table>

        <div class="section-title">III. DOKUMENTASI TANDA TANGAN SERAH TERIMA</div>
        <table class="sig-table">
            <tr>
                <td>
                    <p>Yang Menyerahkan,<br><strong>Petugas IT Staff</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->checkedOutByUser?->name ?? 'Admin IT' }} )</u></p>
                </td>
                <td>
                    <p>Yang Menerima,<br><strong>Pengguna (User)</strong></p>
                    <div class="sig-space"></div>
                    <p><u>( {{ $checkout->primary_user ?? 'Pengguna Unit' }} )</u></p>
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
