<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include $_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php';
session_start();
header('Content-Type: application/json');

$doctorId = $_POST['doctor'] ?? '';
$scheduleId = $_POST['day'] ?? '';
$time = $_POST['appointment-time'] ?? '';
$patientId = $_SESSION['user_id'] ?? ''; // Assuming the patient's ID is stored in the session

// Validate inputs
if (!$doctorId || !$scheduleId || !$time || !$patientId) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

try {
    // Retrieve the day of the week from the schedule ID
    $dayStmt = $pdo->prepare("SELECT day_of_week FROM work_schedule WHERE id = ?");
    $dayStmt->execute([$scheduleId]);
    $dayResult = $dayStmt->fetch(PDO::FETCH_ASSOC);
    $dayOfWeek = $dayResult['day_of_week'] ?? '';

    if (!$dayOfWeek) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid schedule ID']);
        exit;
    }

    // Calculate the next date that corresponds to the selected day of the week
    $appointmentDate = new DateTime();
    while ($appointmentDate->format('l') !== $dayOfWeek) {
        $appointmentDate->modify('+1 day');
    }

    // Insert the appointment
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$patientId, $doctorId, $appointmentDate->format('Y-m-d'), $time, 'booked']);

    echo json_encode(['success' => true, 'message' => 'Appointment booked successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
