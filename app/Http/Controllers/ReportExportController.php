<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\PengajuanAset;
use App\Models\BeritaAcara;
use App\Models\DisposeAset;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    public function download(Request $request)
    {
        $type = $request->query('type');
        $start = $request->query('start_date');
        $end = $request->query('end_date');

        $fileName = 'Laporan_' . $type . '_' . date('Ymd_His') . '.csv';
        $columns = [];
        $dataRows = [];

        if ($type === 'asset_per_dept') {
            $columns = ['Departemen', 'Total Aset', 'Total Nilai (Rp)'];
            $dataRows = Asset::select('department', DB::raw('COUNT(*) as total_assets'), DB::raw('SUM(purchase_cost) as total_value'))
                ->whereNotNull('department')
                ->groupBy('department')
                ->orderByDesc('total_assets')
                ->get()
                ->map(fn($row) => [$row->department, $row->total_assets, $row->total_value])
                ->toArray();
        } 
        elseif ($type === 'asset_overall') {
            $columns = ['Status', 'Kondisi', 'Total Aset'];
            $dataRows = Asset::select('status', 'condition', DB::raw('COUNT(*) as total_assets'))
                ->groupBy('status', 'condition')
                ->orderBy('status')
                ->get()
                ->map(function($row) {
                    return [$row->status, $row->condition, $row->total_assets];
                })
                ->toArray();
        }
        elseif ($type === 'pengajuan_per_dept') {
            $columns = ['Departemen', 'Nama Pemohon', 'Total Pengajuan', 'Total Estimasi Biaya (Rp)'];
            $dataRows = PengajuanAset::select('requester_department', 'requester_name', DB::raw('COUNT(*) as total'), DB::raw('SUM(estimated_cost) as total_cost'))
                ->groupBy('requester_department', 'requester_name')
                ->orderByDesc('total')
                ->get()
                ->map(fn($row) => [$row->requester_department, $row->requester_name, $row->total, $row->total_cost])
                ->toArray();
        }
        elseif ($type === 'pengajuan_recap') {
            $columns = ['No Pengajuan', 'Tanggal', 'Pemohon', 'Departemen', 'Item', 'Qty', 'Estimasi (Rp)', 'Status'];
            $query = PengajuanAset::query()->orderBy('request_date', 'desc');
            if ($start) $query->whereDate('request_date', '>=', $start);
            if ($end) $query->whereDate('request_date', '<=', $end);
            
            $dataRows = $query->get()->map(function($row) {
                return [
                    $row->request_number,
                    $row->request_date?->format('d/m/Y'),
                    $row->requester_name,
                    $row->requester_department,
                    $row->item_type,
                    $row->quantity,
                    $row->estimated_cost,
                    $row->status,
                ];
            })->toArray();
        }
        elseif ($type === 'asset_tco') {
            $columns = ['Asset Tag', 'Nama Aset', 'Departemen', 'Tanggal Pengajuan', 'Nama Pemohon', 'Target Asset', 'Item', 'Qty', 'Biaya Pembelian (Rp)', 'Total Biaya Maintenance (Rp)', 'Total TCO (Rp)'];
            $assets = Asset::with(['assetModel', 'pengajuanAsets' => function($q) { $q->oldest('request_date'); }])
                           ->withSum('maintenanceLogs as total_maintenance_cost', 'cost')
                           ->get();
                           
            $dataRows = $assets->map(function($asset) {
                $pengajuan = $asset->pengajuanAsets->first();
                $purchaseCost = $asset->purchase_cost ?? 0;
                $maintenanceCost = $asset->total_maintenance_cost ?? 0;
                $tco = $purchaseCost + $maintenanceCost;
                
                return [
                    $asset->asset_tag,
                    $asset->assetModel?->full_name ?? $asset->asset_tag,
                    $asset->department,
                    $pengajuan?->request_date?->format('d/m/Y') ?? '-',
                    $pengajuan?->requester_name ?? '-',
                    $asset->asset_tag, // Since Target Asset is the asset itself
                    $pengajuan?->item_type ?? $pengajuan?->title ?? '-',
                    $pengajuan?->quantity ?? '-',
                    $purchaseCost,
                    $maintenanceCost,
                    $tco,
                ];
            })->toArray();
        }
        elseif ($type === 'ticket_per_asset') {
            $columns = ['Asset Tag', 'Nama Aset', 'Departemen', 'Jumlah Tiket/Perbaikan', 'Total Biaya Maintenance (Rp)'];

            // Eager load assetModel for display name
            $query = Asset::withCount(['tickets' => function($q) use ($start, $end) {
                                if ($start) $q->whereDate('created_at', '>=', $start);
                                if ($end) $q->whereDate('created_at', '<=', $end);
                            }])
                            ->withSum(['maintenanceLogs as total_maintenance_cost' => function($q) use ($start, $end) {
                                if ($start) $q->whereDate('performed_at', '>=', $start);
                                if ($end) $q->whereDate('performed_at', '<=', $end);
                            }], 'cost')
                            ->whereHas('tickets', function($q) use ($start, $end) {
                                if ($start) $q->whereDate('created_at', '>=', $start);
                                if ($end) $q->whereDate('created_at', '<=', $end);
                            })
                            ->orderByDesc('tickets_count');
                            
            $dataRows = $query->with('assetModel')->get()->map(function($asset) {
                return [
                    $asset->asset_tag,
                    $asset->assetModel?->full_name ?? $asset->asset_tag,
                    $asset->department,
                    $asset->tickets_count,
                    $asset->total_maintenance_cost ?? 0,
                ];
            })->toArray();
        }
        elseif ($type === 'ticket_per_dept') {
            $columns = ['Departemen', 'Jumlah Tiket', 'Total Biaya Maintenance (Rp)'];
            
            $ticketQuery = \App\Models\Ticket::select('reporter_department', DB::raw('COUNT(*) as total_tickets'))
                ->whereNotNull('reporter_department')
                ->where('reporter_department', '!=', '');
                
            if ($start) $ticketQuery->whereDate('created_at', '>=', $start);
            if ($end) $ticketQuery->whereDate('created_at', '<=', $end);
            
            $ticketStats = $ticketQuery->groupBy('reporter_department')->get()->keyBy('reporter_department');
            
            $costQuery = \App\Models\AssetMaintenanceLog::select('tickets.reporter_department', DB::raw('SUM(asset_maintenance_logs.cost) as total_cost'))
                ->join('tickets', 'asset_maintenance_logs.ticket_id', '=', 'tickets.id')
                ->whereNotNull('tickets.reporter_department')
                ->where('tickets.reporter_department', '!=', '');
                
            if ($start) $costQuery->whereDate('asset_maintenance_logs.performed_at', '>=', $start);
            if ($end) $costQuery->whereDate('asset_maintenance_logs.performed_at', '<=', $end);
            
            $costStats = $costQuery->groupBy('tickets.reporter_department')->get()->keyBy('reporter_department');
            
            $departments = $ticketStats->keys()->merge($costStats->keys())->unique();
            
            foreach ($departments as $dept) {
                $tickets = $ticketStats->has($dept) ? $ticketStats[$dept]->total_tickets : 0;
                $cost = $costStats->has($dept) ? $costStats[$dept]->total_cost : 0;
                $dataRows[] = [
                    $dept,
                    $tickets,
                    $cost,
                ];
            }
            
            // Sort by total tickets desc
            usort($dataRows, function($a, $b) {
                return $b[1] <=> $a[1];
            });
        }
        elseif ($type === 'berita_acara_recap') {
            $columns = ['No BA', 'Tanggal', 'Kategori', 'Penerima', 'Departemen', 'Aset Terkait'];
            $query = BeritaAcara::query()->orderBy('letter_date', 'desc');
            if ($start) $query->whereDate('letter_date', '>=', $start);
            if ($end) $query->whereDate('letter_date', '<=', $end);
            
            $dataRows = $query->get()->map(fn($row) => [
                $row->letter_number, 
                $row->letter_date ? \Carbon\Carbon::parse($row->letter_date)->format('d/m/Y') : '-', 
                $row->category ?? '-',
                $row->party2_name, 
                $row->party2_department, 
                $row->asset_tag ?? '-',
            ])->toArray();
        }
        elseif ($type === 'disposal_recap') {
            $columns = ['No Disposal', 'Tanggal', 'Aset', 'Alasan', 'Nilai Penjualan (Rp)'];
            $query = DisposeAset::query()->orderBy('disposal_date', 'desc');
            if ($start) $query->whereDate('disposal_date', '>=', $start);
            if ($end) $query->whereDate('disposal_date', '<=', $end);
            
            $dataRows = $query->get()->map(fn($row) => [
                $row->disposal_number, 
                $row->disposal_date ? \Carbon\Carbon::parse($row->disposal_date)->format('Y-m-d') : '-', 
                $row->asset_tag ?? '-', 
                $row->disposal_reason, 
                $row->selling_price
            ])->toArray();
        }

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($dataRows, $columns) {
            $file = fopen('php://output', 'w');
            // Add BOM for Excel UTF-8 compatibility
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, $columns);
            foreach ($dataRows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
