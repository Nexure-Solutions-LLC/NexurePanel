<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $pagetitle = "Cali Mail";
    $pagesubtitle = "Inbox";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';
    
?>

    <section class="section first-dashboard-area-cards" style="overflow:hidden; height:90vh;">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-grid caliweb-two-grid email-grid">
                    <div class="caliweb-card dashboard-card caliweb-email-listing-container back-dark-mode">

                        <?php

                            include($_SERVER["DOCUMENT_ROOT"].'/dashboard/administration/email/requiredResources/emailAuthentication/index.php');

                            if (mysqli_num_rows($emailWebClientLoginResult) > 0) {

                                while ($emailWebClientLoginRow = mysqli_fetch_assoc($emailWebClientLoginResult)) {

                                    // Gets the info from a table that unfortuantly isnt encrypted but will be very shortly
                                    // then gets the email 1st part and the email 2nd part and builds an email address
                                    // to authenitcate with.

                                    $caliMailUsername = $emailWebClientLoginRow['email'];
                                    $caliMailDomain = $emailWebClientLoginRow['domain'];
                                    $caliMailPasword = $emailWebClientLoginRow['password'];
                                    $caliMailBuiltEmail = $caliMailUsername.'@'.$caliMailDomain;

                                    $inbox = imap_open($hostName, $caliMailBuiltEmail, $caliMailPasword) or die('Cannot connect to IMAP server: ' . imap_last_error());
                                    $emails = imap_search($inbox, 'ALL');


                                    include($_SERVER["DOCUMENT_ROOT"].'/dashboard/administration/email/requiredResources/requiredFunctions/index.php');

                                    include($_SERVER["DOCUMENT_ROOT"].'/dashboard/administration/email/requiredResources/sideViewMessage/index.php');
                                    

                                }

                            } else {

                                header("location:/error/genericSystemError");

                            }
                        ?>
                        
                    </div>

                    <div class="caliweb-card dashboard-card caliweb-email-content-container back-dark-mode" id="email-content">
                        <div class="center-image-content">
                            <img src="<?php echo $variableDefinitionX->orglogolight; ?>" class="caliweb-navbar-logo-img mail-logo-content light-mode" style="width:20%; margin-top:12%;" />
                            <img src="<?php echo $variableDefinitionX->orglogodark; ?>" class="caliweb-navbar-logo-img mail-logo-content dark-mode" style="width:20%; margin-top:12%;" />
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <div id="accountModal" class="modal">
        <div class="modal-content" style="margin-bottom: 0 !important; margin-top:0% !important;">
            <h6 style="font-size:14px; font-weight:600; padding:0; margin:0;">Email Details</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">No Details.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <button class="caliweb-button primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        function loadEmailContent(emailNumber) {

            fetch(`/dashboard/administration/email/requiredResources/proccessMessage/?emailNumber=${emailNumber}`)

                .then(response => {

                    console.log('Response:', response);

                    if (!response.ok) {

                        throw new Error('Network response was not ok ' + response.statusText);
                    }

                    return response.text();

                })

                .then(data => {

                    document.getElementById('email-content').innerHTML = data;

                })

                .catch(error => console.error('Error:', error));
        }

        var modal = document.getElementById("accountModal");

        function openModal() {
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>

<?php

include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>