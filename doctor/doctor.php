<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/ligonine/db/db_connection.php');
session_start();
// Assuming you have a session or a similar mechanism
$doctorId = $_SESSION['user_id'];

// Prepare and execute the query
$stmt = $pdo->prepare("SELECT u.Name, u.Surname, a.appointment_date, a.appointment_time, a.status, a.id as appointment_id 
                           FROM appointments a 
                           JOIN user u ON a.patient_id = u.id 
                           WHERE a.doctor_id = :doctorId AND a.status = 'booked'");
$stmt->execute(['doctorId' => $doctorId]);

$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Gydytojo Sąsaja - Pacientų Registracijos Sistema</title>
    <link rel="stylesheet" href="/ligonine/style.css">
</head>

<body>
    <header>
        <h1>Gydytojo Sąsaja</h1>
    </header>

    <section class="doctor-dashboard">
        <h2>Užsiregistravę Pacientai</h2>
        <table>
            <thead>
                <tr>
                    <th>Paciento Vardas</th>
                    <th>Paciento Pavardė</th>
                    <th>Registruoto susitikimo Laikas</th>
                    <th>Veiksmai</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['Name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['Surname']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date'] . ' ' . $appointment['appointment_time']); ?></td>
                        <td>
                            <button type="button" onclick="confirmAppointment(<?php echo $appointment['appointment_id']; ?>)">Siųsti žinutę</button>
                        </td>
                    </tr>
                    <!-- Message Modal -->
                    <div id="messageModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2>Write a Message</h2>
                            <textarea id="messageContent"></textarea>
                            <button onclick="sendAppointmentMessage()">Send</button>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
    <div>
        <button class="main-menu-button" onclick="location.href='/ligonine/index.php'">Pradinis puslapis</button>
    </div>



    <footer>
        <p>© 2023 Pacientų Registracijos Sistema</p>
    </footer>

    <script src="/ligonine/login/js/checkLogin.js"></script>
    <script>
        let currentAppointmentId = null;

        function confirmAppointment(appointmentId) {
            currentAppointmentId = appointmentId;
            document.getElementById('messageModal').style.display = 'block';
        }

        function sendAppointmentMessage() {
            const messageContentElement = document.getElementById('messageContent');
            const messageContent = messageContentElement.value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/ligonine/doctor/script.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                // Check if the request was successful
                if (xhr.status === 200) {
                    // Handle response here
                    console.log(xhr.responseText);

                    // Assuming the message is sent successfully, clear the message content
                    messageContentElement.value = '';

                    // Assuming you have a function to close the modal
                    closeModal();
                } else {
                    console.error('An error occurred:', xhr.responseText);
                }
            };
            xhr.send('appointmentId=' + currentAppointmentId + '&messageContent=' + encodeURIComponent(messageContent));
        }
        // Close the modal
        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            let modal = document.getElementById('messageModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Close the modal when the user clicks on 'X'
        document.querySelector('.close').addEventListener('click', closeModal);
    </script>
</body>

</html>