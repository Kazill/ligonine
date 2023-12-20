document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();

    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;

    fetch('/ligonine/login/php/auth.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data.success) {
            updateAppointmentStatuses().then(() => {
                window.location.href ='/ligonine/index.php'; // Redirect after updating appointments
            });
        } else {
            alert(data.message); // Use the server-provided message
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('There was an error during login: ' + error.message);
    });
});
function updateAppointmentStatuses() {
    return fetch('/ligonine/login/php/checkAppointments.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Error updating appointments:', error);
        });
}