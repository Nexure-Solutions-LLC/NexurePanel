<?php

    $pagetitle = "Blacklister";
    $pagesubtitle = "List Customers";
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
                                    <img src="/assets/img/systemIcons/blacklisterservices.png" alt="Client Logo and/or Business Logo" style="background-color:#fbe7e3;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Blacklister Services</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Customers</h4>
                                </div>
                            </div>
                            <div>
                                <a href="/modules/NexureSolutions/Blacklister/GenerateBlacklist" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create Blank Blacklist</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <?php

                                accountsHomeListingTable(
                                    $con,
                                    "SELECT * FROM nexure_users WHERE userrole <> 'administrator' AND userrole <> 'authorized user'",
                                    ['Company/Account Number', 'Owner', 'Phone', 'Type', 'Status', 'Actions'],
                                    ['accountNumber', 'legalName', 'mobileNumber', 'userrole', 'accountStatus'],
                                    ['23%', '17%', '15%', '15%', '10%', '30%'],
                                    [
                                        'View Blacklists' => "/modules/NexureSolutions/Blacklister/ViewBlacklist/?account_number={accountNumber}",
                                        'Blacklist User' => "/modules/NexureSolutions/Blacklister/GenerateBlacklist/?account_number={accountNumber}"
                                    ]
                                );

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php 

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php'); 

?>