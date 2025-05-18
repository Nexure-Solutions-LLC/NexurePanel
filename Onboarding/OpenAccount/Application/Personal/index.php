<?php

    session_start();

    $encryption_key = getenv('ENCRYPTION_KEY');

    $encryption_iv = getenv('ENCRYPTION_IV');

    function encryptTIN($data, $key, $iv) {

        return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv));

    }

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

        $businessLegalName = htmlspecialchars($_POST['businessLegalName']);

        $businessDBAName = htmlspecialchars($_POST['businessDBAName']);

        $line1 = htmlspecialchars($_POST['addressLine1']);

        $line2 = htmlspecialchars($_POST['addressLine2']);

        $city = htmlspecialchars($_POST['city']);

        $state = htmlspecialchars($_POST['state']);

        $postal = htmlspecialchars($_POST['postalCode']);

        $country = htmlspecialchars($_POST['country']);

        $tin = encryptTIN($_POST['businessTIN'], $encryption_key, $encryption_iv);

        $_SESSION['nexureApplication']['businessLegalName'] = $businessLegalName;

        $_SESSION['nexureApplication']['businessDBAName'] = $businessDBAName;

        $_SESSION['nexureApplication']['businessLine1'] = $line1;

        $_SESSION['nexureApplication']['businessLine2'] = $line2;

        $_SESSION['nexureApplication']['businessCity'] = $city;

        $_SESSION['nexureApplication']['businessState'] = $state;

        $_SESSION['nexureApplication']['businessPostalCode'] = $postal;

        $_SESSION['nexureApplication']['businessCountry'] = $country;

        $_SESSION['nexureApplication']['businessTIN'] = $tin;

        header("Location: /Onboarding/OpenAccount/Application/Business/IndustryInformation");

        exit;
    }

    include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Headers/index.php");

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | Personal Account Application</title>

    <style>
        body {
            overflow-y: auto !important;
        }
    </style>

    <section class="section nexure-open-online-access-and-account" style="padding-bottom:7%;">
        <div class="container nexure-container">
            <div style="display:flex; align-items:top;">
                <div>
                    <h5 class="font-18px">Need help?</h5>
                    <p class="font-14px margin-top-10px">If you need help with your application, call us at <?php echo $VariableDefinitionHandler->organizationSupportInfo; ?></p>
                </div>
                <div class="margin-left-80px">
                    <h4 class="font-20px">Your Information</h4>
                    <p class="font-14px margin-top-20px width-60">We collect information about you for compliance and vetting reasons. Please enter the following information to continue opening your account.</p>
                    <form action="" method="POST" class="width-80">
                        <div class="nexure-grid nexure-one-grid no-row-gap margin-top-60px">
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="businessLegalName">Legal Name</label>
                                <input type="text" name="businessLegalName" class="nexure-textbox" required>
                            </div>
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="mobileNumber">Mobile Number</label>
                                <input type="text" name="mobileNumber" class="nexure-textbox">
                            </div>
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="addressLine1">Address Line 1</label>
                                <input type="text" name="addressLine1" class="nexure-textbox" required>
                            </div>
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="addressLine2">Address Line 2</label>
                                <input type="text" name="addressLine2" class="nexure-textbox">
                            </div>
                        </div>
                        <div class="nexure-grid nexure-two-grid no-row-gap">
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="city">City</label>
                                <input type="text" name="city" class="nexure-textbox" required>
                            </div>
                            <div class="form-control" style="text-align:left; align-items:start;">
                                <label for="state">State</label>
                                <input type="text" name="state" class="nexure-textbox" required>
                            </div>
                            <div class="form-control"  style="text-align:left; align-items:start;">
                                <label for="postalCode">Postal Code</label>
                                <input type="text" name="postalCode" class="nexure-textbox" required>
                            </div>
                           <div class="form-control" style="text-align:left; align-items:start;">
                                <label for="country">Country</label>
                                <select name="country" class="nexure-textbox" required>
                                    <option value="">Select a country</option>
                                    <option>Afghanistan</option>
                                    <option>Albania</option>
                                    <option>Algeria</option>
                                    <option>Andorra</option>
                                    <option>Angola</option>
                                    <option>Antigua and Barbuda</option>
                                    <option>Argentina</option>
                                    <option>Armenia</option>
                                    <option>Australia</option>
                                    <option>Austria</option>
                                    <option>Azerbaijan</option>
                                    <option>Bahamas</option>
                                    <option>Bahrain</option>
                                    <option>Bangladesh</option>
                                    <option>Barbados</option>
                                    <option>Belarus</option>
                                    <option>Belgium</option>
                                    <option>Belize</option>
                                    <option>Benin</option>
                                    <option>Bhutan</option>
                                    <option>Bolivia</option>
                                    <option>Bosnia and Herzegovina</option>
                                    <option>Botswana</option>
                                    <option>Brazil</option>
                                    <option>Brunei</option>
                                    <option>Bulgaria</option>
                                    <option>Burkina Faso</option>
                                    <option>Burundi</option>
                                    <option>Cabo Verde</option>
                                    <option>Cambodia</option>
                                    <option>Cameroon</option>
                                    <option>Canada</option>
                                    <option>Central African Republic</option>
                                    <option>Chad</option>
                                    <option>Chile</option>
                                    <option>China</option>
                                    <option>Colombia</option>
                                    <option>Comoros</option>
                                    <option>Congo (Congo-Brazzaville)</option>
                                    <option>Costa Rica</option>
                                    <option>Croatia</option>
                                    <option>Cuba</option>
                                    <option>Cyprus</option>
                                    <option>Czech Republic</option>
                                    <option>Democratic Republic of the Congo</option>
                                    <option>Denmark</option>
                                    <option>Djibouti</option>
                                    <option>Dominica</option>
                                    <option>Dominican Republic</option>
                                    <option>Ecuador</option>
                                    <option>Egypt</option>
                                    <option>El Salvador</option>
                                    <option>Equatorial Guinea</option>
                                    <option>Eritrea</option>
                                    <option>Estonia</option>
                                    <option>Eswatini</option>
                                    <option>Ethiopia</option>
                                    <option>Fiji</option>
                                    <option>Finland</option>
                                    <option>France</option>
                                    <option>Gabon</option>
                                    <option>Gambia</option>
                                    <option>Georgia</option>
                                    <option>Germany</option>
                                    <option>Ghana</option>
                                    <option>Greece</option>
                                    <option>Grenada</option>
                                    <option>Guatemala</option>
                                    <option>Guinea</option>
                                    <option>Guinea-Bissau</option>
                                    <option>Guyana</option>
                                    <option>Haiti</option>
                                    <option>Honduras</option>
                                    <option>Hungary</option>
                                    <option>Iceland</option>
                                    <option>India</option>
                                    <option>Indonesia</option>
                                    <option>Iran</option>
                                    <option>Iraq</option>
                                    <option>Ireland</option>
                                    <option>Israel</option>
                                    <option>Italy</option>
                                    <option>Jamaica</option>
                                    <option>Japan</option>
                                    <option>Jordan</option>
                                    <option>Kazakhstan</option>
                                    <option>Kenya</option>
                                    <option>Kiribati</option>
                                    <option>Kuwait</option>
                                    <option>Kyrgyzstan</option>
                                    <option>Laos</option>
                                    <option>Latvia</option>
                                    <option>Lebanon</option>
                                    <option>Lesotho</option>
                                    <option>Liberia</option>
                                    <option>Libya</option>
                                    <option>Liechtenstein</option>
                                    <option>Lithuania</option>
                                    <option>Luxembourg</option>
                                    <option>Madagascar</option>
                                    <option>Malawi</option>
                                    <option>Malaysia</option>
                                    <option>Maldives</option>
                                    <option>Mali</option>
                                    <option>Malta</option>
                                    <option>Marshall Islands</option>
                                    <option>Mauritania</option>
                                    <option>Mauritius</option>
                                    <option>Mexico</option>
                                    <option>Micronesia</option>
                                    <option>Moldova</option>
                                    <option>Monaco</option>
                                    <option>Mongolia</option>
                                    <option>Montenegro</option>
                                    <option>Morocco</option>
                                    <option>Mozambique</option>
                                    <option>Myanmar</option>
                                    <option>Namibia</option>
                                    <option>Nauru</option>
                                    <option>Nepal</option>
                                    <option>Netherlands</option>
                                    <option>New Zealand</option>
                                    <option>Nicaragua</option>
                                    <option>Niger</option>
                                    <option>Nigeria</option>
                                    <option>North Korea</option>
                                    <option>North Macedonia</option>
                                    <option>Norway</option>
                                    <option>Oman</option>
                                    <option>Pakistan</option>
                                    <option>Palau</option>
                                    <option>Palestine</option>
                                    <option>Panama</option>
                                    <option>Papua New Guinea</option>
                                    <option>Paraguay</option>
                                    <option>Peru</option>
                                    <option>Philippines</option>
                                    <option>Poland</option>
                                    <option>Portugal</option>
                                    <option>Qatar</option>
                                    <option>Romania</option>
                                    <option>Russia</option>
                                    <option>Rwanda</option>
                                    <option>Saint Kitts and Nevis</option>
                                    <option>Saint Lucia</option>
                                    <option>Saint Vincent and the Grenadines</option>
                                    <option>Samoa</option>
                                    <option>San Marino</option>
                                    <option>Sao Tome and Principe</option>
                                    <option>Saudi Arabia</option>
                                    <option>Senegal</option>
                                    <option>Serbia</option>
                                    <option>Seychelles</option>
                                    <option>Sierra Leone</option>
                                    <option>Singapore</option>
                                    <option>Slovakia</option>
                                    <option>Slovenia</option>
                                    <option>Solomon Islands</option>
                                    <option>Somalia</option>
                                    <option>South Africa</option>
                                    <option>South Korea</option>
                                    <option>South Sudan</option>
                                    <option>Spain</option>
                                    <option>Sri Lanka</option>
                                    <option>Sudan</option>
                                    <option>Suriname</option>
                                    <option>Sweden</option>
                                    <option>Switzerland</option>
                                    <option>Syria</option>
                                    <option>Taiwan</option>
                                    <option>Tajikistan</option>
                                    <option>Tanzania</option>
                                    <option>Thailand</option>
                                    <option>Timor-Leste</option>
                                    <option>Togo</option>
                                    <option>Tonga</option>
                                    <option>Trinidad and Tobago</option>
                                    <option>Tunisia</option>
                                    <option>Turkey</option>
                                    <option>Turkmenistan</option>
                                    <option>Tuvalu</option>
                                    <option>Uganda</option>
                                    <option>Ukraine</option>
                                    <option>United Arab Emirates</option>
                                    <option>United Kingdom</option>
                                    <option>United States</option>
                                    <option>Uruguay</option>
                                    <option>Uzbekistan</option>
                                    <option>Vanuatu</option>
                                    <option>Vatican City</option>
                                    <option>Venezuela</option>
                                    <option>Vietnam</option>
                                    <option>Yemen</option>
                                    <option>Zambia</option>
                                    <option>Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                        <div class="nexure-grid nexure-one-grid gap-row-spacing-30">
                            <div class="form-control" style="text-align:left; align-items:start;">
                                <label for="businessTIN">EIN or SSN</label>
                                <input type="text" name="businessTIN" class="nexure-textbox" required>
                            </div>
                        </div>
                        <div class="button-area" style="text-align:right;">
                            <button class="nexure-button primary" style="padding:10px 50px;" type="submit" name="submit">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Footers/index.php"); ?>
