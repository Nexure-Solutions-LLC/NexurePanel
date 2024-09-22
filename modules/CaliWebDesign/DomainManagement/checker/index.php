<?php

// Uninitialized values to prevent page load failure

$pagetitle = '';
$pagesubtitle = 'Domain Services Management';
$pagetype = '';

include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

?>

<!-- HTML Content for administrators view -->

<section class="section first-dashboard-area-cards" style="padding-top:2%;">
    <div class="container width-98">
        <div class="caliweb-one-grid special-caliweb-spacing">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card custom-padding-account-card" style="padding-bottom:0;">
                    <div class="card-header-account" style="margin-bottom:0;">
                        <div class="display-flex align-center">
                            <div class="no-padding margin-10px-right icon-size-formatted">
                                <img src="/assets/img/customerBusinessLogos/defaultstore.png" alt="Client Logo and/or Business Logo" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                            </div>
                            <div>
                                <p class="no-padding font-14px" style="padding-bottom:4px;">Domains</p>
                                <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                                    Domain Check
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="caliweb-card dashboard-card">

                        <div class="domain-search">
                            <form action="" method="GET">
                                <div class="caliweb-grid caliweb-two-grid domain-grid" style="grid-row-gap:50px !important;">
                                    <input type="text" class="form-input" maxlength="256" name="domain" placeholder="Search for a domain e.g example.com" id="domain" required="" />
                                    <button class="caliweb-button primary" style="outline:0; width:100%; margin-top:0;" type="submit" name="submit">Check Domain</button>
                                </div>
                            </form>

                            <?php

                            if (isset($_GET['domain'])) {
                                $domain = $_GET['domain'];
                                $apiKey = 'h1Jkp37ks1gW_YcHwL9a2gxjZTjs16NCrST';
                                $apiSecret = 'Csrp3hvjDxrm45f6juzNEp';

                                // Function to check domain availability using GoDaddy API
                                function checkDomainAvailability($apiKey, $apiSecret, $domain)
                                {
                                    $url = "https://api.godaddy.com/v1/domains/available?domain={$domain}";

                                    $ch = curl_init($url);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                                        'Authorization: sso-key ' . $apiKey . ':' . $apiSecret,
                                    ]);

                                    $response = curl_exec($ch);
                                    curl_close($ch);

                                    return json_decode($response, true);
                                }

                                // Check domain availability
                                $availability = checkDomainAvailability($apiKey, $apiSecret, $domain);

                                // Display the result
                                if ($availability['available']) {
                                    echo "<span style='background-color:#D0FFBD; color:#000; border-radius:8px; padding:10px;' class='badge-exact'>Domain Available</span><br><br><p class='mobile-para-domain' style='width:100%; margin-top:1%; font-size:16px;'>The domain <strong>{$domain}</strong> is available for purchase.</p>";
                                } else {
                                    echo "<span style='background-color:#FFBDBD; color:#000; border-radius:8px; padding:10px;' class='badge-exact'>Domain Not Available</span><br><br><p class='mobile-para-domain' style='width:100%; margin-top:1%; font-size:16px;'>Oh-no! The domain <strong>{$domain}</strong> is already registered. You may continue <a href='#'>searching</a> for another one.</p>";
                                }
                            } else {
                                // Handle case when the domain parameter is not provided
                                echo "";
                            }

                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php

include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>