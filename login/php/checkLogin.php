<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/config.php');

$pdo = null;
$isLoggedIn = false;
$user = null;
$roleBasedNav = '';

try {
    // Database configuration
    $host = 'localhost';
    $db = 'it'; // your database name
    $userDB = 'root'; // your database username
    $pass = ''; // your database password
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Create a new PDO instance
    $pdo = new PDO($dsn, $userDB, $pass, $options);

    if (isset($_SESSION['user_id'])) {
        // Fetch user details from the database
        $stmt = $pdo->prepare("SELECT * FROM user WHERE ID = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $isLoggedIn = true;
            // Build role-based navigation HTML based on the user's role
            $roleBasedNav = '<ul>';
            if ($user['User type'] === 'admin') {
                $roleBasedNav .= '<li><a href="/ligonine/admin/admin.php">Administratoriaus Sąsaja</a></li>';
            }
            if ($user['User type'] === 'doctor') {
                $roleBasedNav .= '<li><a href="/ligonine/doctor/doctor.php">Gydytojo Sąsaja</a></li>';
            }
            if ($user['User type'] === 'patient') {
                $roleBasedNav .= '<li><a href="/ligonine/patient/patient.php">Paciento Sąsaja</a></li>';
            }
            $roleBasedNav .= '</ul>';
        } else {
            // Invalid session or user doesn't exist
            session_unset();
            session_destroy();
        }
    }
} catch (PDOException $e) {
    // Handle error, such as by logging it and sending an error response
    echo "Database error: " . $e->getMessage();
    exit;
}
?>
