<?php
// Include your database connection and necessary files
require_once($_SERVER['DOCUMENT_ROOT'] .'/ligonine/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/ligonine/db/db_connection.php');

// Assuming sendMessage function is defined elsewhere
// You may need to include the file where it is defined

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = $_POST['appointmentId'];
    $messageContent = $_POST['messageContent'];

    // Call the sendMessage function
    sendMessage($appointmentId, $messageContent);
}
function sendMessage($appointmentId, $messageContent)
{
    global $pdo; // Use the global $pdo variable established from the db_connection.php

    $stmt = $pdo->prepare("INSERT INTO messages (appointment_id, message_content) VALUES (?, ?)");
    $stmt->bindParam(1, $appointmentId, PDO::PARAM_INT);
    $stmt->bindParam(2, $messageContent, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $stmt->errorInfo()[2]; // Error information from PDO
    }

    // No need to close $pdo as it will be closed automatically at the end of the script
}
?>