<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Location;
use App\Models\Category;
use App\Models\AssetModel;

// Seed Locations
$factory = Location::firstOrCreate(['name' => 'FACTORY'], ['address' => 'Factory Area']);
$headOffice = Location::firstOrCreate(['name' => 'HEAD OFFICE'], ['address' => 'Head Office Building']);

// Seed Categories
$pcCategory = Category::firstOrCreate(['name' => 'PC'], ['type' => 'asset']);
$laptopCategory = Category::firstOrCreate(['name' => 'Laptop'], ['type' => 'asset']);
$monitorCategory = Category::firstOrCreate(['name' => 'Monitor'], ['type' => 'asset']);
$printerCategory = Category::firstOrCreate(['name' => 'Printer'], ['type' => 'asset']);

// Seed Asset Models
AssetModel::firstOrCreate(
    ['name' => 'Custom PC Unit', 'category_id' => $pcCategory->id],
    ['manufacturer' => 'Custom', 'model_number' => 'PC-DESKTOP']
);
AssetModel::firstOrCreate(
    ['name' => 'Monitor Standard', 'category_id' => $monitorCategory->id],
    ['manufacturer' => 'LG / ViewSonic', 'model_number' => 'MON-19']
);
AssetModel::firstOrCreate(
    ['name' => 'Latitude 5420', 'category_id' => $laptopCategory->id],
    ['manufacturer' => 'Dell', 'model_number' => 'LAT-5420']
);

echo "Initial master data (Locations, Categories, Models) seeded successfully!\n";
