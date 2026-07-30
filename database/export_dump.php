<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (!file_exists('database/dumps')) {
    mkdir('database/dumps', 0777, true);
}

$tables = \Illuminate\Support\Facades\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
$sql = "-- AMS PostgreSQL Backup Dump\n-- Exported at: " . date('Y-m-d H:i:s') . "\n\n";

foreach ($tables as $t) {
    $tableName = $t->table_name;
    if ($tableName === 'migrations') continue;
    $rows = \Illuminate\Support\Facades\DB::table($tableName)->get();
    if ($rows->isEmpty()) continue;
    
    $sql .= "-- Data for table {$tableName}\n";
    foreach ($rows as $row) {
        $cols = array_keys((array)$row);
        $vals = array_map(function($v) {
            if ($v === null) return 'NULL';
            if (is_bool($v)) return $v ? 'true' : 'false';
            if (is_array($v) || is_object($v)) return "'" . addslashes(json_encode($v)) . "'";
            return "'" . addslashes((string)$v) . "'";
        }, array_values((array)$row));
        
        $sql .= "INSERT INTO {$tableName} (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n";
    }
    $sql .= "\n";
}

file_put_contents('database/dumps/ams_backup_latest.sql', $sql);
echo "DB_EXPORT_SUCCESS: database/dumps/ams_backup_latest.sql (" . strlen($sql) . " bytes)\n";
