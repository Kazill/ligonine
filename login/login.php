<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/config.php');
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas - Pacientų Registracijos Sistema</title>
    <link rel="stylesheet" href="/ligonine/style.css">

</head>
<body>
    <header>
        <h1>Prisijungimas</h1>
    </header>

    <section class="login-form">
        <form id="login-form">
            <label for="email">Vartotojo paštas:</label>
            <input type="text" id="email" name="email" required>

            <label for="password">Slaptažodis:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Prisijungti</button>
        </form>
        <button class="main-menu-button" onclick="window.location.href='/ligonine/index.php'">Pradinis puslapis</button>
    </section>
    <script src="js/login.js"></script>
</body>
</html>
