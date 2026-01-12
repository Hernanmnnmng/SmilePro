<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Management',
    'email' => 'management@smilepro.com',
    'password' => Hash::make('management123'),
    'role' => 'management',
]);

echo "\n=== Management User Created ===\n";
echo "Email: management@smilepro.com\n";
echo "Password: management123\n";
echo "Role: management\n";
echo "================================\n";
