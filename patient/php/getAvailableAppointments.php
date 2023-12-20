<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include $_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php';

header('Content-Type: application/json');

$doctorId = isset($_GET['doctorId']) ? intval($_GET['doctorId']) : null;
$scheduleId = $_GET['day'] ?? '';
$appointmentDuration = 30; // Duration of each appointment in minutes

// Validate the inputs
if (!$doctorId || !$scheduleId) {
    echo json_encode(['error' => 'Doctor ID and schedule ID are required']);
    exit;
}

try {
    // Query to get the work intervals for the given schedule ID
    $scheduleStmt = $pdo->prepare("SELECT start_time, end_time 
                                   FROM work_intervals 
                                   WHERE schedule_id = ? AND type = 'work'");
    $scheduleStmt->execute([$scheduleId]);
    $schedule = $scheduleStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get already booked appointments for the selected doctor and schedule
    $bookedStmt = $pdo->prepare("SELECT appointment_time 
                                 FROM appointments 
                                 WHERE doctor_id = ? AND status = 'booked'");
    $bookedStmt->execute([$doctorId]);
    $bookedAppointments = $bookedStmt->fetchAll(PDO::FETCH_ASSOC);
    $bookedTimes = array_map(function ($appointment) {
        return (new DateTime($appointment['appointment_time']))->format('H:i');
    }, $bookedAppointments);

    $availableSlots = [];

    foreach ($schedule as $timeSlot) {
        $startTime = new DateTime($timeSlot['start_time']);
        $endTime = new DateTime($timeSlot['end_time']);

        // Generate slots within each interval
        while ($startTime < $endTime) {
            $slotTime = $startTime->format('H:i');
            if (!in_array($slotTime, $bookedTimes)) {
                $availableSlots[] = $slotTime;
            }

            $startTime->modify("+{$appointmentDuration} minutes");
        }
    }

    echo json_encode(['availableSlots' => $availableSlots]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
