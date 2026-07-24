<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::firstOrCreate(
    ['email' => 'admin@ams.test'],
    [
        'name' => 'Admin',
        'email' => 'admin@ams.test',
        'password' => Hash::make('password'),
    ]
);

echo "User ID: " . $user->id . "\n";
echo "Name: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";

// Assign filament admin role if applicable (Filament v3.x uses native Gate)
echo "Admin user created/verified successfully!\n";
