<?php
require_once 'config.php';
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM registrations");
    $count = $stmt->fetchColumn();
    echo "Total registrations: " . $count . "\n";
    
    $stmt = $pdo->query("SELECT * FROM registrations ORDER BY id DESC LIMIT 1");
    $row = $stmt->fetch();
    if ($row) {
        echo "Latest registration: " . $row['full_name'] . " (" . $row['email'] . ")\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
