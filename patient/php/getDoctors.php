<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php';
header('Content-Type: application/json');

$specialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';
try {
    if (!empty($specialty)) {
        // Fetch doctors for a specific specialty
        $stmt = $pdo->prepare("SELECT id, `Name`, `Surname` FROM user WHERE `User type` = 'doctor' AND `Specialty` = ?");
        $stmt->execute([$specialty]);
    } else {
        // Fetch all doctors if no specialty is specified
        $stmt = $pdo->prepare("SELECT id, `Name`, `Surname` FROM user WHERE `User type` = 'doctor'");
        $stmt->execute();
    }
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['doctors' => $doctors]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
