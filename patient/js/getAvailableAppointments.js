document.getElementById('day-selection').addEventListener('change', function() {
    const selectedDay = this.value;
    const doctorId = document.getElementById('doctor-selection').value;
    if (selectedDay && doctorId) {
        fetch('/ligonine/patient/php/getAvailableAppointments.php?doctorId=' + doctorId + '&day=' + selectedDay)
            .then(response => response.json())
            .then(data => {
                populateAppointmentTimes(data.availableSlots);
            })
            .catch(error => console.error('Unable to get appointments.', error));
    } else {
        resetAppointmentTimes();
    }
});

function populateAppointmentTimes(times) {
    const select = document.getElementById('appointment-time');
    select.innerHTML = ''; // Clear existing options
    select.add(new Option('-- Pasirinkite laiką --', ''));
    times.forEach(time => {
        const option = new Option(`${time}`);
        select.add(option);
    });
}

function resetAppointmentTimes() {
    const select = document.getElementById('appointment-time');
    select.innerHTML = '';
    select.add(new Option('-- Pasirinkite laiką --', ''));
}
