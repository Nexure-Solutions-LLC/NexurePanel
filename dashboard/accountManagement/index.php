<?php

    // ===================== DO NOT TOUCH BELOW THIS LINE IT CONTAINS CORE FUNCTIONS ===================

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    $caliemail = $_SESSION['caliid'];

    $userprofileresult = mysqli_query($con, "SELECT * FROM caliweb_users WHERE email = '$caliemail'");
    $userinfo = mysqli_fetch_array($userprofileresult);
    mysqli_free_result($userprofileresult);

    // User Profile Variable Definitions

    $userrole = strtolower($userinfo['userrole']);

    // Role-to-title mapping

    $roleTitles = [
        'customer' => 'Account Management - Customer',
        'authorized user' => 'Account Management - Authorized User',
        'administrator' => 'Account Management - Administrator',
        'partner' => 'Account Management - Partners'
    ];

    // Default title if role not found

    $pagetitle = isset($roleTitles[$userrole]) ? $roleTitles[$userrole] : 'Account Management';
    $pagesubtitle = 'General';

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    // ===================== DO NOT TOUCH ABOVE THIS LINE IT CONTAINS CORE FUNCTIONS ===================

?>

    <section class="section first-dashboard-area-cards">
        <div class="container caliweb-container">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <a href="/dashboard/accountManagement/" class="sidebar-link-a"><li class="sidebar-link active">Overview</li></a>
                                <a href="/dashboard/accountManagement/AccountSettings/" class="sidebar-link-a"><li class="sidebar-link">Account Settings</li></a>
                                <a href="/dashboard/PersonalDetails/" class="sidebar-link-a"><li class="sidebar-link">Your Personal Details</a></li></a>
                                <a href="/dashboard/Signin/" class="sidebar-link-a"><li class="sidebar-link">Sign-in & Security</li></a>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-two-grid special-caliweb-spacing">

                    <div class="caliweb-card dashboard-card">
                        <div class="card-body">                
                            <h4 class="text-bold font-size-20 no-padding"><?php echo $LANG_ACCOUNTCENTER_CARD_ACCOUNTSETTINGS_TITLE; ?></h4>
                            <p class="font-12px" style="padding-top:20px;"><?php echo $LANG_ACCOUNTCENTER_CARD_ACCOUNTSETTINGS_TEXT; ?></p</div>
                        </div>
                        <div style="margin-top:20px;">
                            <a href="" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center"><?php echo $LANG_ACCOUNTCENTER_CARD_ACCOUNTSETTINGS_LINKTEXT; ?> <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                        </div>
                    </div>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-body">                
                            <h4 class="text-bold font-size-20 no-padding"><?php echo $LANG_ACCOUNTCENTER_CARD_PAYMENTSETTINGS_TITLE; ?></h4>
                            <p class="font-12px" style="padding-top:20px;"><?php echo $LANG_ACCOUNTCENTER_CARD_PAYMENTSETTINGS_TEXT; ?></p</div>
                        </div>
                        <div style="margin-top:20px;">
                            <a href="" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center"><?php echo $LANG_ACCOUNTCENTER_CARD_PAYMENTSETTINGS_LINKTEXT; ?> <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                        </div>
                    </div>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-body">                
                            <h4 class="text-bold font-size-20 no-padding"><?php echo $LANG_ACCOUNTCENTER_CARD_PERSONALDETAILS_TITLE; ?></h4>
                            <p class="font-12px" style="padding-top:20px;"><?php echo $LANG_ACCOUNTCENTER_CARD_PERSONALDETAILS_TEXT; ?></p</div>
                        </div>
                        <div style="margin-top:20px;">
                            <a href="" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center"><?php echo $LANG_ACCOUNTCENTER_CARD_PERSONALDETAILS_LINKTEXT; ?> <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                        </div>
                    </div>

                    <div class="caliweb-card dashboard-card">
                        <div class="card-body">          
                            <h4 class="text-bold font-size-20 no-padding"><?php echo $LANG_ACCOUNTCENTER_CARD_PRIVACYANDSECURITY_TITLE; ?></h4>
                            <p class="font-12px" style="padding-top:20px;"><?php echo $LANG_ACCOUNTCENTER_CARD_PRIVACYANDSECURITY_TEXT; ?></p</div>      
                        </div>
                        <div style="margin-top:20px;">
                            <a href="" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center"><?php echo $LANG_ACCOUNTCENTER_CARD_PRIVACYANDSECURITY_LINKTEXT; ?> <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>