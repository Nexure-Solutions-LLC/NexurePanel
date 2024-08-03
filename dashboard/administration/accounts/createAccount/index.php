<?php
    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

    // When form submitted, insert values into the database.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Personal Information Section

        $legalname = stripslashes($_REQUEST['legalname']);
        $legalname = mysqli_real_escape_string($con, $legalname);
        $caliid = stripslashes($_REQUEST['emailaddress']);
        $caliid = mysqli_real_escape_string($con, $caliid);
        $mobilenumber = stripslashes($_REQUEST['phonenumber']);
        $mobilenumber = mysqli_real_escape_string($con, $mobilenumber);
        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);
        $accountstatus = stripslashes($_REQUEST['accountstatus']);
        $accountstatus = mysqli_real_escape_string($con, $accountstatus);
        $userrole = stripslashes($_REQUEST['userrole']);
        $userrole = mysqli_real_escape_string($con, $userrole);
        $accesslevel = stripslashes($_REQUEST['accesslevel']);
        $accesslevel = mysqli_real_escape_string($con, $accesslevel);

        // Address Information

        $streetaddress = stripslashes($_REQUEST['streetaddress']);
        $streetaddress = mysqli_real_escape_string($con, $streetaddress);
        $additionaladdress = stripslashes($_REQUEST['additionaladdress']);
        $additionaladdress = mysqli_real_escape_string($con, $additionaladdress);
        $city = stripslashes($_REQUEST['city']);
        $city = mysqli_real_escape_string($con, $city);
        $state = stripslashes($_REQUEST['state']);
        $state = mysqli_real_escape_string($con, $state);
        $postalcode = stripslashes($_REQUEST['postalcode']);
        $postalcode = mysqli_real_escape_string($con, $accesslevel);
        $country = stripslashes($_REQUEST['country']);
        $country = mysqli_real_escape_string($con, $country);

        // Additional Information

        $businessname = stripslashes($_REQUEST['businessname']);
        $businessname = mysqli_real_escape_string($con, $businessname);
        $businessindustry = stripslashes($_REQUEST['businessindustry']);
        $businessindustry = mysqli_real_escape_string($con, $businessindustry);
        $businessrevenue = stripslashes($_REQUEST['businessrevenue']);
        $businessrevenue = mysqli_real_escape_string($con, $businessrevenue);
        $accountnotes = stripslashes($_REQUEST['accountnotes']);
        $accountnotes = mysqli_real_escape_string($con, $accountnotes);

        // System Feilds

        $registrationdate = date("Y-m-d H:i:s");
        $accountnumber = substr(str_shuffle("0123456789"), 0, 12);
        $accountnumber_starting = $_ENV['ACCOUNTSTARTNUMBER'];
        $builtaccountnumber = $accountnumber_starting.$accountnumber;

        function generateRandomPrefix($length = 3) {

            $characters = 'abcdefghijklmnopqrstuvwxyz';
            $prefix = '';

            for ($i = 0; $i < $length; $i++) {

                $prefix .= $characters[rand(0, strlen($characters) - 1)];

            }

            return $prefix;

        }
        
        $randomPrefix = generateRandomPrefix(5);

        if (mysqli_connect_errno()) {

            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();

        }
    
        // Perform query
        $result = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig WHERE id = '1'");
        $paymentgateway = mysqli_fetch_array($result);
        // Free result set
        mysqli_free_result($result);
    
        $apikeysecret = $paymentgateway['secretKey'];
        $apikeypublic = $paymentgateway['publicKey'];
        $paymentgatewaystatus = $paymentgateway['status'];
        $paymentProcessorName = $paymentgateway['processorName'];

        // Checks type of payment processor.
        if ($apikeysecret != "" && $paymentgatewaystatus == "Active" || $paymentgatewaystatus == "active") {

            if ($paymentProcessorName == "Stripe") {

                \Stripe\Stripe::setApiKey($apikeysecret);

                $cu = \Stripe\Customer::create(array(
                    'name' => $legalname,
                    'email' => $caliid,
                    'phone' => $mobilenumber,
                    'description' => "Account Number: ".$builtaccountnumber, 
                ));

                $SS_STRIPE_ID =  $cu['id'];

            } else {

                header ("location: /error/genericSystemError");

            }

        } else {

            header ("location: /error/genericSystemError");

        }

        $accountInsertRequest = "INSERT INTO `caliweb_users`(`email`, `password`, `legalName`, `mobileNumber`, `accountStatus`, `statusReason`, `statusDate`, `accountNotes`, `accountNumber`, `accountDBPrefix`, `emailVerfied`, `emailVerifiedDate`, `registrationDate`, `profileIMG`, `stripeID`, `discord_id`, `google_id`, `userrole`, `employeeAccessLevel`, `ownerAuthorizedEmail`, `firstInteractionDate`, `lastInteractionDate`, `lang`) VALUES ('$caliid', '".hash("sha512", $password)."', '$legalname', '$mobilenumber', '$accountstatus', '', '$registrationdate', '$accountnotes', '$builtaccountnumber', '$randomPrefix', 'true', '$registrationdate', '$registrationdate', '', '$SS_STRIPE_ID', '', '', '$userrole', '$accesslevel', '', '$registrationdate', '0000-00-00 00:00:00', 'en-US')";
        $accountInsertResult = mysqli_query($con, $accountInsertRequest);

        if ($accountInsertResult) {

            // Runs a check to see if it's an employee to add the account to the employee payroll module.
            // Logins are treated as customer accounts until they are not. One system for all.

            if ($accesslevel != "Retail" && $accesslevel != "Wholesale" && $accesslevel != "Referral" && $accesslevel != "Undefined") {
        
                $moduleCheckQuery = "SELECT * FROM caliweb_modules WHERE moduleStatus = 'Active' AND `modulePositionType` = 'Staff Function'";
                $moduleCheckResult = mysqli_query($con, $moduleCheckQuery);
        
                if (mysqli_num_rows($moduleCheckResult) > 0) {

                    while ($moduleCheckRow = mysqli_fetch_assoc($moduleCheckResult)) {

                        $moduleCheckName = $moduleCheckRow['moduleName'];
        
                        if ($moduleCheckName == "Cali Payroll") {

                            // Generate Employee IDs

                            $checkEmployeeIDsQuery = "SELECT employeeIDNumber FROM caliweb_payroll ORDER BY id DESC LIMIT 1";
                            $checkEmployeeIDsResult = $con->query($checkEmployeeIDsQuery);
        
                            if ($checkEmployeeIDsResult->num_rows > 0) {

                                $checkEmployeeIDsRow = $checkEmployeeIDsResult->fetch_assoc();
                                $employeeLastID = intval($checkEmployeeIDsRow['employeeIDNumber']);
                                $employeeNewID = $employeeLastID + 1;

                            } else {

                                $employeeNewID = 4;

                            }
        
                            $employeeFormattedID = sprintf('%08d', $employeeNewID);
        
                            // Insert the new employee record

                            $employeeInsertRequest = "INSERT INTO `caliweb_payroll`(`employeeName`, `employeeIDNumber`, `employeePayType`, `employeeEmail`, `employeeTimeType`, `employeeHireDate`, `employeeTerminationDate`, `employeeRehireDate`, `employeePayRate`, `employeeWorkedHours`, `employeeExpectedPay`, `employeeActualPay`, `employeePhoneNumber`, `employeeExtension`, `employeeAddressLine1`, `employeeAddressLine2`, `employeeCity`, `employeeState`, `employeePostalCode`, `employeeCountry`, `employeeDateOfBirth`, `employeeSSNNumber`, `employeeDepartment`, `employeeNotes`, `employeeStatus`, `bankRoutingNumber`, `bankAccountNumber`, `bankAccountType`, `fundingType`) VALUES ('$legalname','$employeeFormattedID','Salary','$caliid','Full-Time','$registrationdate','0000-00-00','0000-00-00','0.00','0.00','0.00','0.00','$mobilenumber','0000','$streetaddress','$additionaladdress','$city','$state','$postalcode','$country','0000-00-00','000-00-0000','Not Assigned','','$accountstatus','000000000','000000000','Undefined','Standard ACH')";
                            $employeeInsertResult = mysqli_query($con, $employeeInsertRequest);
        
                            if ($employeeInsertResult) {

                                header("location: /modules/payroll");

                            } else {

                                header("location: /error/genericSystemError");

                            }

                        } else {

                            // Handle non-employee insertion into ownership information and businesses

                            $addressInsertRequest = "INSERT INTO `caliweb_ownershipinformation`(`legalName`, `phoneNumber`, `emailAddress`, `dateOfBirth`, `EINorSSNNumber`, `addressline1`, `addressline2`, `city`, `state`, `postalcode`, `country`) VALUES ('$legalname', '$mobilenumber', '$caliid', '', '', '$streetaddress', '$additionaladdress', '$city', '$state', '$postalcode', '$country')";
                            $addressInsertResult = mysqli_query($con, $addressInsertRequest);
                    
                            if ($addressInsertResult) {

                                $businessInsertRequest = "INSERT INTO `caliweb_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES ('$businessname', '', '$businessindustry', '$businessrevenue', '$caliid', 'Active', '0000-00-00', '', 'false')";
                                $businessInsertResult = mysqli_query($con, $businessInsertRequest);
                    
                                if ($businessInsertResult) {

                                    echo '<script type="text/javascript">window.location = "/dashboard/administration/accounts"</script>';

                                } else {

                                    header("location: /error/genericSystemError");

                                }

                            } else {

                                header("location: /error/genericSystemError");

                            }

                        }

                    }

                } else {
                    // Handle non-employee insertion into ownership information and businesses
                    $addressInsertRequest = "INSERT INTO `caliweb_ownershipinformation`(`legalName`, `phoneNumber`, `emailAddress`, `dateOfBirth`, `EINorSSNNumber`, `addressline1`, `addressline2`, `city`, `state`, `postalcode`, `country`) VALUES ('$legalname', '$mobilenumber', '$caliid', '', '', '$streetaddress', '$additionaladdress', '$city', '$state', '$postalcode', '$country')";
                    $addressInsertResult = mysqli_query($con, $addressInsertRequest);
            
                    if ($addressInsertResult) {
                        $businessInsertRequest = "INSERT INTO `caliweb_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES ('$businessname', '', '$businessindustry', '$businessrevenue', '$caliid', 'Active', '0000-00-00', '', 'false')";
                        $businessInsertResult = mysqli_query($con, $businessInsertRequest);
            
                        if ($businessInsertResult) {
                            echo '<script type="text/javascript">window.location = "/dashboard/administration/accounts"</script>';
                        } else {
                            header("location: /error/genericSystemError");
                        }
                    } else {
                        header("location: /error/genericSystemError");
                    }
                }
            } else {


                // Handle non-employee insertion into ownership information and businesses
                $addressInsertRequest = "INSERT INTO `caliweb_ownershipinformation`(`legalName`, `phoneNumber`, `emailAddress`, `dateOfBirth`, `EINorSSNNumber`, `addressline1`, `addressline2`, `city`, `state`, `postalcode`, `country`) VALUES ('$legalname', '$mobilenumber', '$caliid', '', '', '$streetaddress', '$additionaladdress', '$city', '$state', '$postalcode', '$country')";
                $addressInsertResult = mysqli_query($con, $addressInsertRequest);
        
                if ($addressInsertResult) {

                    $businessInsertRequest = "INSERT INTO `caliweb_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES ('$businessname', '', '$businessindustry', '$businessrevenue', '$caliid', 'Active', '0000-00-00', '', 'false')";
                    $businessInsertResult = mysqli_query($con, $businessInsertRequest);
        
                    if ($businessInsertResult) {
                        
                        echo '<script type="text/javascript">window.location = "/dashboard/administration/accounts"</script>';

                    } else {

                        header("location: /error/genericSystemError");

                    }

                } else {
                    header("location: /error/genericSystemError");

                }

            }

        } else {

            header("location: /error/genericSystemError");
            
        }

    } else {

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

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

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

    }

?>