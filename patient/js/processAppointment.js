document.getElementById('appointment-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
    fetch('/ligonine/patient/php/processAppointment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Handle response here (e.g., show a success message)
        alert('Appointment booked successfully!');
        window.location.href = '/ligonine/index.php'; // Redirect to the homepage after successful booking
    })
    .catch(error => {
        // Handle errors here
        console.error('Error:', error);
        alert('Error booking appointment');
    });
});
