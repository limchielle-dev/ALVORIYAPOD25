<?php
/**
 * Registration Handler for ALVORIYA Student Club
 * Processes the form submission and saves to MySQL database via PDO.
 */

// Include the database configuration
require_once 'config.php';

// Start session to pass data to the success page
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $student_id = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_SPECIAL_CHARS);
    $major = filter_input(INPUT_POST, 'major', FILTER_SANITIZE_SPECIAL_CHARS);
    $batch = filter_input(INPUT_POST, 'batch', FILTER_SANITIZE_SPECIAL_CHARS);
    $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $motivation = filter_input(INPUT_POST, 'motivation', FILTER_SANITIZE_SPECIAL_CHARS);

    // Basic Validation
    $errors = [];
    if (empty($full_name)) $errors[] = "Full Name is required.";
    if (empty($student_id)) $errors[] = "Student ID is required.";
    if (empty($major)) $errors[] = "Major is required.";
    if (empty($batch)) $errors[] = "Batch is required.";
    if (empty($phone_number)) $errors[] = "Phone Number is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email address is required.";
    if (empty($motivation)) $errors[] = "Motivation/Reason is required.";

    if (!empty($errors)) {
        // In a real app, you might want to redirect back with error messages
        die("Validation failed: " . implode("<br>", $errors));
    }

    try {
        // Prepare the SQL statement to avoid SQL injection
        $sql = "INSERT INTO registrations (full_name, student_id, major, batch, phone_number, email, motivation) 
                VALUES (:full_name, :student_id, :major, :batch, :phone_number, :email, :motivation)";
        
        $stmt = $pdo->prepare($sql);
        
        // Execute the statement with binded parameters
        $stmt->execute([
            ':full_name' => $full_name,
            ':student_id' => $student_id,
            ':major' => $major,
            ':batch' => $batch,
            ':phone_number' => $phone_number,
            ':email' => $email,
            ':motivation' => $motivation
        ]);

        // Store data in session for display on success page
        $_SESSION['registration_data'] = [
            'full_name' => $full_name,
            'student_id' => $student_id,
            'email' => $email,
            'major' => $major,
            'batch' => $batch
        ];

        // Success! Redirect to the success page as requested by the user
        header("Location: success.php");
        exit();

    } catch (PDOException $e) {
        // Log the error and show a user-friendly message
        error_log("Registration Error: " . $e->getMessage());
        die("An error occurred while processing your registration. Please try again later. (Error: " . $e->getMessage() . ")");
    }
} else {
    // If someone tries to access this page directly, send them back to the form
    header("Location: registration.html");
    exit();
}
?>