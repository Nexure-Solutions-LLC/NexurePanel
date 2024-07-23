<?php

    $pagetitle = "Client";
    $pagesubtitle = "Web Design Services Managment";
    $pagetype = "Client";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    if (strtolower($currentAccount->role->name) == "customer") {

?>

        <!-- HTML Content will be injected here for customer users view. -->

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between;">
                        <div>
                            <p class="no-padding" style="font-size:16px;">Overview / Cali Web Design Web Development / Home Page</p>
                        </div>
                        <div class="width-25">
                            <select class="form-input with-border-special" name="websiteSelector" id="websiteSelector" style="margin-top:0;">
                                <option>caliwebdesignservices.com</option>
                                <option>website2.com</option>
                                <option>website3.com</option>
                                <option>website4.com</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section" style="padding-bottom:60px;">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid" style="grid-row-gap: 32px;">
                        <div class="caliweb-card dashboard-card" style="padding:30px;">
                            <div class="card-header no-margin">
                                <div class="display-flex">
                                    <div class="image-fluid thumb-web-img width-25">
                                        <img src="https://image.thum.io/get/https://caliwebdesignservices.com" alt="Website Preview" style="width:100%; height:100%;">
                                    </div>
                                    <div class="website-info-content">
                                        <div>
                                            <h4 style="font-size:24px; padding:0; margin:0;">caliwebdesignservices.com</h4>
                                        </div>

                                        <div class="caliweb-horizantal-spacer" style="margin-top:15px; margin-bottom:15px;"></div>

                                        <div>
                                            <p style="margin-bottom:5px;">Front End Languages: HTML, CSS, JS</p>
                                            <p style="margin-bottom:5px;">Back End Languages: PHP</p>
                                            <p style="margin-bottom:5px;">Database System: MySQL (MariaDB)</p>
                                        </div>

                                        <div class="caliweb-horizantal-spacer" style="margin-top:15px; margin-bottom:15px;"></div>

                                        <div>
                                            <p style="margin-bottom:5px;">Setup Date: 01/01/1970</p>
                                            <p style="margin-bottom:5px;">Next Billing Date: 11/30/2038</p>
                                        </div>

                                        <div class="caliweb-horizantal-spacer" style="margin-top:15px; margin-bottom:15px;"></div>

                                        <div class="display-flex align-center">
                                            <p class="display-flex align-center"><img src="/assets/img/systemIcons/synchronize.png" alt="Backups Icon" width="20px" height="20px" style="margin-right:10px;" /><span>Backups Enabled (1 Day Backups)</span><p>
                                            <p class="display-flex align-center" style="margin-left:20px;"><img src="/assets/img/systemIcons/cloudflare.png" alt="Cloudflare Icon" width="20px" height="20px" style="margin-right:10px;" /><span>Cloudflare not configured</span><p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                
                            </div>
                            <div class="card-footer no-margin">
                                <div class="display-flex align-center" style="justify-content:space-between; width:100%;">
                                    <div class="width-50">
                                        <div class="display-flex align-center">
                                            <a href="" class="display-flex align-center"><img src="/assets/img/systemIcons/website-builder.png" alt="Edit Website Icon" width="20px" height="20px" style="margin-right:10px;" /> <span>Edit Website</span></a>
                                            <a href="" class="display-flex align-center" style="margin-left:20px;"><img src="/assets/img/systemIcons/terminal.png" alt="Terminal Icon" width="20px" height="20px" style="margin-right:10px;" /> <span>Terminal</span></a>
                                            <a href="" class="display-flex align-center" style="margin-left:20px;"><img src="/assets/img/systemIcons/folder.png" alt="File Manager Icon" width="20px" height="20px" style="margin-right:10px;" /> <span>File Manager</span></a>
                                            <a href="" class="display-flex align-center" style="margin-left:20px;"><img src="/assets/img/systemIcons/page-speed.png" alt="File Manager Icon" width="20px" height="20px" style="margin-right:10px;" /> <span>Speed Test</span></a>
                                        </div>
                                    </div>
                                    <div class="width-50" style="float:right; text-align:right;">
                                        <div class="display-flex align-center width-100" style="float:right;">
                                            <p class="font-14px width-75">Directory: /var/www/websitedirectory/caliwebdesignservices</p>
                                            <p class="font-14px width-25" style="margin-left:20px;">System IP: 122.133.144.155</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="caliweb-card dashboard-card" style="padding:30px;">
                            <div class="card-header no-margin">
                                <h4 style="font-size:18px; padding:0; margin:0;">Performance and Analytics</h4>
                                <div class="caliweb-horizantal-spacer" style="margin-top:15px; margin-bottom:15px;"></div>
                            </div>
                            <div class="card-body">
                                <div class="caliweb-three-grid" style="grid-column-gap: 32px;">
                                    <div class="caliweb-card dashboard-card account-center-cards">
                                        <div class="card-body">
                                            <p class="font-12px" style="padding-bottom:20px;">Site Sessions</p</div>
                                            <h4 class="text-bold font-size-20 no-padding">5,102</h4>
                                        </div>
                                    </div>
                                    <div class="caliweb-card dashboard-card account-center-cards">
                                        <div class="card-body">
                                            <p class="font-12px" style="padding-bottom:20px;">Actions</p</div>
                                            <h4 class="text-bold font-size-20 no-padding">5,099</h4>
                                        </div>
                                    </div>
                                    <div class="caliweb-card dashboard-card account-center-cards">
                                        <div class="card-body">
                                            <p class="font-12px" style="padding-bottom:20px;">Contact Requests</p</div>
                                            <h4 class="text-bold font-size-20 no-padding">4,673</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer no-margin">

                            </div>
                        </div>
                        <div class="caliweb-card dashboard-card" style="padding:30px;">
                            <p>No additional data.</p>
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

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>