<?php
session_start();

// Redirect back to home if no registration data is found in session
if (!isset($_SESSION['registration_data'])) {
    header("Location: index.html");
    exit();
}

$data = $_SESSION['registration_data'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful | ALVORIYA</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Redirect back to home after 10 seconds to allow user to see their data -->
    <meta http-equiv="refresh" content="10;url=index.html">
    <style>
        .details-panel {
            text-align: left;
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .detail-row {
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
        }
        .detail-label {
            color: var(--text-muted);
            font-weight: 500;
        }
        .detail-value {
            color: var(--text-color);
            font-weight: 600;
        }
    </style>
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; text-align: center; padding: 20px;">
    <div class="glass-panel fade-in-up" style="max-width: 500px; width: 100%;">
        <div style="font-size: 4rem; color: #4CAF50; margin-bottom: 1rem;">
            <ion-icon name="checkmark-circle-outline"></ion-icon>
        </div>
        <h1 class="hero-title" style="font-size: 2.5rem; margin-bottom: 1rem;">Registration <span class="gradient-text">Successful!</span></h1>
        <p class="hero-subtitle" style="margin-bottom: 1rem;">Welcome to the club, <strong><?php echo htmlspecialchars($data['full_name']); ?></strong>!</p>
        
        <div class="details-panel">
            <h4 style="margin-bottom: 1rem; color: var(--accent-color);">Your Registered Details:</h4>
            <div class="detail-row">
                <span class="detail-label">Student ID:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['student_id']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Major:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['major']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Batch:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['batch']); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><?php echo htmlspecialchars($data['email']); ?></span>
            </div>
        </div>

        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">We have received your application and will contact you via email soon.</p>
        <p style="color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1.5rem;">Redirecting to Home in 10 seconds...</p>
        <a href="index.html" class="btn btn-primary" style="display: inline-block;">Return to Home Now</a>
    </div>

    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
<?php 
// Clean up the session data so it doesn't show again on refresh
unset($_SESSION['registration_data']);
?>
