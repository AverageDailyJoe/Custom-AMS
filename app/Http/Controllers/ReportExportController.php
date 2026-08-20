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
            
            $dataRows = $query->get()->map(fn($row) => [
                $row->request_number, 
                $row->request_date ? $row->request_date->format('Y-m-d') : '-', 
                $row->requester_name, 
                $row->requester_department, 
                $row->item_type, 
                $row->quantity, 
                $row->estimated_cost, 
                $row->status
            ])->toArray();
        }
        elseif ($type === 'berita_acara_recap') {
            $columns = ['No BA', 'Tanggal', 'Penerima', 'Departemen', 'Aset Terkait', 'Status'];
            $query = BeritaAcara::query()->orderBy('letter_date', 'desc');
            if ($start) $query->whereDate('letter_date', '>=', $start);
            if ($end) $query->whereDate('letter_date', '<=', $end);
            
            $dataRows = $query->get()->map(fn($row) => [
                $row->letter_number, 
                $row->letter_date ? \Carbon\Carbon::parse($row->letter_date)->format('Y-m-d') : '-', 
                $row->party2_name, 
                $row->party2_department, 
                $row->asset_tag ?? '-', 
                $row->status ?? '-'
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
