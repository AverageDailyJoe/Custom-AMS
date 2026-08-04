<?php

namespace App\Http\Controllers;

use App\Models\Checkout;

class HandoverFormController extends Controller
{
    public function downloadHandover(Checkout $checkout)
    {
        $checkout->load(['asset.assetModel.category', 'asset.location', 'checkedOutByUser', 'checkedInByUser']);
        return view('pdf.handover-form', compact('checkout'));
    }

    public function downloadReturn(Checkout $checkout)
    {
        $checkout->load(['asset.assetModel.category', 'asset.location', 'checkedOutByUser', 'checkedInByUser']);
        return view('pdf.return-form', compact('checkout'));
    }

    public function downloadBeritaAcara(\App\Models\BeritaAcara $beritaAcara)
    {
        $beritaAcara->load(['asset.assetModel.category', 'createdBy']);
        return view('pdf.berita-acara-form', compact('beritaAcara'));
    }

    public function downloadPengajuanAset(\App\Models\PengajuanAset $pengajuanAset)
    {
        $pengajuanAset->load(['createdBy']);
        return view('pdf.pengajuan-aset-form', compact('pengajuanAset'));
    }

    public function downloadLBS(\App\Models\PengajuanAset $pengajuanAset)
    {
        $pengajuanAset->load(['createdBy']);
        $qty = (int) ($pengajuanAset->quantity ?? 1);
        if ($qty < 1) {
            $qty = 1;
        }
        $unitCost = (float) ($pengajuanAset->estimated_cost ?? 0);
        $totalCost = $unitCost * $qty;

        return view('pdf.lbs-form', compact('pengajuanAset', 'qty', 'unitCost', 'totalCost'));
    }

    public function downloadDisposal(\App\Models\DisposeAset $disposeAset)
    {
        $disposeAset->load(['asset.assetModel.category', 'createdBy']);
        return view('pdf.disposal-form', compact('disposeAset'));
    }

    public function downloadTicket(\App\Models\Ticket $ticket)
    {
        $ticket->load(['location', 'asset.assetModel.category', 'assignedToUser', 'createdBy']);
        return view('pdf.ticket-form', compact('ticket'));
    }

    public function downloadRekapAset(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Asset::with(['assetModel.category', 'location']);

        // Filter Location
        $locationLabel = 'Semua Lokasi (HQ, Cikarang, Surabaya)';
        if ($request->filled('location_id') && $request->location_id !== 'all') {
            $query->where('location_id', $request->location_id);
            $loc = \App\Models\Location::find($request->location_id);
            if ($loc) {
                $locationLabel = $loc->name;
            }
        }

        // Filter Period
        $periodType = $request->input('period_type', 'all');
        $periodLabel = 'Semua Periode Data Aset';

        if ($periodType === 'weekly' && $request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
            $periodLabel = \Carbon\Carbon::parse($request->from_date)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($request->to_date)->format('d/m/Y');
        } elseif ($periodType === 'monthly' && $request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)->whereYear('created_at', $request->year);
            $monthName = \Carbon\Carbon::create()->month((int)$request->month)->translatedFormat('F');
            $periodLabel = "Bulan {$monthName} {$request->year}";
        } elseif ($periodType === 'yearly' && $request->filled('year')) {
            $query->whereYear('created_at', $request->year);
            $periodLabel = "Tahun {$request->year}";
        }

        $assets = $query->orderBy('asset_tag', 'asc')->get();

        $stats = [
            'total' => $assets->count(),
            'in_stock' => $assets->where('status', 'in_stock')->count(),
            'checked_out' => $assets->where('status', 'checked_out')->count(),
            'in_repair' => $assets->where('status', 'in_repair')->count(),
            'disposed' => $assets->where('status', 'disposed')->count(),
        ];

        return view('pdf.rekap-aset-pdf', compact('assets', 'stats', 'periodLabel', 'locationLabel'));
    }

    public function downloadRekapTiket(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Ticket::with(['location', 'asset.assetModel', 'assignedToUser']);

        // Filter Status
        $statusLabel = 'Semua Status Tiket';
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
            $statusLabel = strtoupper($request->status);
        }

        // Filter Period
        $periodType = $request->input('period_type', 'all');
        $periodLabel = 'Semua Periode Tiket IT';

        if ($periodType === 'weekly' && $request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('scheduled_date', [$request->from_date, $request->to_date]);
            $periodLabel = \Carbon\Carbon::parse($request->from_date)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($request->to_date)->format('d/m/Y');
        } elseif ($periodType === 'monthly' && $request->filled('month') && $request->filled('year')) {
            $query->whereMonth('scheduled_date', $request->month)->whereYear('scheduled_date', $request->year);
            $monthName = \Carbon\Carbon::create()->month((int)$request->month)->translatedFormat('F');
            $periodLabel = "Bulan {$monthName} {$request->year}";
        } elseif ($periodType === 'yearly' && $request->filled('year')) {
            $query->whereYear('scheduled_date', $request->year);
            $periodLabel = "Tahun {$request->year}";
        }

        $tickets = $query->orderBy('scheduled_date', 'desc')->get();

        $totalCount = $tickets->count();
        $resolvedCount = $tickets->whereIn('status', ['resolved', 'closed'])->count();
        $inProgressCount = $tickets->whereIn('status', ['open', 'scheduled', 'in_progress', 'rescheduled'])->count();
        $pendingPartCount = $tickets->where('status', 'pending_sparepart')->count();
        $slaRate = $totalCount > 0 ? round(($resolvedCount / $totalCount) * 100, 1) : 100;

        $stats = [
            'total' => $totalCount,
            'resolved' => $resolvedCount,
            'in_progress' => $inProgressCount,
            'pending_part' => $pendingPartCount,
            'sla_compliance' => $slaRate,
        ];

        return view('pdf.rekap-tiket-pdf', compact('tickets', 'stats', 'periodLabel', 'statusLabel'));
    }

    public function exportAsetExcel(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\Asset::with(['assetModel.category', 'location']);

        if ($request->filled('location_id') && $request->location_id !== 'all') {
            $query->where('location_id', $request->location_id);
        }

        $periodType = $request->input('period_type', 'all');
        if ($periodType === 'weekly' && $request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [$request->from_date . ' 00:00:00', $request->to_date . ' 23:59:59']);
        } elseif ($periodType === 'monthly' && $request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)->whereYear('created_at', $request->year);
        } elseif ($periodType === 'yearly' && $request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        $assets = $query->orderBy('asset_tag', 'asc')->get();

        $filename = 'Rekap_Aset_IT_Gondowangi_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['ID Inventaris Tag', 'Serial Number', 'Model / Merk', 'Kategori', 'Processor', 'RAM', 'Storage', 'OS', 'Pengguna Utama', 'Departemen', 'Ruangan', 'Lokasi', 'Status', 'Tahun Beli', 'Harga Beli (Rp)']);

            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->asset_tag,
                    $asset->serial ?? '-',
                    ($asset->assetModel?->manufacturer . ' ' . $asset->assetModel?->name),
                    $asset->assetModel?->category?->name ?? '-',
                    $asset->processor ?? '-',
                    $asset->ram ?? '-',
                    $asset->storage_ssd ? "SSD: {$asset->storage_ssd}" : ($asset->storage_hdd ? "HDD: {$asset->storage_hdd}" : '-'),
                    $asset->operating_system ?? '-',
                    $asset->holder_name,
                    $asset->department ?? '-',
                    $asset->room ?? '-',
                    $asset->location?->name ?? 'HO',
                    $asset->status,
                    $asset->purchase_year ?? ($asset->purchase_date ? $asset->purchase_date->format('Y') : '-'),
                    $asset->purchase_cost ? number_format($asset->purchase_cost, 0, ',', '.') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadSticker103(\Illuminate\Http\Request $request, ?\App\Models\Asset $asset = null)
    {
        $slotNumber = (int) $request->input('slot', 1);
        if ($slotNumber < 1 || $slotNumber > 12) {
            $slotNumber = 1;
        }

        $mappedSlots = [];

        if ($asset) {
            $asset->load(['assetModel.category', 'location']);
            $mappedSlots[$slotNumber] = $asset;
        } elseif ($request->filled('asset_ids')) {
            $assetIds = is_array($request->asset_ids) ? $request->asset_ids : explode(',', $request->input('asset_ids'));
            $assets = \App\Models\Asset::with(['assetModel.category', 'location'])
                ->whereIn('id', $assetIds)
                ->get();

            $currentSlot = $slotNumber;
            foreach ($assets as $a) {
                $mappedSlots[$currentSlot] = $a;
                $currentSlot++;
            }
        }

        return view('pdf.asset-sticker-103', compact('mappedSlots', 'slotNumber'));
    }
}

