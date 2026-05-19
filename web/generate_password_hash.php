<?php
// Script untuk generate Bcrypt hash password

// Password yang ingin di-hash
$password = 'password123';

// Generate Bcrypt hash
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

echo "=== PASSWORD HASH GENERATOR ===\n";
echo "Password: " . $password . "\n";
echo "Bcrypt Hash:\n";
echo $hash . "\n";
echo "\n=== UNTUK UPDATE DATABASE ===\n";
echo "SQL Command:\n";
echo "UPDATE users SET password = '" . $hash . "' WHERE user_id = 1;\n";
echo "\nAtau gunakan query builder di tinker:\n";
echo "DB::table('users')->where('user_id', 1)->update(['password' => '" . $hash . "']);\n";
?>
