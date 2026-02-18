<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Simulate a request
$request = \Illuminate\Http\Request::create(
    '/achats/bon-de-commande/1/receive',
    'POST',
    [
        'received' => [
            '1' => '1.00',
            '2' => '2.00',
        ]
    ],
    [],
    [],
    ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest']
);

// Test validation
$validator = \Illuminate\Support\Facades\Validator::make(
    $request->input(),
    [
        'received' => 'required|array',
        'received.*' => 'nullable|numeric|min:0',
    ]
);

if ($validator->fails()) {
    echo "Validation failed:\n";
    print_r($validator->errors()->toArray());
} else {
    echo "Validation passed!\n";
    echo "Data: ";
    print_r($request->input('received'));
}
?>
