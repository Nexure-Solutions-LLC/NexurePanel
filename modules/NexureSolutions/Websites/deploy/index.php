<?php

    unset($_SESSION['pagetitle']);
    $pagetitle = "Services";
    $pagesubtitle = "Create Order";
    $_SESSION['pagetitle'] = "Order Services as Staff";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

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
                                    <a href="modules/webDesignModule/deploy/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
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
                                                <select type="text" name="purchasable" id="purchasable" class="form-input">
                                                    <option>Please choose an option</option>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-control" style="margin-top:20px;">
                                                <label for="type">Item Type</label>
                                                <select type="text" name="type" id="type" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Web Development</option>
                                                    <option>Web Hosting</option>
                                                    <option>Merchant Processing</option>
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

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>