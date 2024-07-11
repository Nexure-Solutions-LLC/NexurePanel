<?php

    $pagetitle = "Cali Mail";
    $pagesubtitle = "Inbox";

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardHeader.php');

    if ($userrole == "Customer" || $userrole == "customer") {
        
        header("location:/dashboard/customers/emailSuite");

    } else if ($userrole == "Authorized User" || $userrole == "authorized user") {

        header("location:/dashboard/customers/authorizedUserView/emailSuite");

    } else if ($userrole == "Partner" || $userrole == "partner") {

        header("location:/dashboard/partnerships/emailSuite");

    }

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
    
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

                    <div class="caliweb-card dashboard-card caliweb-email-content-container back-dark-mode">
                        <div class="caliweb-email-header">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content: space-between;">
                                    <div class="display-flex align-center">
                                        <div class="no-padding margin-10px-right icon-size-formatted">
                                            <img src="/assets/img/systemIcons/defaultprofileimage.jpg" alt="Email Profile Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                        </div>
                                        <div>
                                            <p class="no-padding font-14px">'.$from.'</p>
                                            <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">'.$subject.'</h4>
                                            <div class="display-flex align-center" style="margin-top:5px;">
                                                <div class="display-flex align-center" style="margin-top:5px;">
                                                    <div>
                                                        <p style="font-size:12px; font-weight:300;">Date: '.$date.'</p>
                                                    </div>
                                                    <span style="margin-right:5px; margin-left:5px;">|</span>
                                                    <div>
                                                        <p style="font-size:12px; font-weight:300;">To: '.$to.'</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <a class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" href="javascript:void(0);" onclick="openModal()">Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="caliweb-email-body" id="email-content"></div>

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

                    console.log('Data:', data);
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

    

?>