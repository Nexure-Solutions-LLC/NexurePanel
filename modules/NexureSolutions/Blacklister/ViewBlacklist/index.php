<?php

    $pagetitle = "Blacklister";
    $pagesubtitle = "View Blacklist";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/tables/accountTables/index.php');
    
    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

    $accountnumber = $_GET['account_number'] ?? '';

    if (!$accountnumber) {

        header("location: /modules/NexureSolutions/Blacklister/");

        exit;

    }

    $manageAccountDefinitionR = new \NexureSolutions\Generic\VariableDefinitions();

    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

    $blacklisterDefinitionG = new NexureSolutions\Blacklister\BlacklistVariableDefinitions();

    $blacklisterDefinitionG->loadBlacklistEntry($con, $accountnumber);

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Blacklist/Headers/index.php'); ?>
                <div class="caliweb-one-grid">
                    <div class="caliweb-card dashboard-card">
                        <div>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header" style="margin:0; padding:0; margin-bottom:2%;">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <p class="no-padding">Blacklist Details</p>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php echo '<p style="font-size:14px; margin:0; padding:0;">'.nl2br($blacklisterDefinitionG->blacklistDescription).'</p>'; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>