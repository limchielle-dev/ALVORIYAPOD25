<?php
$host = 'localhost';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS alvoriya";
if ($conn->query($sql) === TRUE) {
    echo "Database 'alvoriya' created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db('alvoriya');

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS registrations (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    major VARCHAR(100) NOT NULL,
    batch VARCHAR(10) NOT NULL,
    motivation TEXT NOT NULL,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ";

if ($conn->query($sql) === TRUE) {
    echo "Table 'registrations' created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Add columns if they don't exist (for existing tables)
$alter_sql = [
    "ALTER TABLE registrations ADD COLUMN IF NOT EXISTS major VARCHAR(100) NOT NULL AFTER email",
    "ALTER TABLE registrations ADD COLUMN IF NOT EXISTS batch VARCHAR(10) NOT NULL AFTER major"
];

foreach ($alter_sql as $query) {
    if ($conn->query($query) === TRUE) {
        // success
    } else {
        echo "Error altering table: " . $conn->error . "<br>";
    }
}

$conn->close();
?>
