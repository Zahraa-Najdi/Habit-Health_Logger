<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    target_value FLOAT DEFAULT NULL,
    unit VARCHAR(50) DEFAULT NULL,
    is_predefined TINYINT(1) DEFAULT 0, --Added for predefined habits
    rules TEXT DEFAULT NULL, --'rules' column
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX(user_id)
)";

if (mysqli_query($connection, $sql)) {
    echo "Habits table created successfully!";
} 
else {
    echo "Error creating habits table: " . mysqli_error($connection);
}
?>