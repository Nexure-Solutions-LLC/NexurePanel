<?php

    $pagetitle = "Unified Recovery";
    $pagesubtitle = "System Services";
    $pagetype = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/tables/accountTables/index.php');

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content: space-between;">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/systemIcons/serviceabilityrecovery.png" alt="Client Logo and/or Business Logo" style="background-color:#f5e7fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Unified Recovery</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Query Hosts</h4>
                                </div>
                            </div>
                            <div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php 

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php'); 

?>