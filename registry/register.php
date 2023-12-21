<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// At the beginning of each PHP script
?>
<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Registracija - Pacientų Registracijos Sistema</title>
    <link rel="stylesheet" href="/ligonine/style.css">
</head>

<body>
    <header>
        <h1>Registracija</h1>
    </header>

    <section class="register-form">
        <form id="register-form">
            <label for="name">Vardas:</label>
            <input type="text" id="name" name="name" required>

            <label for="surname">Pavardė:</label>
            <input type="text" id="surname" name="surname" required>

            <label for="email">El. Paštas:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Slaptažodis:</label>
            <input type="password" id="password" name="password" required>

            <label for="phone-number">Tel. Nr.:</label>
            <input type="tel" id="phone-number" name="phone-number">

            <label for="user-type">Rolė:</label>
            <select id="user-type" name="user-type" required onchange="toggleSpecialty()">
                <option value="patient">Pacientas</option>
                <!-- <option value="doctor">Gydytojas</option> -->
                <option value="admin">Administratorius</option>
            </select>

            <div id="specialty-field" style="display: none;">
                <label for="specialty">Specialybė:</label>
                <select id="specialty" name="specialty">
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
                </select>
                <fieldset>
                    <legend>Work Schedule:</legend>
                    <p>Select the days you are available and specify your work hours:</p>
                    <div class="work-schedule-container">
                        <div class="work-schedule-day" data-day="Monday">
                            <label class="day-label">
                                <input type="checkbox" id="monday" name="work_days" value="Monday"
                                    onchange="toggleDayHours('monday')">
                                Monday:
                            </label>
                            <div class="day-hours" id="monday_hours">
                                <div class="schedule-entry work-interval">
                                    from <input type="time" id="monday_start_1" name="monday_start[]">
                                    to <input type="time" id="monday_end_1" name="monday_end[]">
                                </div>
                                <br>
                                <button type="button" onclick="addBreak('Monday')" class="add-break-btn">Add
                                    Break</button>
                            </div>
                            <div id="monday_breaks"></div>
                        </div>

                        <div class="work-schedule-day" data-day="Tuesday">
                            <label class="day-label">
                                <input type="checkbox" id="tuesday" name="work_days" value="Tuesday"
                                    onchange="toggleDayHours('tuesday')">
                                    Tuesday:
                            </label>
                            <div class="day-hours" id="tuesday_hours">
                                <div class="schedule-entry work-interval">
                                    from <input type="time" id="tuesday_start_1" name="tuesday_start[]">
                                    to <input type="time" id="tuesday_end_1" name="tuesday_end[]">
                                </div>
                                <br>
                                <button type="button" onclick="addBreak('Tuesday')" class="add-break-btn">Add
                                    Break</button>
                            </div>
                            <div id="tuesday_breaks"></div>
                        </div>

                        <div class="work-schedule-day" data-day="Wednesday">
                            <label class="day-label">
                                <input type="checkbox" id="wednesday" name="work_days" value="Wednesday"
                                    onchange="toggleDayHours('wednesday')">
                                    Wednesday:
                            </label>
                            <div class="day-hours" id="wednesday_hours">
                                <div class="schedule-entry work-interval">
                                    from <input type="time" id="wednesday_start_1" name="wednesday_start[]">
                                    to <input type="time" id="wednesday_end_1" name="wednesday_end[]">
                                </div>
                                <br>
                                <button type="button" onclick="addBreak('Wednesday')" class="add-break-btn">Add
                                    Break</button>
                            </div>
                            <div id="wednesday_breaks"></div>
                        </div>

                        <div class="work-schedule-day" data-day="Thursday">
                            <label class="day-label">
                                <input type="checkbox" id="thursday" name="work_days" value="Thursday"
                                    onchange="toggleDayHours('thursday')">
                                    Thursday:
                            </label>
                            <div class="day-hours" id="thursday_hours">
                                <div class="schedule-entry work-interval">
                                    from <input type="time" id="thursday_start_1" name="thursday_start[]">
                                    to <input type="time" id="thursday_end_1" name="thursday_end[]">
                                </div>
                                <br>
                                <button type="button" onclick="addBreak('Thursday')" class="add-break-btn">Add
                                    Break</button>
                            </div>
                            <div id="thursday_breaks"></div>
                        </div>

                        <div class="work-schedule-day" data-day="Friday">
                            <label class="day-label">
                                <input type="checkbox" id="friday" name="work_days" value="Friday"
                                    onchange="toggleDayHours('friday')">
                                    Friday:
                            </label>
                            <div class="day-hours" id="friday_hours">
                                <div class="schedule-entry work-interval">
                                    from <input type="time" id="friday_start_1" name="friday_start[]">
                                    to <input type="time" id="friday_end_1" name="friday_end[]">
                                </div>
                                <br>
                                <button type="button" onclick="addBreak('Friday')" class="add-break-btn">Add
                                    Break</button>
                            </div>
                            <div id="friday_breaks"></div>
                        </div>

                    </div>
                </fieldset>
            </div>

            <button type="submit">Registruotis</button>
        </form>
        <button class="main-menu-button" onclick="window.location.href='/ligonine/index.php'">Pradinis puslapis</button>
    </section>
    <footer>
        <p>© 2023 Pacientų Registracijos Sistema. Autorius: Jaunius Šilingas. Vadovas: Gadeikytė Aušra</p>
    </footer>
    <script>
        function toggleSpecialty() {
            var userType = document.getElementById('user-type').value;
            var specialtyField = document.getElementById('specialty-field');

            if (userType === 'doctor') {
                specialtyField.style.display = 'block'; // Show
            } else {
                specialtyField.style.display = 'none'; // Hide
            }
        }
        function addBreak(day) {
            var breaksContainer = document.getElementById(day.toLowerCase() + '_breaks');
            var breakCount = breaksContainer.children.length + 1;

            var breakHtml = `
        <div class="schedule-entry break-interval">
            <label for="${day.toLowerCase()}_start_${breakCount}">Break ${breakCount}:</label>
            from <input type="time" id="${day.toLowerCase()}_start_${breakCount}" name="${day.toLowerCase()}_start[]">
            to <input type="time" id="${day.toLowerCase()}_end_${breakCount}" name="${day.toLowerCase()}_end[]">
        </div>
    `;

            breaksContainer.insertAdjacentHTML('beforeend', breakHtml);
        }
        function toggleDayHours(dayId) {
            var checkbox = document.getElementById(dayId);
            var hoursDiv = document.getElementById(dayId + '_hours');
            var breaksDiv = document.getElementById(dayId + '_breaks');

            if (checkbox.checked) {
                hoursDiv.style.display = 'block'; // Show the hours inputs
                breaksDiv.style.display = 'block'; // Show the breaks if any
            } else {
                hoursDiv.style.display = 'none'; // Hide the hours inputs
                breaksDiv.style.display = 'none'; // Hide the breaks
            }
        }
        // Initial call to set up the correct display state based on the checkbox
        document.querySelectorAll('.work-schedule-day input[type="checkbox"]').forEach(function (checkbox) {
            toggleDayHours(checkbox.id);
        });
    </script>
    <script src="/ligonine/registry/register.js"></script>
</body>

</html>