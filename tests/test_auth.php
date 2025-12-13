<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test authentication
echo "Testing authentication...\n";

$user = App\Models\User::where('email', 'admin@kopmerahputih.com')->first();
if ($user) {
    echo "User found: " . $user->name . "\n";
    echo "Has super-admin role: " . ($user->hasRole('super-admin') ? 'Yes' : 'No') . "\n";
    
    // Test password
    if (Illuminate\Support\Facades\Hash::check('password', $user->password)) {
        echo "Password verification: Valid\n";
        
        // Test Auth::attempt
        if (Illuminate\Support\Facades\Auth::attempt(['email' => 'admin@kopmerahputih.com', 'password' => 'password'])) {
            echo "Auth::attempt: Success\n";
            echo "Authenticated user: " . Illuminate\Support\Facades\Auth::user()->name . "\n";
        } else {
            echo "Auth::attempt: Failed\n";
        }
    } else {
        echo "Password verification: Invalid\n";
    }
} else {
    echo "User not found\n";
}

echo "Test completed.\n";
