<?php
/**
 * Student Name: Mikey
 * Project: CraftCanvas Studios
 * Configuration File: InfinityFree Production Environment
 */

// Ensure session is started only once to prevent errors
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- INFINITYFREE DATABASE CREDENTIALS ---
// Go to your Control Panel -> MySQL Databases to find these details.

define('DB_HOST', 'sql101.infinityfree.com'); // Your InfinityFree MySQL Host Name
define('DB_PORT', '3306');                    // Usually stays 3306
define('DB_USER', 'if0_39896485');            // Your InfinityFree MySQL User Name
define('DB_PASS', 'Xjn016027');               // Your InfinityFree vPanel Password

// --- DATABASE NAMES ---

// 1. WordPress Database (For Login/FluentCRM)
// UPDATED: Set to 'wp900' so we can find the registered users.
define('DB_NAME_WP', 'if0_39896485_wp900');    

// 2. E-Commerce Database (For Products/Orders/Cart)
// If your products are ALSO in the 'wp900' database, change this to 'if0_39896485_wp900' as well.
define('ECOM_DB_NAME', 'if0_39896485_513final'); 


// --- CONNECTION FUNCTIONS ---

// 1. Connection to WordPress Database (Used by customer_login.php)
function getWPConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME_WP, DB_PORT);
    if ($conn->connect_error) {
        die("WP Database Connection Failed: " . $conn->connect_error);
    }
    return $conn;
}

// 2. Connection to E-Commerce Database (Used by products.php, cart.php)
function getEcomConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, ECOM_DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        die("E-Commerce Database Connection Failed: " . $conn->connect_error);
    }
    return $conn;
}

// 3. Helper for generic connection (defaults to E-Commerce DB)
function getConnection() {
    return getEcomConnection();
}
?>