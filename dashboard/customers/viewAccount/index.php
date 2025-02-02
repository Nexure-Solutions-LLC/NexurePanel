<?php

    $pagetitle = "Client";
    $pagesubtitle = "Account Overview";
    $pagetype = "Client";

    $accountnumber = (string) $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/tables/accountTables/index.php');

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

        echo 'There are no payment modules available to service this request.';

    }

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

    $storedAccountNumbersString = $currentAccount->accountNumber;

    $storedAccountNumbers = array_map('trim', explode(',', $storedAccountNumbersString));

    if (in_array($accountnumber, $storedAccountNumbers)) {

        $truncatedAccountNumber = substr($accountnumber, -4);

        $customerStatus = $currentAccount->accountStatus;

        $manageAccountDefinitionR = new \NexureSolutions\Generic\VariableDefinitions();
        $manageAccountDefinitionR->manageAccount($con, $storedAccountNumbersString);

        $businessname = ($manageAccountDefinitionR->businessname !== null) ? $manageAccountDefinitionR->businessname : null;

        $isRestricted = strtolower($customerStatus->name) == "restricted";

        $stripeID = $currentAccount->stripe_id;

        $balance = $isRestricted ? '——' : '$'.getCreditBalance($stripeID);

        $dueDate = $isRestricted ? '——' : 'July 12, 2024';

?>

        <section class="first-dashboard-area-cards">

            <section class="section caliweb-section customer-dashboard-greeting-section">
                <div class="container caliweb-container">
                    <div class="display-flex align-center" style="justify-content:space-between">
                        <p class="no-padding" style="font-size:16px;">Overview / Nexure View Account / Details</p>
                        <a href="javascript:void(0)" class="caliweb-button secondary" onclick="openPaymentModal()">Pay Balance</a>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid" style="grid-row-gap: 32px;">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card" style="padding:0;">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding:20px; border:0;">
                                    <div class="display-flex align-top">
                                        <div>
                                            <p style="font-size:14px; font-weight:600;">
                                                <?php echo $variableDefinitionX->orgShortName; ?> Standard
                                                (...<?php echo $truncatedAccountNumber; ?>)
                                            </p>
                                            <p style="font-size:12px; margin-top:5px;">
                                                <?php
                                                echo strtoupper($businessname !== null ? $businessname : $fullname);
                                                ?>
                                            </p>
                                        </div>
                                        <span style="padding-left:15px; padding-right:15px;">|</span>
                                        <div>
                                            <a class="display-flex align-center careers-link" style="text-decoration:none;"
                                            href="javascript:void(0);" onclick="openModal()">
                                                <span style="padding-right:5px;">See full account number</span>
                                                <span class="lnr lnr-chevron-right"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <?php

                                    if (strtolower($currentAccount->accountStatus->name) == "restricted") {

                                        echo '

                                            <div class="account-status-banner '.strtolower($currentAccount->accountStatus->name) == "restricted" ? 'id="accountRestricted"' : ''.'">
                                                <p style="font-size:14px;">We have restricted this account and reopened it to protect your services. If you have any questions, please contact us.</p>
                                                
                                            </div>

                                        ';
                                    
                                    }

                                ?>

                                <div class="card-body">
                                    <div class="caliweb-three-grid" style="padding:20px;">
                                        <div class="customer-balance">
                                            <h5 style="font-weight:300; font-size:40px;" class="no-padding no-margin">
                                                <?php echo strtolower($currentAccount->accountStatus->name) == "restricted" ? '——' : '$'.getCreditBalance($stripeID); ?>
                                            </h5>
                                            <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Owed Balance</p>
                                        </div>
                                        <div class="customer-duedate" style="margin-top:4%;">
                                            <h5 style="font-weight:300; font-size:18px;" class="no-padding no-margin">
                                                <?php echo strtolower($currentAccount->accountStatus->name) == "restricted" ? '——' : 'July 12, 2024'; ?>
                                            </h5>
                                            <p style="font-size:12px; padding-top:5px;" class="no-padding no-margin">Due Date</p>
                                        </div>
                                        <div class="customer-duedate" style="margin-top:3.5%;">
                                            <h5 style="font-weight:300; font-size:18px;" class="no-padding no-margin">
                                                <span class="account-status-badge <?php echo $currentAccount->transformStringToStatusColor($currentAccount->fromAccountStatus($customerStatus))->value; ?>"
                                                    style="margin-left:0;"><?php echo $currentAccount->fromAccountStatus($customerStatus); ?></span>
                                            </h5>
                                            <p style="font-size:12px; padding-top:10px;" class="no-padding no-margin">Account Standing</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section caliweb-section customer-dashboard-section">
                <div class="container caliweb-container">
                    <div class="caliweb-one-grid">
                        <div class="accounts-overview">
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header no-padding no-margin customer-card-header" style="padding-top:20px; padding-bottom:20px; border:0;">
                                    <h6 class="no-padding" style="font-size:16px; font-weight:600; font-family: 'IBM Plex Sans', sans-serif;">
                                        <?php echo $LANG_CUSTOMER_SERVICES_TITLE_TEXT; ?>
                                    </h6>
                                </div>
                                <div class="card-body" style="padding-top:20px; padding-bottom:20px; margin-top:20px;">

                                        <?php

                                            echo '<style>table {margin-top:0!important;}</style>';

                                            accountsManageListingTable(
                                                $con,
                                                "SELECT * FROM nexure_services WHERE accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'",
                                                ['Service ID', 'Service Name', 'Type', 'Started', 'Renewal', 'Cost', 'Status', 'Actions'],
                                                ['serviceIdentifier', 'serviceName', 'serviceType', 'serviceStartDate', 'serviceEndDate', 'serviceCost', 'serviceStatus'],
                                                ['15%', '20%', '15%', '12%', '12%', '8%', '8%'],
                                                [
                                                    'View' => "{linkedServiceName}/?account_number={accountNumber}",
                                                    'Edit' => "/dashboard/administration/accounts/editServices/?account_number={accountNumber}&service_id={serviceIdentifier}",
                                                    'Delete' => "/dashboard/administration/accounts/deleteServices/?account_number={accountNumber}&service_id={serviceIdentifier}"
                                                ]
                                            );

                                        ?>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </section>

        <div id="accountModal" class="modal">
            <div class="modal-content">
                <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Full Account Number</h6>
                <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">Full Account Number: <?php echo $accountnumber; ?></p>
                <p style="font-size:14px; padding-bottom:30px;">This account number will be used to identify your account. Keep this number safe.</p>
                <div style="display:flex; align-items:right; justify-content:right;">
                    <button class="caliweb-button primary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>

        <div id="paybalanceModal" class="modal">
            <div class="modal-content">
                <form method="POST" action="/dashboard/customers/viewAccount/makePayment/?account_number=<?php echo $accountnumber; ?>">
                    <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Pay account balance?</h6>
                    <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                        <div class="form-control">
                            <input class="form-input" id="balanceNumber" type="numeric" maxlenghth="10" name="balanceNumber" style="width:25%;" placeholder="65.00" />
                        </div>
                    </div>
                    <p style="font-size:14px; padding-bottom:10px;">Please specify how much you would like to pay. Payments will post immediatly, you may need to refresh the page if the balance does not update.</p>
                    <p style="font-size:14px; padding-bottom:10px;">NOTE: You can make a payment larger than the balance if you want to prepay for services.</p>
                    <p style="font-size:14px; padding-bottom:30px;">Please do not include the currency, simply type the numeric value.</p>
                    <div style="display:flex; align-items:right; justify-content:right;">
                        <button class="caliweb-button primary" type="submit" name="submit">Submit Payment</button>
                        <button class="caliweb-button secondary" onclick="closePaymentModal()">Close</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            var modal = document.getElementById("accountModal");
            var modalPayBalance = document.getElementById("paybalanceModal");

            function openModal() {
                modal.style.display = "block";
            }

            function closeModal() {
                modal.style.display = "none";
            }

            function openPaymentModal() {
                modalPayBalance.style.display = "block";
            }

            function closePaymentModal() {
                modalPayBalance.style.display = "none";
            }

        </script>

<?php

    } else {

        header("location: /dashboard/customers");
        
    }

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>
