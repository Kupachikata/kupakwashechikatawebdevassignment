<?php
// Hash the password
$hashedPassword = password_hash('1234', PASSWORD_DEFAULT);
echo $hashedPassword; // Copy this hashed password for use in your SQL command
?>$2y$10$85XCLdzNMkAKDY3Z/2KMmOSGXgrEoo/KH/1ZU19NHfWLtmnYBzr3G