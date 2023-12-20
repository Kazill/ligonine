<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include $_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php';
header('Content-Type: application/json');

$doctorId = $_GET['doctorId'] ?? '';
$appointmentDuration = 30; // Duration of each appointment in minutes

// Query to get the work schedule and intervals
$stmt = $pdo->prepare("SELECT ws.id as schedule_id, ws.day_of_week, wi.start_time, wi.end_time 
                       FROM work_schedule ws 
                       JOIN work_intervals wi ON ws.id = wi.schedule_id 
                       WHERE ws.doctor_id = ? AND wi.type = 'work'");
$stmt->execute([$doctorId]);
$schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query to get already booked appointments
$bookedStmt = $pdo->prepare("SELECT appointment_date, appointment_time 
                             FROM appointments 
                             WHERE doctor_id = ? AND status = 'booked'");
$bookedStmt->execute([$doctorId]);
$bookedAppointments = $bookedStmt->fetchAll(PDO::FETCH_ASSOC);

$availableSchedule = []; // Array to hold available schedules

foreach ($schedule as $timeSlot) {
    $startTime = new DateTime($timeSlot['start_time']);
    $endTime = new DateTime($timeSlot['end_time']);

    // Generate slots within each interval
    while ($startTime < $endTime) {
        $endPeriod = clone $startTime;
        $endPeriod->modify("+{$appointmentDuration} minutes");

        if ($endPeriod <= $endTime) {
            $slotTime = $startTime->format('H:i');

            // Check if this slot is already booked
            $isBooked = array_search($slotTime, array_column($bookedAppointments, 'appointment_time')) !== false;

            if (!$isBooked) {
                // If the slot is available, add the schedule ID and day to the array
                $availableSchedule[] = [
                    'schedule_id' => $timeSlot['schedule_id'],
                    'day_of_week' => $timeSlot['day_of_week']
                ];
                break; // Break out of the while loop since we only need to know if at least one slot is available
            }
        }

        $startTime->modify("+{$appointmentDuration} minutes");
    }
}

// Filter out duplicate entries (as one schedule ID may have multiple available slots)
$uniqueAvailableSchedule = array_unique($availableSchedule, SORT_REGULAR);

echo json_encode(['availableTimes' => $uniqueAvailableSchedule]);
?>
