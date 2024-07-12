<?php
    session_start();
    $pagetitle = "Your Calendar and Planner";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {
        
        header("location:/dashboard/customers");

    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView");

    } else if ($userrole == "Partner" || $userrole == "partner") {

        header("location:/dashboard/partnerships");

    }

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

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>