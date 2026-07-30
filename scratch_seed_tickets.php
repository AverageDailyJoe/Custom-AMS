<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Carbon;

$user = User::first();
$location = Location::first();
$asset = Asset::first();

// Clear test tickets
Ticket::where('ticket_number', 'LIKE', 'TCK/%')->delete();

$testTickets = [
    [
        'ticket_number' => 'TCK/IT/2026/05/001',
        'reporter_name' => 'Rina Setyowati',
        'reporter_department' => 'Finance',
        'subject' => 'Laptop ThinkPad mati total indikator baterai berkedip',
        'description' => 'Laptop tidak bisa menyala sama sekali saat ditekan tombol power.',
        'category' => 'hardware',
        'priority' => 'high',
        'status' => 'pending_sparepart',
        'created_at' => Carbon::parse('2026-05-10 10:00:00'),
        'scheduled_date' => Carbon::parse('2026-05-11'),
        'due_date' => Carbon::parse('2026-05-13'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/05/002',
        'reporter_name' => 'Hendra Gunawan',
        'reporter_department' => 'Factory Jababeka',
        'subject' => 'Printer Thermal Line Produksi Macet',
        'description' => 'Hasil cetak label barcode tidak keluar dan kertas tersangkut.',
        'category' => 'printer_scanner',
        'priority' => 'critical',
        'status' => 'resolved',
        'created_at' => Carbon::parse('2026-05-20 14:00:00'),
        'scheduled_date' => Carbon::parse('2026-05-20'),
        'due_date' => Carbon::parse('2026-05-20'),
        'resolved_at' => Carbon::parse('2026-05-20 16:30:00'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/06/001',
        'reporter_name' => 'Budi Santoso',
        'reporter_department' => 'Gudang Surabaya',
        'subject' => 'Wi-Fi Ruijie Reyee Signal Drop di Area Loading Dock',
        'description' => 'Sinyal Wi-Fi sering terputus saat scan barang barcode.',
        'category' => 'network_wifi',
        'priority' => 'high',
        'status' => 'resolved',
        'created_at' => Carbon::parse('2026-06-05 09:30:00'),
        'scheduled_date' => Carbon::parse('2026-06-06'),
        'due_date' => Carbon::parse('2026-06-08'),
        'resolved_at' => Carbon::parse('2026-06-07 11:00:00'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/06/002',
        'reporter_name' => 'Dewi Sartika',
        'reporter_department' => 'HRD',
        'subject' => 'Reset Password Email Corp & Akses VPN',
        'description' => 'Password terblokir karena salah input 3x.',
        'category' => 'access_rights',
        'priority' => 'low',
        'status' => 'closed',
        'created_at' => Carbon::parse('2026-06-12 11:00:00'),
        'scheduled_date' => Carbon::parse('2026-06-12'),
        'due_date' => Carbon::parse('2026-06-15'),
        'resolved_at' => Carbon::parse('2026-06-12 13:00:00'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/06/003',
        'reporter_name' => 'Eko Prasetyo',
        'reporter_department' => 'Marketing',
        'subject' => 'Upgrade RAM 16GB DDR4 & SSD NVMe M.2 512GB',
        'description' => 'Rendering video promosi Natur terasa lambat.',
        'category' => 'hardware',
        'priority' => 'medium',
        'status' => 'rescheduled',
        'reschedule_reason' => 'Menunggu jadwal meeting launching produk selesai',
        'created_at' => Carbon::parse('2026-06-25 15:00:00'),
        'scheduled_date' => Carbon::parse('2026-06-28'),
        'due_date' => Carbon::parse('2026-06-30'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/07/001',
        'reporter_name' => 'Sri Wahyuni',
        'reporter_department' => 'Supply Chain',
        'subject' => 'Install Software SAP GUI & Adobe Photoshop',
        'description' => 'Permintaan install ulang aplikasi SAP GUI versi terbaru.',
        'category' => 'software',
        'priority' => 'low',
        'status' => 'resolved',
        'created_at' => Carbon::parse('2026-07-02 08:30:00'),
        'scheduled_date' => Carbon::parse('2026-07-03'),
        'due_date' => Carbon::parse('2026-07-06'),
        'resolved_at' => Carbon::parse('2026-07-04 10:00:00'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/07/002',
        'reporter_name' => 'Agus Setiawan',
        'reporter_department' => 'IT Operations',
        'subject' => 'Maintenance Rutin Clean Dust & Thermal Paste Server HO',
        'description' => 'Pembersihan fisik berkala dan pengecekan suhu server.',
        'category' => 'scheduled_service',
        'priority' => 'medium',
        'status' => 'resolved',
        'created_at' => Carbon::parse('2026-07-10 13:00:00'),
        'scheduled_date' => Carbon::parse('2026-07-12'),
        'due_date' => Carbon::parse('2026-07-14'),
        'resolved_at' => Carbon::parse('2026-07-13 16:00:00'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/07/003',
        'reporter_name' => 'Yanto',
        'reporter_department' => 'Sales',
        'subject' => 'Layar Monitor ViewSonic Garis Hijau & Flickering',
        'description' => 'Layar berkedip saat dinyalakan lebih dari 1 jam.',
        'category' => 'hardware',
        'priority' => 'medium',
        'status' => 'in_progress',
        'created_at' => Carbon::parse('2026-07-15 10:00:00'),
        'scheduled_date' => Carbon::parse('2026-07-27'),
        'due_date' => Carbon::parse('2026-07-29'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/07/004',
        'reporter_name' => 'Astoria',
        'reporter_department' => 'Marketing',
        'subject' => 'Setting IP Static & Network Gateway Printer CS',
        'description' => 'Printer tidak terdeteksi dari PC marketing.',
        'category' => 'network_wifi',
        'priority' => 'low',
        'status' => 'scheduled',
        'created_at' => Carbon::parse('2026-07-20 14:00:00'),
        'scheduled_date' => Carbon::parse('2026-07-28'),
        'due_date' => Carbon::parse('2026-07-30'),
    ],
    [
        'ticket_number' => 'TCK/IT/2026/07/005',
        'reporter_name' => 'Muhamad Farhan',
        'reporter_department' => 'IT',
        'subject' => 'Pengajuan Keyboard & Mouse Wireless Ergonomis',
        'description' => 'Penggantian mouse kabel yang klik kirinya macet.',
        'category' => 'hardware',
        'priority' => 'low',
        'status' => 'open',
        'created_at' => Carbon::parse('2026-07-25 09:00:00'),
        'scheduled_date' => Carbon::parse('2026-07-29'),
        'due_date' => Carbon::parse('2026-07-31'),
    ],
];

foreach ($testTickets as $data) {
    $data['location_id'] = $location?->id;
    $data['assigned_to'] = $user?->id;
    $data['assigned_to_name'] = $user?->name ?? 'Bambang Yulianto';
    $data['created_by'] = $user?->id;
    if ($asset) {
        $data['asset_id'] = $asset->id;
        $data['asset_tag'] = $asset->asset_tag;
        $data['asset_name'] = "{$asset->assetModel?->manufacturer} {$asset->assetModel?->name}";
    }

    $ticket = new Ticket($data);
    $ticket->created_at = $data['created_at'];
    $ticket->updated_at = $data['created_at'];
    $ticket->save();
}

echo "10 Test Tickets seeded successfully with historical created_at dates!\n";
