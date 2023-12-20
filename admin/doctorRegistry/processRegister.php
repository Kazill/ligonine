<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include $_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php';
header('Content-Type: application/json');
// Function to insert work schedule and intervals into the database
function insertWorkSchedule($pdo, $doctorId, $workIntervals)
{
    foreach ($workIntervals as $interval) {
        // Insert or find the work_schedule ID
        $stmt = $pdo->prepare("INSERT INTO work_schedule (doctor_id, day_of_week) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
        $stmt->execute([$doctorId, $interval['day_of_week']]);
        $scheduleId = $pdo->lastInsertId();

        // Insert the interval
        $stmt = $pdo->prepare("INSERT INTO work_intervals (schedule_id, start_time, end_time, `type`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$scheduleId, $interval['start_time'], $interval['end_time'], $interval['type']]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get the data as JSON
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate the basic data
    if (!isset($data['name'], $data['password'], $data['user-type'], $data['surname'], $data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Incomplete basic data provided']);
        exit;
    }

    $name = $data['name'];
    $surname = $data['surname'];
    $email = $data['email'];
    $password = $data['password'];
    $phone = $data['phone-number'];
    $userType = $data['user-type'];
    $specialty = isset($data['specialty']) ? $data['specialty'] : null; // Set specialty to null if not provided

    // Set $status based on $userType
    if ($userType === "patient" || $userType === "doctor") {
        $status = 1;
    } else {
        $status = 0;
    }

    // After validating the basic data and before hashing the password
    $emailCheckStmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE Email = ?");
    $emailCheckStmt->execute([$email]);
    $emailCount = $emailCheckStmt->fetchColumn();

    if ($emailCount > 0) {
        // If $emailCount is more than 0, the email already exists in the database
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'The provided email address is already in use.']);
        exit;
    }


    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert user data
        $stmt = $pdo->prepare("INSERT INTO user (`Name`, Surname, Email, `Password`, `Phone number`, Specialty, `User type`, `Status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $surname, $email, $hashed_password, $phone, $specialty, $userType, $status]);
        $userId = $pdo->lastInsertId();

        // Additional processing for doctors
        if ($userType === 'doctor' && isset($data['work_schedule'])) {
            insertWorkSchedule($pdo, $userId, $data['work_schedule']);
        }

        // Commit the transaction
        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (\PDOException $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        // Log the error message
        error_log("Error in register.php: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
}
