<?php
    $pagetitle = "Authorized Create";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $accountnumber = $_GET['account_number'] ?? '';

    if (!$accountnumber) {

        header("location: /dashboard/administration/accounts");
        exit;

    }

    $manageAccountDefinitionR = new \CaliWebDesign\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // This gets the feilds for the customer registration system. This pulls it from the html
        // form and remmoves symbols or charaters that can be used for SQL injections.
        
        $fields = [
            'legalname', 'emailaddress', 'phonenumber', 'password', 
            'accountstatus', 'userrole', 'accesslevel'
        ];

        foreach ($fields as $field) {

            $$field = mysqli_real_escape_string($con, stripslashes($_REQUEST[$field]));

        }

        // This is variable definitions 

        $registrationdate = date("Y-m-d H:i:s");
        $ownerOwnedEmail = $manageAccountDefinitionR->customeremail;

        $randomPrefix = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);

        if ($variableDefinitionX->apiKeysecret && $variableDefinitionX->paymentgatewaystatus === "active") {

            if ($variableDefinitionX->paymentProcessorName === "Stripe") {

                \Stripe\Stripe::setApiKey($variableDefinitionX->apiKeysecret);

                $cu = \Stripe\Customer::create([
                    'name' => $legalname,
                    'email' => $emailaddress,
                    'phone' => $phonenumber,
                    'description' => "Account Number: " . $accountnumber,
                ]);

                $SS_STRIPE_ID = $cu['id'];

            } else {

                header("location: /error/genericSystemError");
                exit;

            }

        } else {

            header("location: /error/genericSystemError");
            exit;

        }

        // Peroforms the database entry into MySQL.

        $accountInsertRequest = "INSERT INTO `caliweb_users`(`email`, `password`, `legalName`, `mobileNumber`, `accountStatus`, `statusReason`, `statusDate`, `accountNotes`, `accountNumber`, `accountDBPrefix`, `emailVerfied`, `emailVerifiedDate`, `registrationDate`, `profileIMG`, `stripeID`, `discord_id`, `google_id`, `userrole`, `employeeAccessLevel`, `ownerAuthorizedEmail`, `firstInteractionDate`, `lastInteractionDate`, `lang`) VALUES (
            '$emailaddress', '".hash("sha512", $password)."', '$legalname', '$phonenumber', '$accountstatus', '', '$registrationdate', '', '$accountnumber', '$randomPrefix', 'true', '$registrationdate', '$registrationdate', '', '$SS_STRIPE_ID', '', '', '$userrole', '$accesslevel', '$ownerOwnedEmail', '$registrationdate', '0000-00-00 00:00:00', 'en-US')";

        if (mysqli_query($con, $accountInsertRequest)) {

            header("location: /dashboard/administration/accounts/manageAccount/?account_number={$accountnumber}");
        
        } else {

            header("location: /error/genericSystemError");

        }

    }

    echo "<title>{$pagetitle} | {$pagesubtitle}</title>";

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
                                        <img src="/assets/img/systemIcons/accountsicon.png" alt="Client Logo and/or Business Logo" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">Authorized Users</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Create Authorized User</h4>
                                    </div>
                                </div>
                                <div>
                                    <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                    <a href="/dashboard/administration/accounts/createAuthorizedUser/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/accounts/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fillable-section-holder" style="margin-top:-3% !important; margin-bottom:2%;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Basic Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important;">
                                        <div class="form-left-side" style="width:80%;">
                                            <div class="form-control">
                                                <label for="legalname">Legal Name</label>
                                                <input type="text" name="legalname" id="legalname" class="form-input" placeholder="John Doe" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="emailaddress">Email Address</label>
                                                <input type="email" name="emailaddress" id="emailaddress" class="form-input" placeholder="me@example.com" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="phonenumber">Phone Number</label>
                                                <input type="text" name="phonenumber" id="phonenumber" class="form-input" placeholder="11234567890" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-input" placeholder="Super Secret Password" required="" />
                                            </div>
                                        </div>
                                        <div class="form-left-side" style="display:block; width:80%;">
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="accountstatus">Account Status</label>
                                                <select type="text" name="accountstatus" id="accountstatus" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Active</option>
                                                    <option>Suspended</option>
                                                </select>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="userrole">User Role</label>
                                                <select type="text" name="userrole" id="userrole" class="form-input">
                                                    <option>Authorized User</option>
                                                </select>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="accesslevel">Access Level</label>
                                                <select type="text" name="accesslevel" id="accesslevel" class="form-input">
                                                    <option>Retail</option>
                                                </select>
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

?>