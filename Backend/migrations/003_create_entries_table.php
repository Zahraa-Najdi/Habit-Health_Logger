<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    entry_date DATE NOT NULL,
    free_text TEXT DEFAULT NULL, 
    parsed_json JSON DEFAULT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    rules TEXT DEFAULT NULL, /* 'rules' column */

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_date (user_id, entry_date)
)";

if (mysqli_query($connection, $sql)) {
    echo "Entries table created successfully!";
} else {
    echo "Error creating entries table: " . mysqli_error($connection);
}
?>
