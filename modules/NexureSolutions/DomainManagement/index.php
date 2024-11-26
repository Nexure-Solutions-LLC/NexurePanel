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
                <?php include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/DomainManagement/Backend/Headers/index.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">

                            <div class="caliweb-three-grid" style="grid-column-gap: 32px; grid-row-gap: 32px;">
                                <div class="caliweb-card dashboard-card account-center-cards">
                                    <div class="card-body">
                                        <p class="font-12px" style="padding-bottom:20px;">Renewal Date</p>
                                        <h4 class="text-bold font-size-20 no-padding">September 26 2025</h4>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card account-center-cards">
                                    <div class="card-body">
                                        <p class="font-12px" style="padding-bottom:20px;">Registrar</p>
                                        <h4 class="text-bold font-size-20 no-padding">Godaddy (Partner)</h4>
                                    </div>
                                </div>
                                <div class="caliweb-card dashboard-card account-center-cards">
                                    <div class="card-body">
                                        <p class="font-12px" style="padding-bottom:20px;">Transfer Status</p>
                                        <h4 class="text-bold font-size-20 no-padding">Locked (Provider)</h4>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="caliweb-card dashboard-card" style="margin-top:1%;">
                            <?php include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/DomainManagement/Backend/Menus/index.php'); ?>
                            <div class="website-info-content">
                                <div class="website-bottom-part">
                                    <div class="caliweb-grid caliweb-three-grid" style="grid-column-gap:20px; grid-row-gap:30px;">
                                        
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