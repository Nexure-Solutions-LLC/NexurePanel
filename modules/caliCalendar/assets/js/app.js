document.addEventListener('DOMContentLoaded', () => {

    const calendarContainer = document.getElementById('calendar');
    const weekLabel = document.getElementById('week-label');
    const prevWeekButton = document.getElementById('prev-week');
    const nextWeekButton = document.getElementById('next-week');
    const eventModal = document.getElementById('event-modal');
    const eventForm = document.getElementById('event-form');
    const closeModalButton = document.getElementById('close-modal');

    let currentDate = new Date();

    function renderCalendar() {

        Array.from(calendarContainer.children).forEach(child => {

            if (!child.classList.contains('header-row')) {

                calendarContainer.removeChild(child);

            }

        });

        const startOfWeek = getStartOfWeek(currentDate);
        weekLabel.innerText = `${startOfWeek.toDateString()} - ${new Date(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate() + 6).toDateString()}`;

        for (let hour = 0; hour < 24; hour++) {

            const timeSlot = document.createElement('div');
            timeSlot.className = 'time-slot';
            timeSlot.innerText = `${String(hour).padStart(2, '0')}:00`;
            calendarContainer.appendChild(timeSlot);

            for (let i = 0; i < 7; i++) {

                const day = new Date(startOfWeek.getFullYear(), startOfWeek.getMonth(), startOfWeek.getDate() + i);
                const dayEventSlot = document.createElement('div');
                dayEventSlot.className = 'day-events';
                dayEventSlot.dataset.date = day.toISOString().split('T')[0];
                dayEventSlot.dataset.hour = hour;
                dayEventSlot.addEventListener('click', () => openModal(day, hour));
                calendarContainer.appendChild(dayEventSlot);

            }

        }

    }

    function getStartOfWeek(date) {

        const start = new Date(date);
        const day = start.getDay();
        const diff = start.getDate() - day + (day === 0 ? -6 : 1);
        return new Date(start.setDate(diff));

    }

    function openModal(date, hour) {

        eventModal.style.display = 'block';
        document.getElementById('event-date').value = date.toISOString().split('T')[0];
        document.getElementById('event-time').value = `${String(hour).padStart(2, '0')}:00`;

    }

    function closeModal() {

        eventModal.style.display = 'none';

    }

    function addEvent(event) {

        event.preventDefault();
        const date = new Date(document.getElementById('event-date').value);
        const time = document.getElementById('event-time').value;
        const description = document.getElementById('event-description').value;

        const dayEventSlot = document.querySelector(`.day-events[data-date="${date.toISOString().split('T')[0]}"][data-hour="${parseInt(time.split(':')[0])}"]`);

        if (dayEventSlot) {

            const eventElement = document.createElement('div');
            eventElement.className = 'event';
            eventElement.innerText = `${time} - ${description}`;
            dayEventSlot.appendChild(eventElement);

        }

        closeModal();
    }

    prevWeekButton.addEventListener('click', () => {

        currentDate.setDate(currentDate.getDate() - 7);
        renderCalendar();

    });

    nextWeekButton.addEventListener('click', () => {

        currentDate.setDate(currentDate.getDate() + 7);
        renderCalendar();

    });

    eventForm.addEventListener('submit', addEvent);
    closeModalButton.addEventListener('click', closeModal);

    renderCalendar();
    
});
