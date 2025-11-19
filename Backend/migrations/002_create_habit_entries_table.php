<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS habit_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_id INT NOT NULL,
    user_id INT NOT NULL,
    habit_name VARCHAR(100) NOT NULL,
    value FLOAT DEFAULT NULL,
    unit VARCHAR(50) DEFAULT NULL,
    is_predefined BOOLEAN DEFAULT FALSE
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    rules TEXT DEFAULT NULL, --'rules' column

    FOREIGN KEY (entry_id) REFERENCES entries(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
)";

if (mysqli_query($connection, $sql)) {
    echo 'Habit entries table created successfully!';
} else {
    echo 'Error creating habit entries table: ' . mysqli_error($connection);
}
?>
