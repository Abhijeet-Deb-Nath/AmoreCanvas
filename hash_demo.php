<?php

// Demonstrate how bcrypt hashing works with salt

echo "=== BCRYPT HASH DEMONSTRATION ===\n\n";

// Same password, hashed twice
$password = "MySecret123";

$hash1 = password_hash($password, PASSWORD_BCRYPT);
$hash2 = password_hash($password, PASSWORD_BCRYPT);

echo "Password: $password\n\n";

echo "Hash 1: $hash1\n";
echo "Hash 2: $hash2\n\n";

echo "Are they the same? " . ($hash1 === $hash2 ? "YES" : "NO") . "\n\n";

// Break down hash 1
echo "=== BREAKING DOWN HASH 1 ===\n";
echo "Full hash: $hash1\n";
echo "Algorithm: " . substr($hash1, 0, 4) . "\n";
echo "Cost:      " . substr($hash1, 4, 2) . "\n";
echo "Salt:      " . substr($hash1, 7, 22) . " (22 characters)\n";
echo "Hash:      " . substr($hash1, 29) . " (31 characters)\n\n";

// Break down hash 2
echo "=== BREAKING DOWN HASH 2 ===\n";
echo "Full hash: $hash2\n";
echo "Algorithm: " . substr($hash2, 0, 4) . "\n";
echo "Cost:      " . substr($hash2, 4, 2) . "\n";
echo "Salt:      " . substr($hash2, 7, 22) . " (22 characters) <- DIFFERENT!\n";
echo "Hash:      " . substr($hash2, 29) . " (31 characters) <- DIFFERENT!\n\n";

// But both verify correctly!
echo "=== VERIFICATION ===\n";
echo "Hash 1 verifies: " . (password_verify($password, $hash1) ? "✓ YES" : "✗ NO") . "\n";
echo "Hash 2 verifies: " . (password_verify($password, $hash2) ? "✓ YES" : "✗ NO") . "\n\n";

echo "=== HOW IT WORKS ===\n";
echo "When verifying:\n";
echo "1. Extract the salt from the stored hash\n";
echo "2. Use that SAME salt to hash the input password\n";
echo "3. Compare the results\n\n";

// Demonstrate wrong password
echo "=== WRONG PASSWORD TEST ===\n";
$wrongPassword = "WrongPassword";
echo "Trying password: $wrongPassword\n";
echo "Hash 1 verifies: " . (password_verify($wrongPassword, $hash1) ? "✓ YES" : "✗ NO") . "\n";
echo "Hash 2 verifies: " . (password_verify($wrongPassword, $hash2) ? "✓ YES" : "✗ NO") . "\n";
