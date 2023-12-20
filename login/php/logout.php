<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/config.php');
session_start();
session_destroy(); // Destroy all session data
header('Location: /ligonine/index.php'); // Redirect to the login page
exit;
?>