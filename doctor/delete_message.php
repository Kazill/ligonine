<?php
// Include database connection
require_once($_SERVER['DOCUMENT_ROOT'] .'/ligonine/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/ligonine/db/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['messageId'])) {
    $messageId = $_POST['messageId'];
    
    // Prepare the delete statement
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :messageId");
    $stmt->bindParam(':messageId', $messageId, PDO::PARAM_INT);
    
    // Execute the deletion
    if ($stmt->execute()) {
        echo "Message deleted successfully";
    } else {
        echo "Error deleting message";
    }
} else {
    // Not a POST request or messageId not set
    header('HTTP/1.1 400 Bad Request');
    echo "Invalid request";
}
?>
