<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    rules TEXT DEFAULT NULL, --'rules' column
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($connection, $sql)) {
    echo 'Users table created successfully!';
} else {
    echo 'Error creating users table: ' . mysqli_error($connection);
}
?>