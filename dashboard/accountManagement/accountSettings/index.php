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
                    <div class="caliweb-card dashboard-card sidebar-card">
                        <aside class="caliweb-sidebar">
                            <ul class="sidebar-list-linked">
                                <a href="/dashboard/accountManagement/" class="sidebar-link-a"><li class="sidebar-link">Overview</li></a>
                                <a href="/dashboard/accountManagement/accountSettings/" class="sidebar-link-a"><li class="sidebar-link active">Account Settings</li></a>
                                <a href="/dashboard/accountManagement/personalDetails/" class="sidebar-link-a"><li class="sidebar-link">Your Personal Details</a></li></a>
                                <a href="/dashboard/accountManagement/privacyAndSecurity/" class="sidebar-link-a"><li class="sidebar-link">Sign-in & Security</li></a>
                            </ul>
                        </aside>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div>
                            <h3 class="font-size-20 no-padding">Account Settings</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Add html code here -->
                    </div>
                </div>
            </div>
        </div>
    </section>


<?php


?>