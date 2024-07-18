<?php

    unset($_SESSION['pagetitle']);
    $pagetitle = "Services";
    $pagesubtitle = "Create Order";
    $_SESSION['pagetitle'] = "Order Services as Staff";

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    $accountnumber = $_GET['account_number'];

    if ($accountnumber == "") {

        header("location: /dashboard/administration/accounts");

    } else {

        $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".$accountnumber."'");
        $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
        mysqli_free_result($customerAccountQuery);

        if ($customerAccountInfo != NULL) {

            // Get the menu option listing for the services.

            $sql = "SELECT * FROM caliweb_available_purchasables WHERE serviceOrProductStatus = 'Active'";

            $result = $con->query($sql);

            $options = '';

            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    $productName = htmlspecialchars($row['serviceOrProductName']);
                    $productPrice = htmlspecialchars($row['serviceOrProductPrice']);
                    $options .= '<option data-price="' . $productPrice . '">' . $productName . '</option>';
                }

            } else {

                $options = '';

            }

            // Get the menu option listing for payment methods.

            $proccessorResult = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig");
            $proccessorInfo = mysqli_fetch_array($proccessorResult);
            mysqli_free_result($proccessorResult);

            $paymentProccessorName = $proccessorInfo['processorName'];

            if ($paymentProccessorName == "Stripe") {

                require ($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/index.php');

            }

            // When form submitted, insert values into the database.

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                // Service Info Feilds

                $purchasableItem = stripslashes($_REQUEST['purchasable']);
                $purchasableType = stripslashes($_REQUEST['type']);
                $serviceStatus = stripslashes($_REQUEST['service_status']);
                $paymentMethodFormFeild = stripslashes($_REQUEST['payment_method']);
                $amountPrice = stripslashes($_REQUEST['amount']);
                $endDate = stripslashes($_REQUEST['end_date']);

                // System Feilds

                $orderdate = date("Y-m-d H:i:s");
                $accountnumber = $accountnumber;

                $proccessorResult = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig");
                $proccessorInfo = mysqli_fetch_array($proccessorResult);
                mysqli_free_result($proccessorResult);

                $paymentProccessorName = $proccessorInfo['processorName'];

                if ($paymentProccessorName == "Stripe") {

                    require ($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/internalPayments/index.php');

                }

            } else {

                include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

                $lowerrole = strtolower($userrole);
                switch ($lowerrole) {
                    case "authorized user":
                        header("location:/dashboard/customers/authorizedUserView");
                        break;
                    case "partner":
                        header("location:/dashboard/partnerships");
                        break;
                    case "customer":
                        header("location:/dashboard/customers");
                        break;
                }

                echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

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
                </style>
                <section class="section first-dashboard-area-cards">
                    <div class="container width-98">
                        <div class="caliweb-one-grid special-caliweb-spacing">
                            <div class="caliweb-card dashboard-card">
                                <form method="POST" action="">
                                    <div class="card-header">
                                        <div class="display-flex align-center" style="justify-content: space-between;">
                                            <div class="display-flex align-center">
                                                <div class="no-padding margin-10px-right icon-size-formatted">
                                                    <img src="/assets/img/systemIcons/servicesicon.png" alt="Create Services Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                                </div>
                                                <div>
                                                    <p class="no-padding font-14px">Services</p>
                                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Order Services</h4>
                                                </div>
                                            </div>
                                            <div>
                                                <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                                <a href="/dashboard/administration/accounts/orderServices/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                                <a href="/dashboard/administration/accounts/manageAccount/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="fillable-section-holder" style="margin-top:-3% !important;">
                                            <div class="fillable-header">
                                                <p class="fillable-text">Basic Information</p>
                                            </div>
                                            <div class="fillable-body">
                                                <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important; margin-bottom:4%;">
                                                    <div class="form-left-side" style="width:80%;">
                                                        <div class="form-control">
                                                            <label for="purchasable">Purchasable Item</label>
                                                            <select type="text" name="purchasable" id="purchasable" onchange="updateAmount()" class="form-input">
                                                                <option>Please choose an option</option>
                                                                <?php echo $options; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-control" style="margin-top:20px;">
                                                            <label for="type">Item Type</label>
                                                            <select type="text" name="type" id="type" class="form-input">
                                                                <option>Please choose an option</option>
                                                                <option>Web Development</option>
                                                                <option>Web Hosting</option>
                                                                <option>Merchant Proccessing</option>
                                                                <option>Cloud Computing</option>
                                                                <option>SEO</option>
                                                                <option>Social Media Management</option>
                                                                <option>Graphic Design</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-control" style="margin-top:20px;">
                                                            <label for="service_status">Item Status</label>
                                                            <select type="text" name="service_status" id="service_status" class="form-input">
                                                                <option>Please choose an option</option>
                                                                <option>Active</option>
                                                                <option>Suspended</option>
                                                                <option>Terminated</option>
                                                                <option>Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-left-side" style="display:block; width:80%;">
                                                        <div class="form-control">
                                                            <label for="payment_method">Payment Method</label>
                                                            <select name="payment_method" id="payment_method" class="form-input">
                                                                <option>Please choose an option</option>
                                                                <?php foreach ($paymentMethods->data as $paymentMethod): ?>
                                                                    <?php

                                                                        $card = $paymentMethod->card;
                                                                        $logo = $card->brand;

                                                                    ?>

                                                                    <option value="<?php echo $paymentMethod->id; ?>">
                                                                        <img src="<?php echo $logo; ?>" alt="<?php echo $card->brand; ?>" style="width: 20px; height: auto;">
                                                                        <?php echo $card->brand; ?> ending in <?php echo $card->last4; ?>
                                                                    </option>

                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-control" style="margin-top:20px;">
                                                            <label for="amount">Amount</label>
                                                            <input type="number" min="1" step="any" name="amount" id="amount" class="form-input" placeholder="0.00" inputmode="numeric"  onwheel="return false" required="" />
                                                        </div>
                                                        <div class="form-control" style="margin-top:20px;">
                                                            <label for="end_date">Period Closure Date</label>
                                                            <input type="date" name="end_date" id="end_date" class="form-input" />
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

                <script>

                    function updateAmount() {
                       
                        var serviceSelect = document.getElementById('purchasable');
                        var amountInput = document.getElementById('amount');
                        var selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                        var price = selectedOption.getAttribute('data-price');
                        amountInput.value = price;
                        
                    }

                </script>

<?php

                include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

            }
        
        } else {

            echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';

        }

    }

?>