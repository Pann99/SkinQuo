<?php
/**
 * Quick script to generate Bcrypt password hash
 * Run: php update_admin_password.php
 */

// Simple Bcrypt hash generation (PHP built-in, no Laravel needed)
$password = 'password123';
$hash = password_hash($password, PASSWORD_BCRYPT);

?>
Password: <?php echo $password; ?>

Hash: <?php echo $hash; ?>
