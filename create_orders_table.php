<?php
// Student Name: Mikey
require_once 'config.php';

// IMPORTANT: On InfinityFree, the database must be created in the Control Panel first.
// This script ONLY creates the table inside that existing database.

// Connect directly to the specific E-Commerce database defined in config.php
$ecom_conn = getEcomConnection(); 

// Create Orders Table
$sql_table = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    customer_name VARCHAR(100),
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2),
    order_status VARCHAR(20) DEFAULT 'pending',
    items JSON
)";

if ($ecom_conn->query($sql_table) === TRUE) {
    echo "<h3>Success!</h3>";
    echo "Table 'orders' has been successfully created inside database: " . ECOM_DB_NAME;
} else {
    echo "<h3>Error</h3>";
    echo "Error creating table: " . $ecom_conn->error;
}
?>