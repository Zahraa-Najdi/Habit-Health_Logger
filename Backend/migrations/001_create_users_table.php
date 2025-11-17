<?php
include("../connection/connection.php");

$sql = "CREATE TABLE IF NOT EXISTS cars(
          id INT(11) AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255) NOT NULL,
          color VARCHAR(255) NOT NULL,
          year VARCHAR(255) NOT NULL)";

$query = $connection->prepare($sql);
$query->execute();

echo "Table(s) Created!";

?>