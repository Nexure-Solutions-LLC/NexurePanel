// ====================== Nexure Risk Score 1.0 ======================

const score = window.nexureRiskScore || 0;

const maxScore = 999;

const bar = document.querySelector('.score-bar');

const indicator = document.getElementById('score-indicator');

function updateIndicatorPosition() {

    if (bar && indicator) {

        const percent = Math.min((score / maxScore) * 100, 100);

        indicator.style.left = `calc(${percent}% - 1.5px)`;

    }
    
}

updateIndicatorPosition();

document.addEventListener('DOMContentLoaded', () => {

    const timeElement = document.getElementById('userSystemTime');

    function formatTime(date) {

        const options = {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        };

        const timePart = date.toLocaleTimeString(undefined, options);

        const day = date.getDate();

        const month = date.toLocaleString(undefined, { month: 'long' });

        const year = date.getFullYear();

        return `${timePart} ${month} ${day}, ${year}`;

    }

    function updateTime() {

        const now = new Date();

        timeElement.textContent = `${formatTime(now)}`;

    }

    if (timeElement) {

        updateTime();

        setInterval(updateTime, 1000);

    }

});

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

// ====================== Dashboard Time of Day Text ======================

const greetingElement = document.getElementById('greetingMessage');

if (greetingElement) {

    const myDate = new Date();

    const hrs = myDate.getHours();

    let greet;

    if (hrs < 12)
        greet = 'Good Morning';
    else if (hrs >= 12 && hrs <= 17)
        greet = 'Good Afternoon';
    else
        greet = 'Good Evening';

    greetingElement.innerHTML = greet;

}
