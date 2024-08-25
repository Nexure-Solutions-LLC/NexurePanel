<?php
    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo "<title>{$pagetitle} | {$pagesubtitle}</title>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // This gets the feilds for the customer registration system. This pulls it from the html
        // form and remmoves symbols or charaters that can be used for SQL injections.
        
        $fields = [
            'legalname', 'emailaddress', 'phonenumber', 'password', 
            'accountstatus', 'userrole', 'accesslevel', 'dateofbirth',
            'streetaddress', 'additionaladdress', 'city', 'state', 
            'postalcode', 'country', 'businessname', 'businessindustry', 
            'businessrevenue', 'accountnotes', 'einorssn'
        ];

        foreach ($fields as $field) {

            $$field = mysqli_real_escape_string($con, stripslashes($_REQUEST[$field]));

        }

        // This is variable definitions 

        $registrationdate = date("Y-m-d H:i:s");
        $builtaccountnumber = $_ENV['ACCOUNTSTARTNUMBER'] . substr(str_shuffle("0123456789"), 0, 12);

        // Social Security / Employer Identification Numbers encryption logic.

        $encryptKey = hex2bin($_ENV['ENCRYPTION_KEY']);
        $encryptIv = hex2bin($_ENV['ENCRYPTION_IV']);

        $encryptedeinssnumber = base64_encode(openssl_encrypt($einorssn, 'aes-256-cbc', $encryptKey, 0, $encryptIv) . '::' . $encryptIv);

        // Database table prefix for customers who make their own SQL databases.

        $randomPrefix = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);

        // Checks to see if a payment system is active to add the customer into it, if it is then it checks to see what
        // proccessor it is, if its Stripe then it proceedes, if not throws error as Stripe is the only supported system
        // as of right now.
  
        if ($variableDefinitionX->apiKeysecret && $variableDefinitionX->paymentgatewaystatus === "active") {

            if ($variableDefinitionX->paymentProcessorName === "Stripe") {

                \Stripe\Stripe::setApiKey($variableDefinitionX->apiKeysecret);

                $cu = \Stripe\Customer::create([
                    'name' => $legalname,
                    'email' => $emailaddress,
                    'phone' => $phonenumber,
                    'description' => "Account Number: " . $builtaccountnumber,
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
            '$emailaddress', '".hash("sha512", $password)."', '$legalname', '$phonenumber', '$accountstatus', '', '$registrationdate', '$accountnotes', '$builtaccountnumber', '$randomPrefix', 'true', '$registrationdate', '$registrationdate', '', '$SS_STRIPE_ID', '', '', '$userrole', '$accesslevel', '', '$registrationdate', '0000-00-00 00:00:00', 'en-US')";

        if (mysqli_query($con, $accountInsertRequest)) {

            handleEmployeeOrBusinessInsert($con, $legalname, $emailaddress, $registrationdate, $phonenumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $dateofbirth, $encryptedeinssnumber, $businessname, $businessindustry, $businessrevenue, $accountstatus, $accesslevel);
        
        } else {

            header("location: /error/genericSystemError");

        }
    }

    // Employee or Business function to determine what tables get data sent to them.

    function handleEmployeeOrBusinessInsert($con, $legalname, $email, $registrationdate, $phonenumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $dateofbirth, $encryptedeinssnumber, $businessname, $businessindustry, $businessrevenue, $accountstatus, $accesslevel) {
        
        if (in_array($accesslevel, ["Retail", "Wholesale", "Referral", "Undefined"])) {

            insertOwnershipInformationAndBusiness($con, $legalname, $phonenumber, $email, $dateofbirth, $encryptedeinssnumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $businessname, $businessindustry, $businessrevenue);
        
        } else {

            $moduleCheckResult = mysqli_query($con, "SELECT moduleName FROM caliweb_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Staff Function'");
            $hasPayrollModule = false;

            while ($moduleCheckRow = mysqli_fetch_assoc($moduleCheckResult)) {

                if ($moduleCheckRow['moduleName'] === "Cali Payroll") {

                    $hasPayrollModule = true;
                    break;

                }

            }

            if ($hasPayrollModule) {

                $employeeNewID = getNextEmployeeID($con);
                $employeeFormattedID = sprintf('%08d', $employeeNewID);

                $employeeInsertRequest = "INSERT INTO `caliweb_payroll`(`employeeName`, `employeeIDNumber`, `employeePayType`, `employeeEmail`, `employeeTimeType`, `employeeHireDate`, `employeeTerminationDate`, `employeeRehireDate`, `employeePayRate`, `employeeWorkedHours`, `employeeExpectedPay`, `employeeActualPay`, `employeePhoneNumber`, `employeeExtension`, `employeeAddressLine1`, `employeeAddressLine2`, `employeeCity`, `employeeState`, `employeePostalCode`, `employeeCountry`, `employeeDateOfBirth`, `employeeSSNNumber`, `employeeDepartment`, `employeeNotes`, `employeeStatus`, `bankRoutingNumber`, `bankAccountNumber`, `bankAccountType`, `fundingType`) VALUES (
                    '$legalname','$employeeFormattedID','Salary','$email','Full-Time','$registrationdate','0000-00-00','0000-00-00','0.00','0.00','0.00','0.00','$phonenumber','0000','$streetaddress','$additionaladdress','$city','$state','$postalcode','$country','$dateofbirth','000-00-0000','Not Assigned','','$accountstatus','000000000','000000000','Undefined','Standard ACH')";
                
                if (mysqli_query($con, $employeeInsertRequest)) {

                    header("location: /modules/payroll");

                } else {

                    header("location: /error/genericSystemError");

                }

            } else {

                insertOwnershipInformationAndBusiness($con, $legalname, $phonenumber, $email, $dateofbirth, $encryptedeinssnumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $businessname, $businessindustry, $businessrevenue);
            }

        }

    }

    // Employee ID number generation function.

    function getNextEmployeeID($con) {

        $checkEmployeeIDsResult = mysqli_query($con, "SELECT employeeIDNumber FROM caliweb_payroll ORDER BY id DESC LIMIT 1");

        if ($row = mysqli_fetch_assoc($checkEmployeeIDsResult)) {

            return intval($row['employeeIDNumber']) + 1;

        } else {

            return 4;
        }

    }
    
    // Ownership table insert.

    function insertOwnershipInformationAndBusiness($con, $legalname, $phonenumber, $email, $dateofbirth, $encryptedeinssnumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $businessname, $businessindustry, $businessrevenue) {
        
        $addressInsertRequest = "INSERT INTO `caliweb_ownershipinformation`(`legalName`, `phoneNumber`, `emailAddress`, `dateOfBirth`, `EINorSSNNumber`, `addressline1`, `addressline2`, `city`, `state`, `postalcode`, `country`) VALUES (
            '$legalname', '$phonenumber', '$email', '$dateofbirth', '$encryptedeinssnumber', '$streetaddress', '$additionaladdress', '$city', '$state', '$postalcode', '$country')";

        if (mysqli_query($con, $addressInsertRequest)) {

            $businessInsertRequest = "INSERT INTO `caliweb_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES (
                '$businessname', '', '$businessindustry', '$businessrevenue', '$email', '', '', '', 'false')";

            if (mysqli_query($con, $businessInsertRequest)) {

                header("location: /");

            } else {

                header("location: /error/genericSystemError");

            }

        } else {

            header("location: /error/genericSystemError");

        }

    }

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
                                        <p class="no-padding font-14px">Accounts</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Create Account</h4>
                                    </div>
                                </div>
                                <div>
                                    <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                    <a href="/dashboard/administration/accounts/createAccount/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/accounts/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fillable-section-holder" style="margin-top:-3% !important;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Personal Information</p>
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
                                            <div class="form-control">
                                                <label for="dateofbirth">Date of Birth</label>
                                                <input type="date" name="dateofbirth" id="dateofbirth" class="form-input" placeholder="01/01/1999" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="accountstatus">Account Status</label>
                                                <select type="text" name="accountstatus" id="accountstatus" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Active</option>
                                                    <option>Suspended</option>
                                                    <option>Terminated</option>
                                                    <option>Under Review</option>
                                                    <option>Closed</option>
                                                </select>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="userrole">User Role</label>
                                                <select type="text" name="userrole" id="userrole" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Customer</option>
                                                    <option>Authorized User</option>
                                                    <option>Administrator</option>
                                                    <option>Partner</option>
                                                </select>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="accesslevel">Access Level</label>
                                                <select type="text" name="accesslevel" id="accesslevel" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Retail</option>
                                                    <option>Wholesale</option>
                                                    <option>Referral</option>
                                                    <option>Employee</option>
                                                    <option>Manager</option>
                                                    <option>Executive</option>
                                                    <option>Undefined</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fillable-section-holder" style="margin-top:3% !important; margin-bottom:3% !important;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Address Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important;">
                                        <div class="form-left-side" style="width:80%;">
                                            <div class="form-control">
                                                <label for="streetaddress">Street Address</label>
                                                <input type="text" name="streetaddress" id="streetaddress" class="form-input" placeholder="123 Main Street" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="additionaladdress">Additional Address</label>
                                                <input type="text" name="additionaladdress" id="additionaladdress" class="form-input" placeholder="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="city">City</label>
                                                <input type="text" name="city" id="city" class="form-input" placeholder="Any City" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="state">State</label>
                                                <input type="text" name="state" id="state" class="form-input" placeholder="Any State" required="" />
                                            </div>
                                        </div>
                                        <div class="form-left-side" style="display:block; width:80%;">
                                            <div class="form-control">
                                                <label for="postalcode">Postal Code</label>
                                                <input type="text" name="postalcode" id="postalcode" class="form-input" placeholder="12345" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="country">Country</label>
                                                <input type="text" name="country" id="country" class="form-input" placeholder="United States" required="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fillable-section-holder" style="margin-top:3% !important; margin-bottom:3% !important;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Additional Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important;">
                                        <div class="form-left-side" style="width:80%;">
                                            <div class="form-control">
                                                <label for="businessname">Business Name</label>
                                                <input type="text" name="businessname" id="businessname" class="form-input" placeholder="Little Internet Widgets Ltd." required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="einorssn">Business Tax ID</label>
                                                <input type="text" name="einorssn" id="einorssn" class="form-input" placeholder="12-3456789" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="businessindustry">Business Industry</label>
                                                <input type="text" name="businessindustry" id="businessindustry" class="form-input" placeholder="Custom Computer Programming" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="businessrevenue">Business Revenue</label>
                                                <input type="text" name="businessrevenue" id="businessrevenue" class="form-input" placeholder="$123,456.00" required="" />
                                            </div>
                                        </div>
                                        <div class="form-left-side" style="display:block; width:80%;">
                                            <div class="form-control">
                                                <label for="accountnotes">Notes</label>
                                                <textarea type="text" name="accountnotes" id="accountnotes" class="form-input" placeholder="" style="height:180px;"></textarea>
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