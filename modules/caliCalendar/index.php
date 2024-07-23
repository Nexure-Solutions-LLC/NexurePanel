<?php
    session_start();
    $pagetitle = "Your Calendar and Planner";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.'</title>';
?>

    <link href="/modules/caliCalendar/assets/css/main.css" rel="stylesheet" />
    <script src="https://demos.codexworld.com/includes/js/jquery.min.js"></script>
    <script src="/modules/caliCalendar/assets/js/app.js"></script>
    <script src="/modules/caliCalendar/assets/js/dialog.js"></script>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid">
                <div class="caliweb-card dashboard-card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>