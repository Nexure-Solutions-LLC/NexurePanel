<?php

    $pagetitle = "Services";
    $pagesubtitle = "Create Order";

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    $accountnumber = $_GET['account_number'];

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    // Get the menu option listing for the services.

    $sql = "SELECT serviceOrProductName FROM caliweb_available_purchasables WHERE serviceOrProductStatus = 'Active'";

    $result = $con->query($sql);

    $options = '';

    if ($result->num_rows > 0) {

        while($row = $result->fetch_assoc()) {

            $options .= '<option>' . htmlspecialchars($row['serviceOrProductName']) . '</option>';
            $serviceOrProductPrice = $row['serviceOrProductPrice'];

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

        // System Feilds

        $orderdate = date("Y-m-d H:i:s");
        

    } else {

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

?>

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
                                                <label for="accountstatus">Product or Service</label>
                                                <select type="text" name="accountstatus" id="accountstatus" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <?php echo $options; ?>
                                                </select>
                                            </div>
                                            <div class="form-control" style="margin-top:20px;">
                                                <label for="accountstatus">Status</label>
                                                <select type="text" name="accountstatus" id="accountstatus" class="form-input">
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
                                                <label for="legalname">Amount</label>
                                                <input type="text" name="legalname" id="legalname" class="form-input" placeholder="0.00" value="<?php echo $serviceOrProductPrice; ?>" required="" />
                                            </div>
                                            <div class="form-control" style="margin-top:20px;">
                                                <label for="legalname">Service Renewal/End Date</label>
                                                <input type="date" name="legalname" id="legalname" class="form-input" />
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

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

    }

?>