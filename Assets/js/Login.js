// ============ Greeting Javascript Code =============

var myDate = new Date();

var hrs = myDate.getHours();

var greet;

if (hrs < 12)
    greet = 'Morning';
else if (hrs >= 12 && hrs <= 17)
    greet = 'Afternoon';
else if (hrs >= 17 && hrs <= 24)
    greet = 'Evening';

document.getElementById('lblGreetings').innerHTML = greet;

// ============ Countdown Javascript Code =============

function startCountdown(seconds, redirectUrl) {

    var countdownElement = document.getElementById('countdown');
    
    var remainingSeconds = seconds;
    
    var countdownInterval = setInterval(function() {

        countdownElement.innerHTML = remainingSeconds;

        remainingSeconds--;
        
        if (remainingSeconds < 0) {

            clearInterval(countdownInterval);

            window.location.href = redirectUrl;

        }

    }, 1000);

}

// ============ Identity Verification Javascript Code =============

document.addEventListener('DOMContentLoaded', () => {

    const verifyBtn = document.getElementById('verifyBtn');

    if (verifyBtn) {

        verifyBtn.addEventListener('click', async () => {

            try {

                const res = await fetch('/Modules/NexureSolutions/Identity/index.php');

                const data = await res.json();

                if (data.url) {

                    window.location.href = data.url;

                } else {

                    alert('Failed to start verification. Please try again.');

                }

            } catch (err) {

                console.error('Verification error:', err);

                alert('An error occurred. Please try again.');

            }

        });

    }

});

// ============ Redirect Javascript Code =============

window.onload = function() {

    startCountdown(5, '/Login');

};