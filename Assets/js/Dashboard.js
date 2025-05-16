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
