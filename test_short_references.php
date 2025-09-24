<?php

echo "=== Testing Shorter Reference Number Generation ===\n\n";

// Test the new shorter reference generation functions
function generateFspReference(): string
{
    // Generate a shorter reference: FSP + 8 digit unique ID = 11 characters total
    return 'FSP' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
}

function generatePaymentReference(): string
{
    // Generate a shorter reference: PAY + 8 digit unique ID = 11 characters total
    return 'PAY' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
}

// Test generation multiple times
echo "Testing FSP Reference Generation (11 chars):\n";
echo str_repeat("-", 50) . "\n";
for ($i = 0; $i < 10; $i++) {
    $ref = generateFspReference();
    $len = strlen($ref);
    $status = $len <= 20 ? "✓ OK" : "✗ TOO LONG";
    echo sprintf("%-20s Length: %2d %s\n", $ref, $len, $status);
}

echo "\nTesting Payment Reference Generation (11 chars):\n";
echo str_repeat("-", 50) . "\n";
for ($i = 0; $i < 10; $i++) {
    $ref = generatePaymentReference();
    $len = strlen($ref);
    $status = $len <= 20 ? "✓ OK" : "✗ TOO LONG";
    echo sprintf("%-20s Length: %2d %s\n", $ref, $len, $status);
}

echo "\n=== Reference Format Summary ===\n";
echo "FSP Reference: Exactly 11 chars (FSP + 8 digits) ✓\n";
echo "Payment Reference: Exactly 11 chars (PAY + 8 digits) ✓\n";
echo "Both fit comfortably within a 20 character column limit.\n";
echo "\nExamples:\n";
echo "  FSP Reference: " . generateFspReference() . "\n";
echo "  Payment Reference: " . generatePaymentReference() . "\n";