<?php

// createTable.php - Drops and recreates tblUser, then loads data from userData.txt

include('DBConn.php');

// Disable foreign key checks to allow dropping tables
$conn->query("SET FOREIGN_KEY_CHECKS=0");

// Drop tblUser if it already exists
$dropTable = "DROP TABLE IF EXISTS tblUser";
if ($conn->query($dropTable) === TRUE) {
    echo "Old tblUser dropped successfully.<br>";
} else {
    echo "Error dropping table: " . $conn->error . "<br>";
}

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS=1");

// Create tblUser fresh
$createTable = "CREATE TABLE IF NOT EXISTS tblUser (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('pending','verified') DEFAULT 'pending',
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createTable) === TRUE) {
    echo "tblUser created successfully.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Load data from userData.txt
$file = fopen("database/userData.txt", "r");

if ($file) {
    while (($line = fgets($file)) !== false) {
        $line = trim($line);
        $data = explode("|", $line);

        if (count($data) == 5) {
            $firstName = $conn->real_escape_string($data[0]);
            $lastName  = $conn->real_escape_string($data[1]);
            $email     = $conn->real_escape_string($data[2]);
            $username  = $conn->real_escape_string($data[3]);
            $password  = $conn->real_escape_string($data[4]);

            $insert = "INSERT INTO tblUser 
                       (firstName, lastName, email, username, password, status) 
                       VALUES 
                       ('$firstName','$lastName','$email','$username','$password','verified')";

            if ($conn->query($insert) === TRUE) {
                echo "User $firstName $lastName inserted successfully.<br>";
            } else {
                echo "Error inserting $firstName: " . $conn->error . "<br>";
            }
        }
    }
    fclose($file);
    echo "<br>All users loaded successfully!";
} else {
    echo "Error opening userData.txt";
}

$conn->close();
?>