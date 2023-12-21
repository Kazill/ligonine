<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include($_SERVER['DOCUMENT_ROOT'] . '/ligonine/login/php/checkLogin.php');

$messages = [];
if ($isLoggedIn && $user && $_SESSION['user_role'] == "patient") {
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT m.id, m.message_content, m.sent_at, u.Name AS doctor_name, u.Surname AS doctor_surname
    FROM messages m
    JOIN appointments a ON m.appointment_id = a.id
    JOIN user u ON a.doctor_id = u.id
    WHERE a.patient_id = :userId");

    if ($stmt->execute(['userId' => $userId])) {
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
$isAdminVerified = false; // Flag to hold verification status

// Check if user is admin
if ($isLoggedIn && $user && $_SESSION['user_role'] == "admin") {
    // Check the 'Status' field for the logged-in admin user
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT `Status` FROM `user` WHERE `id` = :userId AND `User type` = 'admin'");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $adminStatus = $stmt->fetch(PDO::FETCH_ASSOC);
        $isAdminVerified = $adminStatus && $adminStatus['Status'] == 1;
    }
}

?>
<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Pacientų Registracijos Sistema</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Pacientų Registracijos Sistema</h1>
    </header>

    <main>
    <?php if ($isLoggedIn && $user && $_SESSION['user_role'] == "admin") : ?>
                <p>Admin Status: <?= $isAdminVerified ? "Verified" : "Not Verified"; ?></p>
            <?php endif; ?>
        <?php if ($isLoggedIn && $user) : ?>
            <p id="welcome-message">Sveiki atvykę, <?= htmlspecialchars($user['Name']); ?>.</p>
            <button class="main-menu-button" id="sign-out" onclick="window.location.href ='/ligonine/login/php/logout.php'">Atsijungti</button>
            <?php if ($_SESSION['user_role'] == "admin" && $isAdminVerified) : ?>
                <!-- Content for verified admin -->
                <nav id="role-based-nav">
                    <?= $roleBasedNav; ?>
                </nav>
                <!-- You can include other admin-specific content here -->
            <?php endif; ?>

            <?php if ($_SESSION['user_role'] == "patient" && !empty($messages)) : ?>
                <div class="patient-messages">
                    <h2>Jūsų žinutės</h2>
                    <?php foreach ($messages as $message) : ?>
                        <div class="message">
                            <p><?= htmlspecialchars($message['message_content']); ?></p>
                            <small>Siuntėjas: <?= htmlspecialchars($message['doctor_name'] . " " . $message['doctor_surname']); ?></small>
                            <small>Atsiųsta: <?= htmlspecialchars($message['sent_at']); ?></small>
                            <button onclick="deleteMessage(<?= htmlspecialchars($message['id']); ?>)">Delete</button>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


        <?php else : ?>
            <p id="welcome-message">Sveiki atvykę į Pacientų Registracijos Sistemą. Prašome prisijungti.</p>
            <div id="auth-buttons">
                <button onclick="window.location.href='/ligonine/login/login.php'">Prisijungti</button>
                <button onclick="window.location.href='/ligonine/registry/register.php'">Registruotis</button>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>© 2023 Pacientų Registracijos Sistema. Autorius: Jaunius Šilingas. Vadovas: Gadeikytė Aušra</p>
    </footer>
    <script>
        function deleteMessage(messageId) {
            if (!confirm('Ar tikrai norite ištrinti žinutę?')) {
                return; // Stop if user cancels the confirmation
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/ligonine/doctor/delete_message.php', true); // Make sure this URL is correct
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Handle successful deletion here, such as removing the message div from the DOM
                    console.log('Message deleted');
                    location.reload(); // Quick way to refresh the page and show the updated message list
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            };
            xhr.send('messageId=' + messageId);
        }
    </script>
</body>

</html>