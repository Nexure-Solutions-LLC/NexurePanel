<?php

    // Uninitialized values to prevent page load failure

    $pagetitle = '';
    $pagesubtitle = 'Domain Services Management';
    $pagetype = '';

    use Dotenv\Dotenv;

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    $discord_dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . "/modules/NexureSolutions/DomainManagement/checker");
    $discord_dotenv->load();

    $bannerMessage = '';
    $bannerStyle = '';

    if (isset($_GET['domain'])) {

        $domain = $_GET['domain'];
        $apiKey = $_ENV['GODADDY_KEY'];
        $apiSecret = $_ENV['GODADDY_SECRET'];

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

        // Set the banner message and style

        if ($availability['available'] ?? false) {

            $bannerMessage = "The domain <strong>{$domain}</strong> is available for purchase.";
            $bannerStyle = "background-color:#D0FFBD; color:#000; padding: 15px; text-align:center; border-radius: 5px;";

        } else {

            $bannerMessage = "Oh no! The domain <strong>{$domain}</strong> is already registered.";
            $bannerStyle = "background-color:#FFBDBD; color:#000; padding: 15px; text-align:center; border-radius: 5px;";

        }

    }

?>

<!-- Banner Section -->
<?php if (!empty($bannerMessage)): ?>
    <div class="domain-banner" style="<?php echo $bannerStyle; ?>">
        <?php echo $bannerMessage; ?>
    </div>
<?php endif; ?>

<!-- HTML Content for administrators view -->
<section class="section first-dashboard-area-cards" style="padding-top:1%;">
    <div class="container width-98">
        <div class="caliweb-one-grid special-caliweb-spacing">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card custom-padding-account-card" style="padding-bottom:0;">
                    <div class="card-header-services" style="margin-bottom:0;">
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
                        <div class="domain-search" style="width:60%;">
                            <form action="" method="GET">
                                <div>
                                    <input type="text" class="form-input" maxlength="256" name="domain" placeholder="Search for a domain e.g example.com" id="domain" required="" />
                                    <button class="caliweb-button primary" style="outline:0; margin-top:4%; padding:8px 24px;" type="submit" name="submit">Check Domain</button>
                                </div>
                            </form>

                            <?php
                            // The result is now shown at the top in the banner
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include($_SERVER["DOCUMENT_ROOT"] . '/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');
?>