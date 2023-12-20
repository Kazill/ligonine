<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/config.php');
include $_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php';
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

$email = $input['email'];
$password = $input['password'];

$sql = "SELECT * FROM user WHERE Email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    header('Content-Type: application/json'); // Set the header for JSON response
    echo json_encode(['success' => false, 'message' => 'Vartotojo paštas nerastas.']);
    exit;
}

if (!password_verify($password, $user['Password'])) {
    header('Content-Type: application/json'); // Set the header for JSON response
    echo json_encode(['success' => false, 'message' => 'Neteisingas slaptažodis.']);
    exit;
}

// If the execution reaches here, it means both email and password are correct
$_SESSION['user_id'] = $user['id']; // Or another unique identifier from the user's record
$_SESSION['user_role'] = $user['User type']; // Save role in session for role-based actions
header('Content-Type: application/json'); // Set the header for JSON response
echo json_encode(['success' => true]);
?>