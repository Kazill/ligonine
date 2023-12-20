document.getElementById('specialty').addEventListener('change', function() {
    const specialty = this.value;
    fetch('/ligonine/patient/php/getDoctors.php?specialty=' + specialty)
        .then(response => response.json())
        .then(data => {
            populateDoctors(data.doctors);
        })
        .catch(error => console.error('Unable to get doctors.', error));
});

function populateDoctors(doctors) {
    const select = document.getElementById('doctor-selection');
    select.innerHTML = ''; // Clear existing options
    select.add(new Option('-- Pasirinkite gydytoją --', '')); // Add the default option
    doctors.forEach(doctor => {
        const option = new Option(doctor.Name + " " + doctor.Surname, doctor.id);
        select.add(option);
    });
}