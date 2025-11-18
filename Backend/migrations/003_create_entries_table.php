<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    entry_date DATE NOT NULL,
    free_text TEXT DEFAULT NULL, --Added for free-text input
    parsed_json JSON DEFAULT NULL, --Added for AI-parsed data
    rules TEXT DEFAULT NULL, --'rules' column
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX(user_id),
    UNIQUE KEY unique_user_date (user_id, entry_date)
)";

if (mysqli_query($connection, $sql)) {
    echo "Entries table created successfully!";
} else {
    echo "Error creating entries table: " . mysqli_error($connection);
}
?>