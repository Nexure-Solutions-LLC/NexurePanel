// Cali Web Design Panel UI/UX Library
// Feel free to add to this as needed.

// ====================== Required Preloader JS Code ======================

window.addEventListener("load", function() {const preloader = document.querySelector(".preloader");setTimeout(function() {preloader.classList.add("loaded");}, 1);});


// ====================== Dashboard Calendar Plugin ======================

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendarEl = document.getElementById('calendar');

    if (!calendarEl) {
        return;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 650,
        events: 'fetchEvents.php',

        selectable: true,
        select: async function (start, end, allDay) {
            const { value: formValues } = await Swal.fire({
                title: 'Add Event',
                html:
                    '<input id="swalEvtTitle" class="swal2-input" placeholder="Enter title">' +
                    '<textarea id="swalEvtDesc" class="swal2-input" placeholder="Enter description"></textarea>' +
                    '<input id="swalEvtURL" class="swal2-input" placeholder="Enter URL">',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('swalEvtTitle').value,
                        document.getElementById('swalEvtDesc').value,
                        document.getElementById('swalEvtURL').value
                    ]
                }
            });

            if (formValues) {
                // Add event
                fetch("eventHandler.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ request_type:'addEvent', start:start.startStr, end:start.endStr, event_data: formValues}),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == 1) {
                            Swal.fire('Event Add feature is disabled for this demo!', '', 'warning');
                        } else {
                            Swal.fire(data.error, '', 'error');
                        }
                        calendar.refetchEvents();
                    })
                    .catch(console.error);
            }
        },

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            info.el.style.borderColor = 'red';

            Swal.fire({
                title: info.event.title,
                //text: info.event.extendedProps.description,
                icon: 'info',
                html:'<p>'+info.event.extendedProps.description+'</p><a href="'+info.event.url+'">Visit event page</a>',
                showCloseButton: true,
                showCancelButton: true,
                showDenyButton: true,
                cancelButtonText: 'Close',
                confirmButtonText: 'Delete',
                denyButtonText: 'Edit',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Delete event
                    fetch("eventHandler.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ request_type:'deleteEvent', event_id: info.event.id}),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == 1) {
                                //Swal.fire('Event deleted successfully!', '', 'success');
                                Swal.fire('Event Delete feature is disabled for this demo!', '', 'warning');
                            } else {
                                Swal.fire(data.error, '', 'error');
                            }

                            // Refetch events from all sources and rerender
                            calendar.refetchEvents();
                        })
                        .catch(console.error);
                } else if (result.isDenied) {
                    // Edit and update event
                    Swal.fire({
                        title: 'Edit Event',
                        html:
                            '<input id="swalEvtTitle_edit" class="swal2-input" placeholder="Enter title" value="'+info.event.title+'">'+
                            '<textarea id="swalEvtDesc_edit" class="swal2-input" placeholder="Enter description">'+info.event.extendedProps.description+'</textarea>'+
                            '<input id="swalEvtURL_edit" class="swal2-input" placeholder="Enter URL" value="'+info.event.url+'">',
                        focusConfirm: false,
                        confirmButtonText: 'Submit',
                        preConfirm: () => {
                            return [
                                document.getElementById('swalEvtTitle_edit').value,
                                document.getElementById('swalEvtDesc_edit').value,
                                document.getElementById('swalEvtURL_edit').value
                            ];
                        }
                    }).then((result) => {
                        if (result.value) {
                            // Event update request
                            fetch("eventHandler.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({request_type:'editEvent', start:info.event.startStr, end:info.event.endStr, event_id: info.event.id, event_data: result.value}),
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.status == 1) {
                                        //Swal.fire('Event updated successfully!', '', 'success');
                                        Swal.fire('Event Update feature is disabled for this demo!', '', 'warning');
                                    } else {
                                        Swal.fire(data.error, '', 'error');
                                    }

                                    // Refetch events from all sources and rerender
                                    calendar.refetchEvents();
                                })
                                .catch(console.error);
                        }
                    });
                } else {
                    Swal.close();
                }
            });
        }
    });

    calendar.render();
});

// ====================== Dropdown JS Code ======================

document.addEventListener('DOMContentLoaded', function () {
    const moreButton = document.querySelector('.more-button');
    const more = document.querySelector('.more');

    moreButton.addEventListener('click', function (e) {
        e.preventDefault();
        more.classList.toggle('active');
    });

    document.addEventListener('click', function (e) {
        if (!more.contains(e.target)) {
            more.classList.remove('active');
        }
    });
});

// ====================== Dashboard Time of Day Text ======================


var myDate = new Date();
var hrs = myDate.getHours();

var greet;

if (hrs < 12)
    greet = 'Good Morning';
else if (hrs >= 12 && hrs <= 17)
    greet = 'Good Afternoon';
else if (hrs >= 17 && hrs <= 24)
    greet = 'Good Evening';

document.getElementById('greetingMessage').innerHTML = greet;
