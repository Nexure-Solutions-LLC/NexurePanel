<?php

    // Uninitialized values to prevent page load failure

    $pagetitle = 'Web Design Services Management';
    $pagesubtitle = '';
    $pagetype = '';

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    $accountnumber = $_GET['account_number'] ?? '';

    function setPageTitleAndType($role) {

        $titles = [
            'customer' => 'Client',
            'authorized user' => 'Authorized User',
            'administrator' => 'Administration',
            'partner' => 'Partners',
        ];

        if (isset($titles[strtolower($role)])) {

            return [
                'title' => $titles[strtolower($role)],
                'type' => $titles[strtolower($role)]
            ];

        } else {

            echo "Error: Undefined role. Please contact the system administrator.";
            exit();

        }

    }

    if (isset($currentAccount->role->name)) {

        $pageInfo = setPageTitleAndType($currentAccount->role->name);
        $pagesubtitle = $pageInfo['title'];
        $pagetype = $pageInfo['type'];

    }

    function fetchCustomerAccount($con, $accountnumber) {

        $query = "SELECT * FROM caliweb_users WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'";
        $result = mysqli_query($con, $query);
        $info = mysqli_fetch_array($result);
        mysqli_free_result($result);

        return $info;

    }

    function displayPageTitle($pagetitle, $pagesubtitle) {

        echo '<title>' . $pagetitle . ' - ' . $pagesubtitle . '</title>';

    }

    if (strtolower($currentAccount->role->name) == "customer") {

        $accountnumber = $_GET['account_number'] ?? '';

        if (!$accountnumber) {

            header("location: /dashboard/customers/");
            exit;

        }

        $customerAccountInfo = fetchCustomerAccount($con, $accountnumber);

        if (!$customerAccountInfo) {

            header("location: /dashboard/administration/accounts");
            exit;

        }

        $domainName = "caliwebdesignservices.com";
        displayPageTitle($pagetitle, $pagesubtitle);

?>

        <!-- HTML Content for customer users view -->

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
                                <div class="caliweb-grid caliweb-two-grid" style="grid-template-columns: .6fr 1.5fr; grid-row-gap: 0px;">
                                    <div class="image-fluid thumb-web-img">
                                        <img src="https://image.thum.io/get/https://caliwebdesignservices.com" alt="Website Preview" style="width:100%; height:100%;">
                                    </div>
                                    <div class="website-info-content">
                                        <div class="website-bottom-part">
                                            <div class="caliweb-grid caliweb-four-grid" style="grid-column-gap:20px; grid-row-gap:30px;">
                                                <a href="/modules/webDesignModule/siteBuilder" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/website-builder.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">Edit Website</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">Edit your websites quickly and seamlessly.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/terminal/" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/terminal.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">Terminal</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">Run commands using the command line for your website.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/fileManager" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/folder.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">File Manager</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">Edit your websites files, change permissions or delete files.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/logFiles" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/log-file.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">System Logs</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">View your websites IP, access logs and visitor logs.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/speedTest" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/page-speed.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">Speed Test</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">Review insights into how your websites speed is performing.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/backupServices" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/synchronize.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">Backups</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">We call this the oops zone, Create or restore backups here.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/cloudflareSync" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/cloudflare.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">Cloudflare</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">Manage your Cloudflare services right from our system.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/sslTLSSecurity" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/encrypt.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">SSL/TLS Security</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">This section manages your websites secutity settings.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/monitoringServices" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/monitoring.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">Monitoring</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">This section manages your monitoring service with.</p>
                                                    </div>
                                                </a>
                                                <a href="/modules/webDesignModule/gitHub" style="text-decoration:none;" class="dark-mode-white">
                                                    <div class="caliweb-web-manage-btn">
                                                        <p style="font-weight:700; display:flex; align-items:center;">
                                                            <img src="/assets/img/systemIcons/github.png" style="height:20px; margin-right:10px; width:20px;" /> 
                                                            <span style="font-family: 'Mona Sans', sans-serif;">Manage GitHub</span>
                                                        </p>
                                                        <p style="font-size:12px; margin-top:10px;">Push, Pull and Deploy to GitHub effortlessly.</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                
                            </div>
                            <div class="card-footer" style="margin-top:1%;">
                                <div class="display-flex align-center" style="justify-content:space-between; width:100%;">
                                    <div class="width-50">
                                        <p class="font-14px">Directory: /var/www/vhosts/<?php if (!empty($domainName)) {
                                            $folderName = str_replace('.com', '', $domainName);
                                            echo $folderName;
                                        } ?></p>
                                    </div>
                                    <div class="width-50" style="float:right; text-align:right;">
                                        <p class="font-14px">System IP: <?php echo $_SERVER['SERVER_ADDR']; ?></p>
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
        
        displayPageTitle($pagetitle, $pagesubtitle);
    
?>

    <!-- HTML Content for authorized users view -->

<?php

    } else if (strtolower($currentAccount->role->name) == "administrator") {

        if (!$accountnumber) {

            header("location: /dashboard/administration/accounts/");
            exit;

        }

        $domainName = "caliwebdesignservices.com";

        $customerAccountInfo = fetchCustomerAccount($con, $accountnumber);
        displayPageTitle($pagetitle, $pagesubtitle);

?>


        <!-- HTML Content for administrators view -->

        <section class="section first-dashboard-area-cards" style="padding-top:2%;">
            <div class="container width-98">
                <div class="caliweb-two-grid special-caliweb-spacing" style="grid-template-columns: .6fr 1.5fr; grid-row-gap: 0px;">

                    <div class="caliweb-card dashboard-card">
                        <div class="card-body">                
                            <div class="image-fluid thumb-web-img">
                                <img src="https://image.thum.io/get/https://caliwebdesignservices.com" alt="Website Preview" style="width:100%; height:100%;">
                            </div>
                        </div>
                    </div>
                    <div class="caliweb-card dashboard-card">
                        <div class="card-body">                
                            
                        </div>
                    </div>

                </div>
            </div>
        </section>


<?php

    }

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>
