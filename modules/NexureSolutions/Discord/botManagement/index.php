<?php

    // Uninitialized values to prevent page load failure

    $pagetitle = '';
    $pagesubtitle = 'Discord Hosting Services Management';
    $pagetype = '';

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    $accountnumber = $_GET['account_number'] ?? '';

    // Ensure account number is sanitized and validated

    if (empty($accountnumber) || !preg_match('/^\d+$/', $accountnumber)) {

        header("Location: /dashboard/customers/");

        exit;
    }

    $accountnumber = $accountnumber;

    $role = $currentAccount->role->name;

    function setPageTitleAndType($role)
    {

        $titles = [
            'customer' => 'Client',
            'authorized user' => 'Authorized User',
            'administrator' => 'Administration',
            'partner' => 'Partners',
        ];

        $roleLower = strtolower($role);

        if (isset($titles[$roleLower])) {

            return [
                'title' => $titles[$roleLower],
                'type' => $titles[$roleLower]
            ];
        } else {

            header("Location: /error/genericSystemError/");

            exit();
        }
    }

    function fetchCustomerAccount($con, $accountnumber)
    {

        $query = "SELECT * FROM nexure_users WHERE FIND_IN_SET('$accountnumber', accountNumber) > 0";

        $result = mysqli_query($con, $query);

        $info = [];

        while ($row = mysqli_fetch_array($result)) {

            $info[] = $row;
        }

        mysqli_free_result($result);

        return $info;
    }

    function displayPageTitle($pagetitle, $pagesubtitle)
    {

        echo '<title>' . htmlspecialchars($pagetitle, ENT_QUOTES, 'UTF-8') . ' | ' . htmlspecialchars($pagesubtitle, ENT_QUOTES, 'UTF-8') . '</title>';
    }

    if (strtolower($currentAccount->role->name) == "customer") {

        $customerAccountInfo = fetchCustomerAccount($con, $accountnumber);

        if (!$customerAccountInfo) {

            header("location: /dashboard/customers/");

            exit;
        }

        if (isset($currentAccount->role->name)) {

            $pageInfo = setPageTitleAndType($currentAccount->role->name);
            $pagetitle = $pageInfo['title'];

            $pagesubtitle =  $pagesubtitle;

            $pagetype = $pageInfo['type'];
        }

        $domainName = "NexureSolutionsservices.com";

        displayPageTitle($pagetitle, $pagesubtitle);

        if (in_array($pagetitle, $clientPages) || (isset($pagesubtitle) && $pagesubtitle == "Client") || $pagetype == "Client") {

            echo '<link href="/assets/css/client-dashboard-css-2024.css" rel="stylesheet" type="text/css" />';
        }

?>

    <!-- HTML Content for customer users view -->

    <section class="first-dashboard-area-cards">

        <section class="section caliweb-section customer-dashboard-greeting-section">
            <div class="container caliweb-container">
                <div class="display-flex align-center" style="justify-content:space-between;">
                    <div>
                        <p class="no-padding" style="font-size:16px;">Overview / Cali Web Design Bot Hosting / Home Page</p>
                    </div>
                    <div class="width-25">
                        <select class="form-input with-border-special" name="discordbotselector" id="discordbotselector" style="margin-top:0;">
                            <option>Discord Bot 1</option>
                            <option>Discord Bot 2</option>
                            <option>Discord Bot 3</option>
                            <option>Discord Bot 4</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section class="section caliweb-section customer-dashboard-section" style="padding-bottom:60px;">
            <div class="container caliweb-container">
                <div class="caliweb-one-grid" style="grid-row-gap: 32px;">
                    <div class="caliweb-card dashboard-card" style="padding:30px;">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div class="title">
                                <h4 class="font-size-20" style="padding:0; margin:0;">Discord Bot 1</h4>
                            </div>
                            <div class="actionButtons">
                                <div class="display-flex align-center">
                                    <div>
                                        <button class="caliweb-button secondary" onclick="" name="" type="">Start</button>
                                        <button class="caliweb-button secondary" style="margin-left:10px;" onclick="" name="" type="">Stop</button>
                                        <button class="caliweb-button secondary" style="margin-left:10px;" onclick="" name="" type="">Restart</button>
                                        <button class="caliweb-button secondary red" style="margin-left:10px;" onclick="" name="" type="">Destroy</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="caliweb-grid caliweb-two-grid" style="grid-template-columns: 1.5fr .5fr; grid-row-gap: 0px; grid-column-gap:40px;">
                            <div class="botOutputfromDocker">
                                <p>This bot is not started.</p>
                            </div>
                            <div class="website-info-content" style="margin-top:10%;">
                                <div class="website-bottom-part">
                                    <div class="caliweb-grid caliweb-one-grid" style="grid-column-gap:20px; grid-row-gap:20px;">
                                        <a href="/modules/NexureSolutions/Discord/botManagement/terminal/" style="text-decoration:none;" class="dark-mode-white">
                                            <div class="caliweb-web-manage-btn">
                                                <p style="font-weight:700; display:flex; align-items:center;">
                                                    <img src="/assets/img/systemIcons/terminal.png" style="height:20px; margin-right:10px; width:20px;" />
                                                    <span style="font-family: 'Mona Sans', sans-serif;">Terminal</span>
                                                </p>
                                                <p style="font-size:12px; margin-top:10px;">Run commands using the command line for your bot.</p>
                                            </div>
                                        </a>
                                        <a href="/modules/NexureSolutions/Discord/botManagement/fileManager" style="text-decoration:none;" class="dark-mode-white">
                                            <div class="caliweb-web-manage-btn">
                                                <p style="font-weight:700; display:flex; align-items:center;">
                                                    <img src="/assets/img/systemIcons/folder.png" style="height:20px; margin-right:10px; width:20px;" />
                                                    <span style="font-family: 'Mona Sans', sans-serif;">File Manager</span>
                                                </p>
                                                <p style="font-size:12px; margin-top:10px;">Edit your bots files, change permissions or delete files.</p>
                                            </div>
                                        </a>
                                        <a href="/modules/NexureSolutions/Discord/botManagement/gitHub" style="text-decoration:none;" class="dark-mode-white">
                                            <div class="caliweb-web-manage-btn">
                                                <p style="font-weight:700; display:flex; align-items:center;">
                                                    <img src="/assets/img/systemIcons/github.png" style="height:20px; margin-right:10px; width:20px;" />
                                                    <span style="font-family: 'Mona Sans', sans-serif;">Manage GitHub</span>
                                                </p>
                                                <p style="font-size:12px; margin-top:10px;">Push from and Pull or Deploy to GitHub effortlessly.</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="caliweb-card dashboard-card" style="padding:30px;">
                        <div class="card-header no-padding no-margin customer-card-header" style="padding-top:20px; padding-bottom:20px; border:0;">
                            <h6 class="no-padding" style="font-size:16px; font-weight:600;">
                                <?php echo 'Configuration'; ?>
                            </h6>
                        </div>
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

        $domainName = "NexureSolutionsservices.com";

        $customerAccountInfo = fetchCustomerAccount($con, $accountnumber);
        displayPageTitle($pagetitle, $pagesubtitle);

?>


    <!-- HTML Content for administrators view -->

    <!-- HTML Content for administrators view -->

    <section class="section first-dashboard-area-cards" style="padding-top:2%;">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Discord/botManagement/Backend/Headers/index.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">

                            <div class="caliweb-three-grid" style="grid-column-gap: 32px; grid-row-gap: 32px;">
                                <div class="caliweb-card dashboard-card account-center-cards">
                                    <div class="card-body">
                                        <p class="font-12px" style="padding-bottom:20px;">CPU Usage</p>
                                        <h4 class="text-bold font-size-20 no-padding">20%</h4>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card account-center-cards">
                                    <div class="card-body">
                                        <p class="font-12px" style="padding-bottom:20px;">Memory Usage</p>
                                        <h4 class="text-bold font-size-20 no-padding">536 MB</h4>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card account-center-cards">
                                    <div class="card-body">
                                        <p class="font-12px" style="padding-bottom:20px;">Storage Usage</p>
                                        <h4 class="text-bold font-size-20 no-padding">89.33 MB</h4>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="caliweb-card dashboard-card" style="margin-top:1%;">
                            <?php include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Discord/botManagement/Backend/Menus/index.php'); ?>
                            <div class="website-info-content">
                                <div class="website-bottom-part">
                                    <div class="caliweb-grid caliweb-three-grid" style="grid-column-gap:20px; grid-row-gap:30px;">
                                        <a href="/modules/NexureSolutions/Discord/botManagement/terminal/" style="text-decoration:none;" class="dark-mode-white">
                                            <div class="caliweb-web-manage-btn">
                                                <p style="font-weight:700; display:flex; align-items:center;">
                                                    <img src="/assets/img/systemIcons/terminal.png" style="height:20px; margin-right:10px; width:20px;" />
                                                    <span style="font-family: 'Mona Sans', sans-serif;">Terminal</span>
                                                </p>
                                                <p style="font-size:12px; margin-top:10px;">Run commands using the command line for your bot.</p>
                                            </div>
                                        </a>
                                        <a href="/modules/NexureSolutions/Discord/botManagement/fileManager" style="text-decoration:none;" class="dark-mode-white">
                                            <div class="caliweb-web-manage-btn">
                                                <p style="font-weight:700; display:flex; align-items:center;">
                                                    <img src="/assets/img/systemIcons/folder.png" style="height:20px; margin-right:10px; width:20px;" />
                                                    <span style="font-family: 'Mona Sans', sans-serif;">File Manager</span>
                                                </p>
                                                <p style="font-size:12px; margin-top:10px;">Edit your bots files, change permissions or delete files.</p>
                                            </div>
                                        </a>
                                        <a href="/modules/NexureSolutions/Discord/botManagement/gitHub" style="text-decoration:none;" class="dark-mode-white">
                                            <div class="caliweb-web-manage-btn">
                                                <p style="font-weight:700; display:flex; align-items:center;">
                                                    <img src="/assets/img/systemIcons/github.png" style="height:20px; margin-right:10px; width:20px;" />
                                                    <span style="font-family: 'Mona Sans', sans-serif;">Manage GitHub</span>
                                                </p>
                                                <p style="font-size:12px; margin-top:10px;">Push from and Pull or Deploy to GitHub effortlessly.</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <div class="image-fluid thumb-web-img" style="margin-right:0; height:unset; width:100%;">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



<?php

    }

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>
