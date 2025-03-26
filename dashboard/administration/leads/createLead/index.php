<?php

    $pagetitle = "Leads";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

     include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    // When form submitted, insert values into the database.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $current_time = time();

        // Check if the last submission time is stored in the session
        
        if (isset($_SESSION['last_submit_time'])) {

            $time_diff = $current_time - $_SESSION['last_submit_time'];

            if ($time_diff < 5) {

                header("Location: /error/rateLimit");
                exit;

            }
        }

        // If the rate limit check passes, update the last submission time

        $_SESSION['last_submit_time'] = $current_time;

        // Lead Information

        $leadname = stripslashes($_REQUEST['leadname']);
        $leadname = mysqli_real_escape_string($con, $leadname);
        $contactname = stripslashes($_REQUEST['contactname']);
        $contactname = mysqli_real_escape_string($con, $contactname);
        $leadstatus = stripslashes($_REQUEST['leadstatus']);
        $leadstatus = mysqli_real_escape_string($con, $leadstatus);
        $assignedagent = stripslashes($_REQUEST['assignedagent']);
        $assignedagent = mysqli_real_escape_string($con, $assignedagent);
        $leaddescription = stripslashes($_REQUEST['leaddescription']);
        $leaddescription = mysqli_real_escape_string($con, $leaddescription);

        // Contact Information

        $contactname = stripslashes($_REQUEST['contactname']);
        $contactname = mysqli_real_escape_string($con, $contactname);
        $company = stripslashes($_REQUEST['company']);
        $company = mysqli_real_escape_string($con, $company);
        $contactphone = stripslashes($_REQUEST['contactphone']);
        $contactphone = mysqli_real_escape_string($con, $contactphone);
        $contactemail = stripslashes($_REQUEST['contactemail']);
        $contactemail = mysqli_real_escape_string($con, $contactemail);

        // Address Information

        $addressline1 = stripslashes($_REQUEST['addressline1']);
        $addressline1 = mysqli_real_escape_string($con, $addressline1);
        $addressline2 = stripslashes($_REQUEST['addressline2']);
        $addressline2 = mysqli_real_escape_string($con, $addressline2);
        $city = stripslashes($_REQUEST['city']);
        $city = mysqli_real_escape_string($con, $city);
        $state = stripslashes($_REQUEST['state']);
        $state = mysqli_real_escape_string($con, $state);
        $country = stripslashes($_REQUEST['country']);
        $country = mysqli_real_escape_string($con, $country);
        $postalcode = stripslashes($_REQUEST['postalcode']);
        $postalcode = mysqli_real_escape_string($con, $postalcode);


        // System Feilds

        $leadcreated = date("Y-m-d H:i:s");

        // Database Calls
        
        $leadInsertRequest = "INSERT INTO `nexure_leads`(`assignedAgent`, `customerName`, `accountNumber`, `leadname`, `leaddescription`, `leadStartDate`, `leadDueDate`, `status`) VALUES ('$assignedagent','$contactname','','$leadname','$leaddescription','$leadcreated','0000-00-00 00:00:00','$leadstatus')";
        
        $leadInsertResult = mysqli_query($con, $leadInsertRequest);

        if ($leadInsertResult) {

            $contactInsertRequest = "INSERT INTO `nexure_ownershipinformation`(`legalName`, `phoneNumber`, `emailAddress`, `dateOfBirth`, `EINorSSNNumber`, `addressline1`, `addressline2`, `city`, `state`, `postalcode`, `country`) VALUES ('$contactname','$contactphone','$contactemail','','','$addressline1','$addressline2','$city','$state','$postalcode','$country')";
            
            $contactInsertResult = mysqli_query($con, $contactInsertRequest);
        
            if ($contactInsertResult && isset($company)) {

                $businessInsertRequest = "INSERT INTO `nexure_businesses`(`businessName`, `businessType`, `businessIndustry`, `businessRevenue`, `email`, `businessStatus`, `businessRegDate`, `businessDescription`, `isRestricted`) VALUES ('$company','','','','$contactemail','','0000-00-00','','')";
                
                $businessInsertResult = mysqli_query($con, $businessInsertRequest);
                
                if (!$businessInsertResult) {

                    header("location: /error/genericSystemError");

                    exit;

                }

            }
            
            header("location: /dashboard/administration/leads");

        } else {

            header("location: /error/genericSystemError");
            
        }

    }

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

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
                                        <img src="/assets/img/systemIcons/leadsicon.png" alt="Leads Page Icon" style="background-color:#fff9dd;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">Leads</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Create Lead</h4>
                                    </div>
                                </div>
                                <div>
                                    <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                    <a href="/dashboard/administration/leads/createLead/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/leads/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fillable-section-holder" style="margin-top:-3% !important; padding-bottom:3%;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Lead Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important;">
                                        <div class="form-left-side" style="width:80%;">
                                            <div class="form-control">
                                                <label for="casetitle">Lead Name</label>
                                                <input type="text" name="leadname" id="leadname" class="form-input" placeholder="Please give a name for your lead." required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="contactname">Contact Name</label>
                                                <input type="text" name="contactname" id="contactname" class="form-input" placeholder="John Doe" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="company">Company</label>
                                                <input type="text" name="company" id="company" class="form-input" placeholder="Little Internet Widgets Ltd." required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="contactphone">Contact Phone Number</label>
                                                <input type="text" name="contactphone" id="contactphone" class="form-input" placeholder="12135441991" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="contactemail">Contact Email</label>
                                                <input type="text" name="contactemail" id="contactemail" class="form-input" placeholder="me@example.com" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="casestatus">Lead Status</label>
                                                <select type="text" name="leadstatus" id="leadstatus" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Open - Not Contacted</option>
                                                    <option>Working - Contacted</option>
                                                    <option>Closed - Not Converted</option>
                                                    <option>Converted</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-left-side" style="display:block; width:80%;">
                                            <div class="form-control">
                                                <label for="assignedagent">Assigned Sales Agent</label>
                                                <input type="email" name="assignedagent" id="assignedagent" class="form-input" placeholder="me@example.com" required="" />
                                                <div id="assignedagentresults" class="indivdual-search-results"></div>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="leaddescription">Lead Details</label>
                                                <textarea style="height:150px" type="text" name="leaddescription" id="leaddescription" class="form-input" required=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fillable-section-holder" style="padding-bottom:3%;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Address Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important;">
                                        <div class="form-left-side" style="width:80%;">
                                            <div class="form-control">
                                                <label for="casetitle">Address Line 1</label>
                                                <input type="text" name="addressline1" id="addressline1" class="form-input" placeholder="123 Main Street" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="city">City</label>
                                                <input type="text" name="city" id="city" class="form-input" placeholder="Los Angeles" required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="city">Postal Code</label>
                                                <input type="text" name="postalcode" id="postalcode" class="form-input" placeholder="12345" required="" />
                                            </div>
                                        </div>
                                        <div class="form-left-side" style="display:block; width:80%;">
                                            <div class="form-control">
                                                <label for="addressline2">Address line 2</label>
                                                <input type="text" name="addressline2" id="addressline2" class="form-input" placeholder="Suite 2" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="addressline2">State</label>
                                                <input type="text" name="state" id="State" class="form-input" placeholder="California" required="" />
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>