<?php

    $pagetitle = "Client";
    $pagesubtitle = "Account Overview";
    $pagetype = "Client";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    $storedAccountNumber = $currentAccount->accountNumber;

    if ($accountnumber == $storedAccountNumber) {

        $websiteresult = mysqli_query($con, "SELECT * FROM caliweb_websites WHERE email = '$caliemail'");
        $websiteinfo = mysqli_fetch_array($websiteresult);

        $customerStatus = $userinfo['accountStatus'];

?>

            <section class="first-dashboard-area-cards">

                <section class="section caliweb-section customer-dashboard-greeting-section">
                    <div class="container caliweb-container">
                        <p class="no-padding" style="font-size:16px;">Overview / Cali Web Design Standard</p>
                    </div>
                </section>


                <section class="section caliweb-section customer-dashboard-section">
                    <div class="container caliweb-container">
                        <div class="caliweb-one-grid" style="grid-row-gap: 32px;">
                            <div class="accounts-overview">
                                <div class="caliweb-card dashboard-card" style="padding:0;">
                                    <div class="card-header no-padding no-margin customer-card-header" style="padding:20px;">
                                        <?php
                                            if (mysqli_num_rows($websiteresult) > 0)  {
                                                mysqli_free_result($websiteresult);
                                                $websiteDomainName = $websiteinfo['domainName'];
                                                $websitePlan = $websiteinfo['servicePlan'];
                                                $websiteRoot = $websiteinfo['websiteDIR'];
                                                $websiteType = $websiteinfo['websiteType'];

                                                $frontendlangType = $websiteinfo['fontendFramework'];
                                                $backendlangType = $websiteinfo['backendLang'];
                                                $databaseType = $websiteinfo['databaseSystem'];

                                                $setupdate = $websiteinfo['setupDate'];
                                                $setupdateformatted = new DateTime($setupdate);
                                                $setupdateformattedfinal = $setupdateformatted->format('F j, Y g:i A');

                                                echo '
                                                    <div id="full-card-area-container" class="display-flex width-100">
                                                        <div id="image-container" class="thumb-web-img">
                                                            <img src="https://image.thum.io/get/https://'.$websiteDomainName.'" alt="Website Preview" style="width: 100%; height: auto;">
                                                        </div>
                                                        <div class="display-flex width-100" style="justify-content:space-between !important;">
                                                            <div id="text-container" style="width:55%;">
                                                                <div class="caliweb-one-grid" style="grid-row-gap: 24px;">
                                                                    <div>
                                                                        <p class="no-padding no-padding font-14px subtitle-text">DEPLOYMENT</p>
                                                                        <p class="no-padding no-padding font-14px text-bold" style="padding-top:1%;">https://'.$websiteDomainName.'</p>
                                                                    </div>
                                                                    <div class="caliweb-three-grid" style="grid-template-columns: .3fr 1.2fr .5fr;">
                                                                        <div>
                                                                            <p class="no-padding no-padding font-14px subtitle-text">TYPE</p>
                                                                            <p class="no-padding no-padding font-14px text-bold" style="padding-top:5px;">'.$websiteType.'</p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="no-padding no-padding font-14px subtitle-text">PLAN</p>
                                                                            <p class="no-padding no-padding font-14px text-bold" style="padding-top:5px;">'.$websitePlan.'</p>
                                                                        </div>
                                                                        <div>
                                                                            <p class="no-padding no-padding font-14px subtitle-text">HOME DIRECTORY</p>
                                                                            <p class="no-padding no-padding font-14px text-bold" style="padding-top:5px;">'.$websiteRoot.'</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="">
                                                                        <p class="no-padding no-padding font-14px subtitle-text">SETUP DATE</p>
                                                                        <p class="no-padding no-padding font-14px text-bold" style="padding-top:5px;">'.$setupdateformattedfinal.'</p>
                                                                    </div>
                                                                    <div class="">
                                                                        <p class="no-padding no-padding font-14px subtitle-text">LANGUAGE STACK</p>
                                                                        <p class="no-padding no-padding font-14px text-bold" style="padding-top:5px;">Front End is '.$frontendlangType.', Backend is '.$backendlangType.', Database is '.$databaseType.'</p>
                                                                    </div>
                                                                    <div class="">
                                                                        <p class="no-padding no-padding font-14px subtitle-text">REPOSITORY</p>
                                                                        <p class="no-padding no-padding font-14px text-bold" style="padding-top:5px;">--</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="buttons-container">
                                                                <div class="display-flex align-center">
                                                                    <a href="" class="caliweb-button primary">Edit Account</a>
                                                                    <a href="" class="caliweb-button secondary">Close Account</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ';
                                            } else {
                                                echo '
                                                    <h6 class="no-padding" style="font-size:16px; font-weight:600;">
                                                        e
                                                    </h6>
                                                ';
                                            }
                                        ?>
                                    </div>
                                    <div class="card-body">

                                    </div>
                                </div>
                            </div>
                            <div class="accounts-overview" style="margin-bottom:2%;">
                                <div class="caliweb-card dashboard-card" style="padding:0;">
                                    <div class="card-header no-padding no-margin customer-card-header" style="padding:20px;">
                                        <iframe id="shellinabox" style="border:0; outline:none; height:35vh;" class="width-100" src="https://<?php echo $_SERVER['HTTP_HOST'] ?>/terminal"></iframe>
                                    </div>
                                    <div class="card-body">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </section>

<?php

    } else {

        echo '<script type="text/javascript">window.location = "/dashboard/customers/viewAccount/viewByService/?account_number='.$storedAccountNumber.'"</script>';

    }

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>