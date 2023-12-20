<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php');

$topDoctors = [];
$stmt = $pdo->query("SELECT u.id, u.Name, u.Surname, COUNT(a.id) AS completed_appointments
                     FROM user u
                     JOIN appointments a ON u.id = a.doctor_id
                     WHERE a.status = 'completed' AND u.`User type` = 'doctor'
                     GROUP BY u.id
                     ORDER BY completed_appointments DESC
                     LIMIT 5");

if ($stmt) {
    $topDoctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Vartotojo Sąsaja - Pacientų Registracijos Sistema</title>
    <link rel="stylesheet" href="/ligonine/style.css">
</head>

<body>
    <header>
        <h1>Paciento Sąsaja</h1>
    </header>

    <section class="patient-dashboard">
        <?php
        // Somewhere in the body where you want to display the top doctors
        if (!empty($topDoctors)) {
            echo "<section class='top-doctors'>";
            echo "<h2>5 geriausi daktarai</h2>";
            echo "<ul>";
            foreach ($topDoctors as $doctor) {
                echo "<li>" . htmlspecialchars($doctor['Name']) . " " . htmlspecialchars($doctor['Surname']) . " - " . htmlspecialchars($doctor['completed_appointments']) . " įvykdyti vizitai</li>";
            }
            echo "</ul>";
            echo "</section>";
        }
        ?>
        <h2>Užsiregistruokite pas gydytoją</h2>
        <form id="appointment-form">
            <label for="doctor-selection">Pasirinkite Specialybę:</label>
            <select id="specialty" name="specialty" required>
                <option value="">-- Pasirinkite specialybę --</option>
                <option value="general-practice">Šeimos gydytojas</option>
                <option value="cardiology">Kardiologija</option>
                <option value="neurology">Neurologija</option>
                <option value="pediatrics">Pediatrija</option>
                <option value="orthopedics">Ortopedija</option>
                <option value="dermatology">Dermatologija</option>
                <option value="oncology">Onkologija</option>
                <option value="ophthalmology">Oftalmologija</option>
                <option value="psychiatry">Psichiatrija</option>
                <option value="endocrinology">Endokrinologija</option>
                <!-- More specialties as needed -->
            </select>
            <label for="doctor-selection">Pasirinkite gydytoją:</label>
            <select id="doctor-selection" name="doctor">
                <option value="">-- Pasirinkite specialybę --</option>
            </select>

            <label for="day-selection">Pasirinkite dieną:</label>
            <select id="day-selection" name="day">
                <option value="">-- Pasirinkite dieną --</option>
                <option value="Monday">Pirmadienis</option>
                <option value="Tuesday">Antradienis</option>
                <option value="Wednesday">Trečiadienis</option>
                <option value="Thursday">Ketvirtadienis</option>
                <option value="Friday">Penktadienis</option>
                <!-- Add other days as needed -->
            </select>

            <label for="appointment-time">Pasirinkite laiką:</label>
            <select id="appointment-time" name="appointment-time">
                <option value="">-- Pasirinkite laiką --</option>
                <!-- Times will be populated here based on the selected day -->
            </select>


            <button type="submit">Užsiregistruoti</button>
        </form>
    </section>
    <div>
        <button class="main-menu-button" onclick="location.href='/ligonine/index.php'">Pradinis puslapis</button>
    </div>


    <footer>
        <p>© 2023 Pacientų Registracijos Sistema</p>
    </footer>
    <script src="/ligonine/patient/js/getDoctors.js"></script>
    <script src="/ligonine/patient/js/getAvailableAppointments.js"></script>
    <script src="/ligonine/patient/js/getAvailableTimes.js"></script>
    <script src="/ligonine/patient/js/processAppointment.js"></script>
    <script src="/ligonine/login/js/checkLogin.js"></script>
</body>

</html>