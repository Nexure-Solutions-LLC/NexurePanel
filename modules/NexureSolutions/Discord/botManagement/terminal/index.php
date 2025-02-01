<?php

    $pagetitle = "Client";
    $pagesubtitle = "Web Design Services Managment";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    if (strtolower($currentAccount->role->name) == "customer") {

?>

        <!-- HTML Content will be injected here for customer users view. -->

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between;">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Overview / Nexure Bot Hosting / Terminal</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section" style="padding-bottom:60px;">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid" style="grid-row-gap: 32px;">
                        <div class="caliweb-card dashboard-card" style="padding:20px; background-color:#000 !important;">
                            <div class="card-body">
                                <iframe src="/modules/NexureSolutions/Discord/botManagement/terminal/terminalLogic/index.php" style="padding:0; margin:0; border:0; width:100%; height:45vh;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </section>


<?php

    } else if (strtolower($currentAccount->role->name) == "authorized user") {

?>

        <!-- HTML Content will be injected here for authorized users view. -->


<?php

    } else if (strtolower($currentAccount->role->name) == "administrator") {

?>

        <!-- HTML Content will be injected here for admin users view. -->

<?php

    } else if (strtolower($currentAccount->role->name) == "partner") {

?>

        <!-- HTML Content will be injected here for partners view. -->

<?php

    }

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>