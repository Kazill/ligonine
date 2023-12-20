<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include $_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php';
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/config.php');
try {
    $currentTime = new DateTime();
    $thresholdTime = $currentTime->modify('-30 minutes')->format('Y-m-d H:i:s');

    // Update only those appointments where both date and time are older than the threshold
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'completed' 
                           WHERE CONCAT(appointment_date, ' ', appointment_time) < ? 
                           AND status = 'booked'");
    $stmt->execute([$thresholdTime]);

    echo "Updated appointments successfully";
} catch (PDOException $e) {
    echo "Error updating appointments: " . $e->getMessage();
}
?>
