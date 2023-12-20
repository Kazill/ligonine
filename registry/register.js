
function collectWorkIntervals() {
    let workIntervals = [];
    document.querySelectorAll('.work-schedule-day input[type="checkbox"]:checked').forEach(function(checkbox) {
        const day = checkbox.value;
        const dayDiv = checkbox.closest('.work-schedule-day');
        // Process work intervals
        const workIntervalsDivs = dayDiv.querySelectorAll('.schedule-entry.work-interval');
        workIntervalsDivs.forEach(function(interval, index) {
            const startInput = interval.querySelector('input[name^="' + day.toLowerCase() + '_start"]');
            const endInput = interval.querySelector('input[name^="' + day.toLowerCase() + '_end"]');

            if (startInput && endInput && startInput.value && endInput.value) {
                workIntervals.push({
                    day_of_week: day,
                    start_time: startInput.value,
                    end_time: endInput.value,
                    type: 'work'
                });
            }
        });

        // Process break intervals
        const breakIntervalsDivs = dayDiv.querySelectorAll('.schedule-entry.break-interval');
        breakIntervalsDivs.forEach(function(interval, index) {
            const startInput = interval.querySelector('input[name^="' + day.toLowerCase() + '_start"]');
            const endInput = interval.querySelector('input[name^="' + day.toLowerCase() + '_end"]');

            if (startInput && endInput && startInput.value && endInput.value) {
                workIntervals.push({
                    day_of_week: day,
                    start_time: startInput.value,
                    end_time: endInput.value,
                    type: 'break'
                });
            }
        });
    });

    return workIntervals;
}




document.getElementById('register-form').addEventListener('submit', function(event) {
    event.preventDefault();

    var name = document.getElementById('name').value;
    var surname = document.getElementById('surname').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var phoneNumber = document.getElementById('phone-number').value;
    var specialty = document.getElementById('specialty').value;
    var userType = document.getElementById('user-type').value;

    var formData = {
        name: name,
        surname: surname,
        email: email,
        password: password,
        'phone-number': phoneNumber,
        'user-type': userType
    };
    
    if (userType === 'doctor') {
        formData.specialty = specialty;
        formData.work_schedule = collectWorkIntervals(); // Collect the work schedule intervals
    }
    fetch('/ligonine/registry/processRegister.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 400) {
                return response.json().then(data => Promise.reject(data.error)); // Handle 400 Bad Request
            }
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert('Vartotojas sėkmingai užregistruotas ' + userType);
            window.location.href ='/ligonine/login/login.php'; // Redirect to login after registration
        } else {
            alert('Error: ' + data.error); // Now showing detailed error message from the server
        }
    })
    .catch(error => {
        if (typeof error === 'string') {
            // This is the case when the error message is from Promise.reject(data.error)
            alert('Registration error: ' + error);
        } else {
            console.error('Error:', error);
            alert(`There was an error registering the user: ${error.message}`);
        }
    });
    
});
