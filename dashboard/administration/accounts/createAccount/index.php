<?php
    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

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
        $builtaccountnumber = $_ENV['ACCOUNTSTARTNUMBER'] . substr(str_shuffle("0123456789"), 0, $accountnumberlength);

        // Social Security / Employer Identification Numbers encryption logic.

        $encryptKey = hex2bin($_ENV['ENCRYPTION_KEY']);
        $encryptIv = hex2bin($_ENV['ENCRYPTION_IV']);

        $encryptedeinssnumber = base64_encode(openssl_encrypt($einorssn, 'aes-256-cbc', $encryptKey, 0, $encryptIv) . '::' . $encryptIv);

        // Database table prefix for customers who make their own SQL databases.

        $randomPrefix = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 5);

        // Check duplicate accounts

        $checkCaliID = "SELECT * FROM nexure_users WHERE `email` = '$emailaddress'";
        $resultCaliIDCheck = mysqli_query($con, $checkCaliID);

        if (mysqli_num_rows($resultCaliIDCheck) == 1) {

            $register_error = true;

        } else {

            // Checks to see if a payment system is active to add the customer into it, if it is then it checks to see what
            // proccessor it is, if its Stripe then it proceedes, if not throws error as Stripe is the only supported system
            // as of right now.

            if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                    include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

                } else {

                    header ("location: /error/genericSystemError");

                }

            } else {

                echo 'There are no payment modules available to service this request.';

            }

            $SS_STRIPE_ID = add_customer($legalname, $emailaddress, $phonenumber, $builtaccountnumber);

            // Peroforms the database entry into MySQL.

            $accountInsertRequest = "INSERT INTO `nexure_users`(`email`, `password`, `legalName`, `mobileNumber`, `accountStatus`, `statusReason`, `statusDate`, `accountNotes`, `accountNumber`, `accountDBPrefix`, `emailVerfied`, `emailVerifiedDate`, `registrationDate`, `profileIMG`, `stripeID`, `discord_id`, `google_id`, `userrole`, `employeeAccessLevel`, `ownerAuthorizedEmail`, `firstInteractionDate`, `lastInteractionDate`, `lang`) VALUES (
                '$emailaddress', '".hash("sha512", $password)."', '$legalname', '$phonenumber', '$accountstatus', '', '$registrationdate', '$accountnotes', '$builtaccountnumber', '$randomPrefix', 'true', '$registrationdate', '$registrationdate', '/assets/img/profileImages/default.png', '$SS_STRIPE_ID', '', '', '$userrole', '$accesslevel', '', '$registrationdate', '0000-00-00 00:00:00', 'en-US')";

            if (mysqli_query($con, $accountInsertRequest)) {

                include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/SendEmail/index.php');

                sendEmailAccountRegistration(
                    "Account Opened", 
                    $emailaddress, 
                    $legalname, 
                    "[Important Information] - Hello from Nexure Solutions"
                );

                handleEmployeeOrBusinessInsert($con, $legalname, $emailaddress, $registrationdate, $phonenumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $dateofbirth, $encryptedeinssnumber, $businessname, $businessindustry, $businessrevenue, $accountstatus, $accesslevel);
            
            } else {

                header("location: /error/genericSystemError");

            }

        }

    }

    // Employee or Business function to determine what tables get data sent to them.

    function handleEmployeeOrBusinessInsert($con, $legalname, $email, $registrationdate, $phonenumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $dateofbirth, $encryptedeinssnumber, $businessname, $businessindustry, $businessrevenue, $accountstatus, $accesslevel) {
        
        if (in_array($accesslevel, ["Retail", "Wholesale", "Referral", "Undefined"])) {

            insertOwnershipInformationAndBusiness($con, $legalname, $phonenumber, $email, $dateofbirth, $encryptedeinssnumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $businessname, $businessindustry, $businessrevenue);
        
        } else {

            $moduleCheckResult = mysqli_query($con, "SELECT moduleName FROM nexure_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Staff Function'");
            $hasPayrollModule = false;

            while ($moduleCheckRow = mysqli_fetch_assoc($moduleCheckResult)) {

                if ($moduleCheckRow['moduleName'] === "Nexure Payroll") {

                    $hasPayrollModule = true;
                    break;

                }

            }

            if ($hasPayrollModule) {

                $employeeNewID = getNextEmployeeID($con);
                $employeeFormattedID = sprintf('%08d', $employeeNewID);

                $employeeInsertRequest = "INSERT INTO `nexure_payroll`(`employeeName`, `employeeIDNumber`, `employeePayType`, `employeeEmail`, `employeeTimeType`, `employeeHireDate`, `employeeTerminationDate`, `employeeRehireDate`, `employeePayRate`, `employeeWorkedHours`, `employeeExpectedPay`, `employeeActualPay`, `employeePhoneNumber`, `employeeExtension`, `employeeAddressLine1`, `employeeAddressLine2`, `employeeCity`, `employeeState`, `employeePostalCode`, `employeeCountry`, `employeeDateOfBirth`, `employeeSSNNumber`, `employeeDepartment`, `employeeNotes`, `employeeStatus`, `bankRoutingNumber`, `bankAccountNumber`, `bankAccountType`, `fundingType`) VALUES (
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

        $checkEmployeeIDsResult = mysqli_query($con, "SELECT employeeIDNumber FROM nexure_payroll ORDER BY id DESC LIMIT 1");

        if ($row = mysqli_fetch_assoc($checkEmployeeIDsResult)) {

            return intval($row['employeeIDNumber']) + 1;

        } else {

            return 4;
        }

    }
    
    // Ownership table insert.

    function insertOwnershipInformationAndBusiness($con, $legalname, $phonenumber, $email, $dateofbirth, $encryptedeinssnumber, $streetaddress, $additionaladdress, $city, $state, $postalcode, $country, $businessname, $businessindustry, $businessrevenue) {
        
        $addressInsertRequest = "INSERT INTO `nexure_ownershipinformation`(`legalName`, `phoneNumber`, `emailAddress`, `dateOfBirth`, `EINorSSNNumber`, `addressline1`, `addressline2`, `city`, `state`, `postalcode`, `country`) VALUES (
            '$legalname', '$phonenumber', '$email', '$dateofbirth', '$encryptedeinssnumber', '$streetaddress', '$additionaladdress', '$city', '$state', '$postalcode', '$country')";

        if (mysqli_query($con, $addressInsertRequest)) {

            $businessInsertRequest = "INSERT INTO `nexure_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES (
                '$businessname', '', '$businessindustry', '$businessrevenue', '$email', 'Active', '0000-00-00', '', 'false')";

            if (mysqli_query($con, $businessInsertRequest)) {

                header("location: /dashboard/administration/accounts");

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

                                    <?php if (isset($register_error)): ?>
                                        <div class="caliweb-error-box" style="margin-bottom:2%; margin-top:2%;">
                                            <p class="caliweb-login-sublink" style="font-weight:700; padding-top:0; margin-top:0;">Duplicate email has been detected.</p>
                                            <p class="caliweb-login-sublink" style="font-size:12px; margin-top:5px;">There is a user with this email. Please use a diffrent email or have the customer reset their password.</p>
                                        </div>
                                    <?php endif; ?>

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
                                                <input type="number" name="phonenumber" id="phonenumber" class="form-input" placeholder="11234567890" maxlength="10" inputmode="numeric" required="" />
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
                                                <input type="number" name="postalcode" id="postalcode" class="form-input" placeholder="12345" maxlength="6" inputmode="numeric" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="country">Country</label>
                                                <select name="country" id="country" class="form-input" required="">
                                                    <option value="AF">Afghanistan</option>
                                                    <option value="AX">Åland Islands</option>
                                                    <option value="AL">Albania</option>
                                                    <option value="DZ">Algeria</option>
                                                    <option value="AS">American Samoa</option>
                                                    <option value="AD">Andorra</option>
                                                    <option value="AO">Angola</option>
                                                    <option value="AI">Anguilla</option>
                                                    <option value="AQ">Antarctica</option>
                                                    <option value="AG">Antigua and Barbuda</option>
                                                    <option value="AR">Argentina</option>
                                                    <option value="AM">Armenia</option>
                                                    <option value="AW">Aruba</option>
                                                    <option value="AU">Australia</option>
                                                    <option value="AT">Austria</option>
                                                    <option value="AZ">Azerbaijan</option>
                                                    <option value="BS">Bahamas</option>
                                                    <option value="BH">Bahrain</option>
                                                    <option value="BD">Bangladesh</option>
                                                    <option value="BB">Barbados</option>
                                                    <option value="BY">Belarus</option>
                                                    <option value="BE">Belgium</option>
                                                    <option value="BZ">Belize</option>
                                                    <option value="BJ">Benin</option>
                                                    <option value="BM">Bermuda</option>
                                                    <option value="BT">Bhutan</option>
                                                    <option value="BO">Bolivia, Plurinational State of</option>
                                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                                    <option value="BA">Bosnia and Herzegovina</option>
                                                    <option value="BW">Botswana</option>
                                                    <option value="BV">Bouvet Island</option>
                                                    <option value="BR">Brazil</option>
                                                    <option value="IO">British Indian Ocean Territory</option>
                                                    <option value="BN">Brunei Darussalam</option>
                                                    <option value="BG">Bulgaria</option>
                                                    <option value="BF">Burkina Faso</option>
                                                    <option value="BI">Burundi</option>
                                                    <option value="KH">Cambodia</option>
                                                    <option value="CM">Cameroon</option>
                                                    <option value="CA">Canada</option>
                                                    <option value="CV">Cape Verde</option>
                                                    <option value="KY">Cayman Islands</option>
                                                    <option value="CF">Central African Republic</option>
                                                    <option value="TD">Chad</option>
                                                    <option value="CL">Chile</option>
                                                    <option value="CN">China</option>
                                                    <option value="CX">Christmas Island</option>
                                                    <option value="CC">Cocos (Keeling) Islands</option>
                                                    <option value="CO">Colombia</option>
                                                    <option value="KM">Comoros</option>
                                                    <option value="CG">Congo</option>
                                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                                    <option value="CK">Cook Islands</option>
                                                    <option value="CR">Costa Rica</option>
                                                    <option value="CI">Côte d'Ivoire</option>
                                                    <option value="HR">Croatia</option>
                                                    <option value="CU">Cuba</option>
                                                    <option value="CW">Curaçao</option>
                                                    <option value="CY">Cyprus</option>
                                                    <option value="CZ">Czech Republic</option>
                                                    <option value="DK">Denmark</option>
                                                    <option value="DJ">Djibouti</option>
                                                    <option value="DM">Dominica</option>
                                                    <option value="DO">Dominican Republic</option>
                                                    <option value="EC">Ecuador</option>
                                                    <option value="EG">Egypt</option>
                                                    <option value="SV">El Salvador</option>
                                                    <option value="GQ">Equatorial Guinea</option>
                                                    <option value="ER">Eritrea</option>
                                                    <option value="EE">Estonia</option>
                                                    <option value="ET">Ethiopia</option>
                                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                                    <option value="FO">Faroe Islands</option>
                                                    <option value="FJ">Fiji</option>
                                                    <option value="FI">Finland</option>
                                                    <option value="FR">France</option>
                                                    <option value="GF">French Guiana</option>
                                                    <option value="PF">French Polynesia</option>
                                                    <option value="TF">French Southern Territories</option>
                                                    <option value="GA">Gabon</option>
                                                    <option value="GM">Gambia</option>
                                                    <option value="GE">Georgia</option>
                                                    <option value="DE">Germany</option>
                                                    <option value="GH">Ghana</option>
                                                    <option value="GI">Gibraltar</option>
                                                    <option value="GR">Greece</option>
                                                    <option value="GL">Greenland</option>
                                                    <option value="GD">Grenada</option>
                                                    <option value="GP">Guadeloupe</option>
                                                    <option value="GU">Guam</option>
                                                    <option value="GT">Guatemala</option>
                                                    <option value="GG">Guernsey</option>
                                                    <option value="GN">Guinea</option>
                                                    <option value="GW">Guinea-Bissau</option>
                                                    <option value="GY">Guyana</option>
                                                    <option value="HT">Haiti</option>
                                                    <option value="HM">Heard Island and McDonald Islands</option>
                                                    <option value="VA">Holy See (Vatican City State)</option>
                                                    <option value="HN">Honduras</option>
                                                    <option value="HK">Hong Kong</option>
                                                    <option value="HU">Hungary</option>
                                                    <option value="IS">Iceland</option>
                                                    <option value="IN">India</option>
                                                    <option value="ID">Indonesia</option>
                                                    <option value="IR">Iran, Islamic Republic of</option>
                                                    <option value="IQ">Iraq</option>
                                                    <option value="IE">Ireland</option>
                                                    <option value="IM">Isle of Man</option>
                                                    <option value="IL">Israel</option>
                                                    <option value="IT">Italy</option>
                                                    <option value="JM">Jamaica</option>
                                                    <option value="JP">Japan</option>
                                                    <option value="JE">Jersey</option>
                                                    <option value="JO">Jordan</option>
                                                    <option value="KZ">Kazakhstan</option>
                                                    <option value="KE">Kenya</option>
                                                    <option value="KI">Kiribati</option>
                                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                                    <option value="KR">Korea, Republic of</option>
                                                    <option value="KW">Kuwait</option>
                                                    <option value="KG">Kyrgyzstan</option>
                                                    <option value="LA">Lao People's Democratic Republic</option>
                                                    <option value="LV">Latvia</option>
                                                    <option value="LB">Lebanon</option>
                                                    <option value="LS">Lesotho</option>
                                                    <option value="LR">Liberia</option>
                                                    <option value="LY">Libya</option>
                                                    <option value="LI">Liechtenstein</option>
                                                    <option value="LT">Lithuania</option>
                                                    <option value="LU">Luxembourg</option>
                                                    <option value="MO">Macao</option>
                                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                                    <option value="MG">Madagascar</option>
                                                    <option value="MW">Malawi</option>
                                                    <option value="MY">Malaysia</option>
                                                    <option value="MV">Maldives</option>
                                                    <option value="ML">Mali</option>
                                                    <option value="MT">Malta</option>
                                                    <option value="MH">Marshall Islands</option>
                                                    <option value="MQ">Martinique</option>
                                                    <option value="MR">Mauritania</option>
                                                    <option value="MU">Mauritius</option>
                                                    <option value="YT">Mayotte</option>
                                                    <option value="MX">Mexico</option>
                                                    <option value="FM">Micronesia, Federated States of</option>
                                                    <option value="MD">Moldova, Republic of</option>
                                                    <option value="MC">Monaco</option>
                                                    <option value="MN">Mongolia</option>
                                                    <option value="ME">Montenegro</option>
                                                    <option value="MS">Montserrat</option>
                                                    <option value="MA">Morocco</option>
                                                    <option value="MZ">Mozambique</option>
                                                    <option value="MM">Myanmar</option>
                                                    <option value="NA">Namibia</option>
                                                    <option value="NR">Nauru</option>
                                                    <option value="NP">Nepal</option>
                                                    <option value="NL">Netherlands</option>
                                                    <option value="NC">New Caledonia</option>
                                                    <option value="NZ">New Zealand</option>
                                                    <option value="NI">Nicaragua</option>
                                                    <option value="NE">Niger</option>
                                                    <option value="NG">Nigeria</option>
                                                    <option value="NU">Niue</option>
                                                    <option value="NF">Norfolk Island</option>
                                                    <option value="MP">Northern Mariana Islands</option>
                                                    <option value="NO">Norway</option>
                                                    <option value="OM">Oman</option>
                                                    <option value="PK">Pakistan</option>
                                                    <option value="PW">Palau</option>
                                                    <option value="PS">Palestinian Territory, Occupied</option>
                                                    <option value="PA">Panama</option>
                                                    <option value="PG">Papua New Guinea</option>
                                                    <option value="PY">Paraguay</option>
                                                    <option value="PE">Peru</option>
                                                    <option value="PH">Philippines</option>
                                                    <option value="PN">Pitcairn</option>
                                                    <option value="PL">Poland</option>
                                                    <option value="PT">Portugal</option>
                                                    <option value="PR">Puerto Rico</option>
                                                    <option value="QA">Qatar</option>
                                                    <option value="RE">Réunion</option>
                                                    <option value="RO">Romania</option>
                                                    <option value="RU">Russian Federation</option>
                                                    <option value="RW">Rwanda</option>
                                                    <option value="BL">Saint Barthélemy</option>
                                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                                    <option value="KN">Saint Kitts and Nevis</option>
                                                    <option value="LC">Saint Lucia</option>
                                                    <option value="MF">Saint Martin (French part)</option>
                                                    <option value="PM">Saint Pierre and Miquelon</option>
                                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                                    <option value="WS">Samoa</option>
                                                    <option value="SM">San Marino</option>
                                                    <option value="ST">Sao Tome and Principe</option>
                                                    <option value="SA">Saudi Arabia</option>
                                                    <option value="SN">Senegal</option>
                                                    <option value="RS">Serbia</option>
                                                    <option value="SC">Seychelles</option>
                                                    <option value="SL">Sierra Leone</option>
                                                    <option value="SG">Singapore</option>
                                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                                    <option value="SK">Slovakia</option>
                                                    <option value="SI">Slovenia</option>
                                                    <option value="SB">Solomon Islands</option>
                                                    <option value="SO">Somalia</option>
                                                    <option value="ZA">South Africa</option>
                                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                                    <option value="SS">South Sudan</option>
                                                    <option value="ES">Spain</option>
                                                    <option value="LK">Sri Lanka</option>
                                                    <option value="SD">Sudan</option>
                                                    <option value="SR">Suriname</option>
                                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                                    <option value="SZ">Swaziland</option>
                                                    <option value="SE">Sweden</option>
                                                    <option value="CH">Switzerland</option>
                                                    <option value="SY">Syrian Arab Republic</option>
                                                    <option value="TW">Taiwan, Province of China</option>
                                                    <option value="TJ">Tajikistan</option>
                                                    <option value="TZ">Tanzania, United Republic of</option>
                                                    <option value="TH">Thailand</option>
                                                    <option value="TL">Timor-Leste</option>
                                                    <option value="TG">Togo</option>
                                                    <option value="TK">Tokelau</option>
                                                    <option value="TO">Tonga</option>
                                                    <option value="TT">Trinidad and Tobago</option>
                                                    <option value="TN">Tunisia</option>
                                                    <option value="TR">Turkey</option>
                                                    <option value="TM">Turkmenistan</option>
                                                    <option value="TC">Turks and Caicos Islands</option>
                                                    <option value="TV">Tuvalu</option>
                                                    <option value="UG">Uganda</option>
                                                    <option value="UA">Ukraine</option>
                                                    <option value="AE">United Arab Emirates</option>
                                                    <option value="GB">United Kingdom</option>
                                                    <option value="US">United States</option>
                                                    <option value="UM">United States Minor Outlying Islands</option>
                                                    <option value="UY">Uruguay</option>
                                                    <option value="UZ">Uzbekistan</option>
                                                    <option value="VU">Vanuatu</option>
                                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                                    <option value="VN">Viet Nam</option>
                                                    <option value="VG">Virgin Islands, British</option>
                                                    <option value="VI">Virgin Islands, U.S.</option>
                                                    <option value="WF">Wallis and Futuna</option>
                                                    <option value="EH">Western Sahara</option>
                                                    <option value="YE">Yemen</option>
                                                    <option value="ZM">Zambia</option>
                                                    <option value="ZW">Zimbabwe</option>
                                                </select>
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
                                                <input type="password" name="einorssn" id="einorssn" class="form-input" placeholder="12-3456789" maxlength="9" inputmode="numeric" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="businessindustry">Business Industry</label>
                                                <input type="text" name="businessindustry" id="businessindustry" class="form-input" placeholder="Custom Computer Programming" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="businessrevenue">Business Revenue</label>
                                                <input type="text" name="businessrevenue" id="businessrevenue" class="form-input" placeholder="$123,456.00" maxlength="9" inputmode="numeric" required="" />
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

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>