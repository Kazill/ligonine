document.getElementById('doctor-selection').addEventListener('change', function () {
    const doctorId = this.value;
    resetTimes();
    fetch('/ligonine/patient/php/getAvailableTimes.php?doctorId=' + doctorId)
        .then(response => response.json())
        .then(data => {
            populateTimes(data.availableTimes);
        })
        .catch(error => console.error('Unable to get available times.', error));
});

function populateTimes(availableTimes) {
    const input = document.getElementById('day-selection');
    input.innerHTML = ''; // Clear previous options
    input.add(new Option('-- Pasirinkite dieną --', '')); // Add the default option
    availableTimes.forEach(time => {
        // Format and add available time slots
        $lithuanianDay = convertDayToLithuanian(time.day_of_week);
        const option = new Option($lithuanianDay, time.schedule_id);
        input.appendChild(option);
    });
}
function convertDayToLithuanian(dayInEnglish) {
    const days = {
        'Monday': 'Pirmadienis',
        'Tuesday': 'Antradienis',
        'Wednesday': 'Trečiadienis',
        'Thursday': 'Ketvirtadienis',
        'Friday': 'Penktadienis',
        'Saturday': 'Šeštadienis',
        'Sunday': 'Sekmadienis'
    };

    return days[dayInEnglish] || 'Unknown';
}



function resetTimes() {
    const input = document.getElementById('day-selection');
    input.innerHTML = ''; // Clear previous options
}