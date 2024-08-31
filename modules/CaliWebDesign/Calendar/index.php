<?php

    session_start();
    $pagetitle = "Your Calendar and Planner";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>' . $pagetitle . '</title>';

    $eventsDefinitionW = new \CaliWebDesign\Calendar\CalendarComponents();
    $eventsDefinitionW->eventsRetrive($con);

?>

<link href="/modules/CaliWebDesign/Calendar/assets/css/main.css" rel="stylesheet" />
<script src="https://demos.codexworld.com/includes/js/jquery.min.js"></script>
<script src="/modules/CaliWebDesign/Calendar/assets/js/app.js"></script>
<script src="/modules/CaliWebDesign/Calendar/assets/js/dialog.js"></script>

<section class="section first-dashboard-area-cards">
    <div class="container width-98">
        <div class="caliweb-one-grid">
            <div class="caliweb-card dashboard-card">
                <div class="card-header">
                    <div class="display-flex align-center" style="justify-content: space-between;">
                        <div class="display-flex align-center">
                            <div class="no-padding margin-10px-right icon-size-formatted">
                                <img src="/assets/img/systemIcons/calendar.png" alt="Tasks Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                            </div>
                            <div>
                                <p class="no-padding font-14px">Calendar</p>
                                <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;"><span id="week-label"></span></h4>
                            </div>
                        </div>
                        <div>
                            <button class="caliweb-button secondary no-margin" id="prev-week" style="padding:6px 8px;"><span class="lnr lnr-chevron-left"></span></button>
                            <button class="caliweb-button secondary no-margin" id="next-week" style="padding:6px 8px;"><span class="lnr lnr-chevron-right"></span></button>
                            <a href="javascript:void(0)" onclick="openModal(day, hour)" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="margin-top:-2%;">
                    <div class="caliweb-grid caliweb-two-grid account-grid-modified no-grid-row-bottom">
                        <div>
                            <div id="calendar">
                                <div class="header-row">
                                    <div class="time-header"></div> <!-- Empty corner -->
                                    <div class="day-header">Sunday</div>
                                    <div class="day-header">Monday</div>
                                    <div class="day-header">Tuesday</div>
                                    <div class="day-header">Wednesday</div>
                                    <div class="day-header">Thursday</div>
                                    <div class="day-header">Friday</div>
                                    <div class="day-header">Saturday</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="display-flex align-center" style="justify-content:space-between; margin-bottom:6%;">
                                <p class="no-padding">Upcoming Events</p>
                            </div>
                            <?php if ($eventsDefinitionW->eventsresponse->num_rows > 0): ?>
                                <?php while ($event = mysqli_fetch_assoc($eventsDefinitionW->eventsresponse)): ?>
                                    <div class="caliweb-card dashboard-card note-card">
                                        <div class="card-header">
                                            <div class="display-flex align-center">
                                                <div class="no-padding margin-20px-right icon-size-formatted" style="height: 40px; width: 40px;">
                                                    <img src="/assets/img/systemIcons/event.png" alt="Events Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                                </div>
                                                <div>
                                                    <p class="no-padding font-12px"><strong><?php echo htmlspecialchars($event['eventName']); ?></strong></p>
                                                    <p class="no-padding font-12px"><?php echo date('m/d/Y h:i A', strtotime($event['eventTimeDate'])); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="no-padding font-12px"><?php echo htmlspecialchars($event['eventDescription']); ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p style="font-size:14px;">No upcoming events found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="event-modal" class="modal">
    <div class="modal-content">
        <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Create New Event</h6>

        <form id="event-form">
            <input type="hidden" id="event-date" name="date">
            <div class="form-control" style="margin-bottom:2%; margin-top:4%;">
                <label for="event-time">Time:</label>
                <input type="time" id="event-time" class="form-input" name="time" required="">
            </div>
            <div class="form-control" style="margin-bottom:4%;">
                <label for="event-description">Description:</label>
                <textarea id="event-description" name="description" class="form-input" required=""></textarea>
            </div>
        </form>

        <div style="display:flex; align-items:right; justify-content:right;">
            <button type="submit" name="submit" class="caliweb-button primary">Add Event</button>
            <button class="caliweb-button secondary" id="close-modal">Close</button>
        </div>
    </div>
</div>



<?php

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>