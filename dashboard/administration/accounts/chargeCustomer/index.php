<?php

    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = $pagetitle = "Customer Accounts";
    $_SESSION['pagesubtitle'] = $pagesubtitle = "Alter Balances";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

        if ($variableDefinitionX->paymentProcessorName == "Stripe") {

            include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

        echo 'There are no payment modules available to service this request.';

    }

    $accountnumber = $_GET['account_number'];

    if ($accountnumber == "") {

        header("location: /dashboard/administration/accounts");

    }

    $manageAccountDefinitionR = new \CaliWebDesign\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

    $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".$accountnumber."'");

    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);

    mysqli_free_result($customerAccountQuery);

    if (!$customerAccountInfo) {

        header("location: /dashboard/administration/accounts");
        exit();

    }

    $sql = "SELECT * FROM caliweb_notetypes";

    $result = $con->query($sql);

    $options = '';

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $type = htmlspecialchars($row['type']);
            $preset = htmlspecialchars($row['preset']);
            $options .= '<option data-preset="' . $preset . '">' . $type . '</option>';

        }

    } else {

        $options = '';

    }

    $options .= '<option data-preset="' . "" . '">' . "Other" . '</option>';

    if ($customerAccountInfo != NULL) {

        // When form submitted, insert values into the database.

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $amount = filter_input(INPUT_POST, 'chargeAmount', FILTER_VALIDATE_FLOAT);

            $message = chargeCustomer($manageAccountDefinitionR->customerStripeID, $amount);

            if ($message == "Success") {

                header("location:/dashboard/administration/accounts/manageAccount?account_number=" . $accountnumber);

            } else {

                header("Location: /error/genericSystemError");

            }

        }

        echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

        <style>
            input[type=number] {
                -moz-appearance:textfield;
            }

            input[type=number]::-webkit-outer-spin-button,
            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            textarea {
                resize: none;
            }
        </style>

        <section class="section first-dashboard-area-cards">
            <div class="container width-98">
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <form method="POST" action="" id="caliwebdesign-panel-form">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content: space-between;">
                                    <div class="display-flex align-center">
                                        <div class="no-padding margin-10px-right icon-size-formatted">
                                            <img src="/assets/img/systemIcons/chargeicon.png" alt="Alter Balance Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                        </div>
                                        <div>
                                            <p class="no-padding font-14px">Accounts</p>
                                            <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Charge Account</h4>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                        <a href="/dashboard/administration/accounts/chargeCustomer/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                        <a href="/dashboard/administration/accounts/manageAccount/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="fillable-section-holder" style="margin-top:-3% !important;">
                                    <div class="fillable-header">
                                        <p class="fillable-text">Charge Information</p>
                                    </div>
                                    <div class="fillable-body">
                                        <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important; margin-bottom:4%;">
                                            <div class="form-left-side" style="width:80%;">
                                                <div class="form-control" style="margin-top:20px;">
                                                    <label for="balance">New Charge</label>
                                                    <input type="number" maxlength="8" name="chargeAmount" id="chargeAmount" class="form-input" style="min-width: 500px;" inputmode="numeric" step="0.01" placeholder="340.28" />

                                                    <p style="font-size:14px; margin-top:6%;">NOTICE: When entering charge amounts do not include the currency symbol. Stripe automatically handles the currency.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

<?php

        include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

    } else {

        // this used to return genericSystemError however its more fit for a redirection as it is
        // an invalid account number

        header("location: /dashboard/administration/accounts");

    }

?>