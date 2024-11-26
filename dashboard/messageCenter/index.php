<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container caliweb-container">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card" style="overflow-y: scroll;">
                        <?php

                            include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/MessageCenter/Sidebars/index.php');

                        ?>
                    </div>
                </div>
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card" style="padding:0;">
                        <div class="caliweb-email-header">
                            <div class="card-header">
                                <div class="display-flex align-center">
                                    <div class="no-padding margin-10px-right icon-size-formatted">
                                        <img src="/assets/img/systemIcons/defaultprofileimage.jpg" alt="Email Profile Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <h4 class="text-bold font-size-20 no-padding" style="margin-top:1%; margin-bottom:10px;">{Legal Name}</h4>
                                        <p style="font-size:12px; font-weight:300;">AKA {Nickname} | Last Active: {Activity Time}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="caliweb-email-body" style="padding:20px;">
                            <div class="card-body">
                                Message Content Here
                            </div>
                            <div class="card-footer"  style="position:fixed; bottom:4%; width:75%;">
                                <input class="form-input" name="messgaeBox" id="messageBox" type="text"  placeholder="Message {Legal Name}"/>
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