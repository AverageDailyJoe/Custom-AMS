<?php

namespace App\Console\Commands;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\Location;
use Illuminate\Console\Command;

class ImportAssetsCsv extends Command
{
    protected $signature = 'ams:import-csv {file : Path to CSV or TSV file}';
    protected $description = 'Import asset master data from CSV/TSV file into PostgreSQL';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $content = file_get_contents($filePath);
        $firstLine = strtok($content, "\r\n");

        // Auto-detect delimiter: tab, semicolon, or comma
        $delimiter = ',';
        if (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
            $delimiter = "\t";
        } elseif (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->error("Failed to open file: {$filePath}");
            return 1;
        }

        $rawHeader = fgetcsv($handle, 4096, $delimiter);
        if (!$rawHeader) {
            $this->error("Invalid or empty CSV file.");
            fclose($handle);
            return 1;
        }

        // Clean & normalize headers
        $header = array_map(function ($h) {
            return strtoupper(trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $h)));
        }, $rawHeader);

        $this->info("Detected Delimiter: [" . ($delimiter === "\t" ? 'TAB' : $delimiter) . "]");
        $this->info("Found CSV headers: " . implode(' | ', array_filter($header)));

        $count = 0;
        while (($row = fgetcsv($handle, 4096, $delimiter)) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            // Map row to header keys
            $data = [];
            foreach ($header as $index => $colName) {
                if (!empty($colName)) {
                    $data[$colName] = $row[$index] ?? null;
                }
            }

            $assetTag = trim($data['ID INVENTARIS'] ?? $data['ASSET TAG'] ?? '');
            if (empty($assetTag)) {
                continue;
            }

            // 1. Location
            $locationName = trim($data['LOKASI'] ?? 'FACTORY');
            if (empty($locationName) || $locationName === '-') $locationName = 'FACTORY';
            $location = Location::firstOrCreate(
                ['name' => $locationName],
                ['address' => $locationName]
            );

            // 2. Category
            $categoryName = trim($data['TYPE ASSET'] ?? 'PC');
            if (empty($categoryName) || $categoryName === '-') $categoryName = 'PC';
            $category = Category::firstOrCreate(
                ['name' => $categoryName],
                ['type' => 'asset']
            );

            // 3. Asset Model
            $modelName = trim($data['TYPE/MODEL UNIT'] ?? '');
            if (empty($modelName) || $modelName === '-') {
                $modelName = "{$categoryName} Standard Unit";
            }
            $assetModel = AssetModel::firstOrCreate(
                ['name' => $modelName, 'category_id' => $category->id],
                ['manufacturer' => 'Custom / General']
            );

            // 4. Users & Status
            $primaryUser = trim($data['PENGGUNA'] ?? $data['PENGGUNA 1'] ?? '');
            if ($primaryUser === '-') $primaryUser = null;

            $secondaryUser = trim($data['PENGGUNA 2'] ?? '');
            if ($secondaryUser === '-') $secondaryUser = null;

            $statusRaw = strtoupper(trim($data['STATUS'] ?? 'AKTIF'));
            $status = ($statusRaw === 'AKTIF' || !empty($primaryUser)) ? 'checked_out' : 'in_stock';

            // 5. Cost & Year
            $costRaw = $data['HARGA'] ?? '';
            $costClean = preg_replace('/[^0-9.]/', '', $costRaw);
            $cost = is_numeric($costClean) && strlen($costClean) > 0 ? floatval($costClean) : null;

            $yearRaw = $data['TAHUN PEMBELIAN'] ?? '';
            $yearClean = preg_replace('/[^0-9]/', '', $yearRaw);
            $year = is_numeric($yearClean) && strlen($yearClean) === 4 ? intval($yearClean) : null;

            // 6. Notes, Vendor & Warranty
            $notes = trim($data['KETERANGAN'] ?? '');
            $vendor = trim($data['VENDOR'] ?? '');
            if (!empty($vendor) && $vendor !== '-') {
                $notes = $notes ? "{$notes} | Vendor: {$vendor}" : "Vendor: {$vendor}";
            }

            $priority = trim($data['PRIORITAS'] ?? '');
            if (!empty($priority) && $priority !== '-') {
                $notes = $notes ? "{$notes} | Prioritas: {$priority}" : "Prioritas: {$priority}";
            }

            // 7. Update or Create Asset
            $asset = Asset::updateOrCreate(
                ['asset_tag' => $assetTag],
                [
                    'asset_model_id' => $assetModel->id,
                    'location_id' => $location->id,
                    'serial' => trim($data['SERIAL NUMBER'] ?? '') ?: null,
                    'room' => trim($data['RUANGAN'] ?? $data['RUANGAN / DETAIL LOKASI'] ?? '') ?: null,
                    'department' => trim($data['DEPARTEMEN'] ?? '') ?: null,
                    'primary_user' => $primaryUser,
                    'secondary_user' => $secondaryUser,
                    'processor' => trim($data['PROCESSOR'] ?? '') ?: null,
                    'ram' => trim($data['RAM'] ?? '') ?: null,
                    'storage_hdd' => trim($data['HDD'] ?? '') ?: null,
                    'storage_ssd' => trim($data['SSD'] ?? '') ?: null,
                    'vga_card' => trim($data['VGA CARD'] ?? '') ?: null,
                    'monitor_id' => trim($data['ID MONITOR'] ?? '') ?: null,
                    'monitor_spec' => trim($data['MONITOR'] ?? '') ?: null,
                    'status' => $status,
                    'condition' => strtolower(trim($data['KONDISI'] ?? 'bagus')) ?: 'bagus',
                    'purchase_year' => $year,
                    'purchase_cost' => $cost,
                    'warranty' => trim($data['GARANSI'] ?? '') ?: null,
                    'notes' => $notes ?: null,
                ]
            );

            // 8. Log active checkout history
            if ($status === 'checked_out' || !empty($primaryUser)) {
                if (!$asset->currentCheckout()->exists()) {
                    Checkout::create([
                        'asset_id' => $asset->id,
                        'primary_user' => $primaryUser ?: 'Pengguna Asset',
                        'secondary_user' => $secondaryUser,
                        'checked_out_by' => 1,
                        'checked_out_at' => now(),
                        'checkout_notes' => 'Import dari master data Excel/CSV',
                    ]);
                }
            }

            $count++;
        }

        fclose($handle);

        $this->info("Successfully imported/updated {$count} assets in PostgreSQL!");
        return 0;
    }
}
