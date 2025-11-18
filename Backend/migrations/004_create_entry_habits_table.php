<?php

include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS entry_habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entry_id INT NOT NULL,
    habit_id INT NOT NULL,
    value FLOAT DEFAULT NULL,
    rules TEXT DEFAULT NULL, --'rules' column
    FOREIGN KEY (entry_id) REFERENCES entries(id) ON DELETE CASCADE,
    FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE,
    UNIQUE KEY unique_entry_habit (entry_id, habit_id)
)";

if (mysqli_query($connection, $sql)) {
    echo 'Entry habits table created successfully!';
} else {
    echo 'Error creating entry habits table: ' . mysqli_error($connection);
}
?>