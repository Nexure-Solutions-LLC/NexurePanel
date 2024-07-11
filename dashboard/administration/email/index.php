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
                    <?php

                        // Autenticate into the email server.

                        $hostName = '{mail.caliwebdesignservices.com:993/imap/ssl}INBOX'; // DO NOT MODIFY THIS LINE

                        $emailWebClientLoginQuery = "SELECT * FROM caliweb_calimail WHERE assignedUser = '$caliemail'";
                        $emailWebClientLoginResult = mysqli_query($con, $emailWebClientLoginQuery);

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

                                // Check for subject thats non-standard encoding

                                function decodeMimeStr($string, $charset = 'UTF-8') {

                                    if (preg_match_all('/=\?([^?]+)\?(B|Q)\?([^?]+)\?=/i', $string, $matches)) {

                                        $decoded_string = '';

                                        for ($i = 0; $i < count($matches[0]); $i++) {

                                            $encoding = strtoupper($matches[2][$i]);
                                            $data = $matches[3][$i];

                                            switch ($encoding) {
                                                case 'B':
                                                    $decoded_string .= base64_decode($data);
                                                    break;
                                                case 'Q':
                                                    $decoded_string .= quoted_printable_decode(str_replace('_', ' ', $data));
                                                    break;
                                            }

                                        }

                                        return mb_convert_encoding($decoded_string, $charset, $matches[1][0]);

                                    } else {

                                        return $string;
                                    }

                                }

                                // This formats the date to MM/DD/YYYY HH:MM AM/PM

                                function formatDate($dateStr) {

                                    $date = DateTime::createFromFormat('D, d M Y H:i:s O', $dateStr);
                                    return $date ? $date->format('m/d/y h:i A') : 'Unknown date';

                                }

                                // Create a listing for all the emails that come in.

                                echo '
                                    <div class="caliweb-card dashboard-card caliweb-email-listing-container back-dark-mode">
                                ';

                                        if ($emails) {
                                            rsort($emails); 

                                            foreach ($emails as $email_number) {
                                                $overview = imap_fetch_overview($inbox, $email_number, 0);
                                                $date = isset($overview[0]->date) ? formatDate($overview[0]->date) : 'Unknown date';

                                                $sender = decodeMimeStr($overview[0]->from);     

                                                echo '
                                                    <div class="caliweb-email-listing" onclick="loadEmailContent('.$email_number.')">
                                                        <div class="caliweb-email-listing-header display-flex align-center" style="justify-content:space-between">
                                                            <div>
                                                                <p style="font-size:12px; font-weight:300;">'.$sender.'</p>
                                                            </div>
                                                            <div>
                                                                <p style="font-size:12px; font-weight:300;">'.$date.'</p>
                                                            </div>
                                                        </div>
                                                        <div class="caliweb-email-listing-body">
                                                            <p style="font-size:12px; font-weight:700;">

                                                ';
                                                                if ($overview[0]->subject == "") {
                                                                    
                                                                    echo '(No Subject)';

                                                                } else {

                                                                    $subject = decodeMimeStr($overview[0]->subject);

                                                                    echo $subject;

                                                                }          
                                                echo '

                                                            </p>
                                                        </div>
                                                    </div>
                                                ';
                                            }
                                        }

                                echo '</div>';

                                if (mysqli_num_rows($emailWebClientLoginResult) > 0) {

                                    $emailNumber = isset($_GET['emailNumber']) ? intval($_GET['emailNumber']) : null;

                                    if ($emailNumber !== null) {
                                
                                        while ($emailWebClientLoginRow = mysqli_fetch_assoc($emailWebClientLoginResult)) {
                                    
                                            $caliMailUsername = $emailWebClientLoginRow['email'];
                                            $caliMailDomain = $emailWebClientLoginRow['domain'];
                                            $caliMailPasword = $emailWebClientLoginRow['password'];
                                            $caliMailBuiltEmail = $caliMailUsername.'@'.$caliMailDomain;
                                    
                                            $inbox = imap_open($hostName, $caliMailBuiltEmail, $caliMailPasword) or die('Cannot connect to IMAP server: ' . imap_last_error());
                                            $overview = imap_fetch_overview($inbox, $emailNumber, 0)[0];
                                            $message = imap_fetchbody($inbox, $emailNumber, 2);
                                            $message = quoted_printable_decode($message);
                                            
                                            $subject = decodeMimeStr($overview->subject);
                                            $date = formatDate($overview->date);
                                            $from = $overview->from;
                                            $to = $overview->to;

                                            echo '
                                                <div id="email-content" class="caliweb-card dashboard-card caliweb-email-content-container back-dark-mode">
                                                    <div class="caliweb-email-header">
                                                        <div class="card-header">
                                                            <div class="display-flex align-center" style="justify-content: space-between;">
                                                                <div class="display-flex align-center">
                                                                    <div class="no-padding margin-10px-right icon-size-formatted">
                                                                        <img src="/assets/img/systemIcons/defaultprofileimage.jpg" alt="Email Profile Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                                                    </div>
                                                                    <div>
                                                                        <p class="no-padding font-14px">Ray Ban</p>
                                                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Hottest Sunglasses at Only $27.99 - Shop Now!</h4>
                                                                        <div class="display-flex align-center" style="margin-top:5px;">
                                                                            <p class="no-padding font-14px">'.$from.'</p>
                                                                            <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">'.$subject.'</h4>
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

                                                        <div class="caliweb-email-body">
                                                            '.$message.'
                                                        </div>
                                            ';

                                        }
                                    
                                    } else {
                                        echo '

                                                <div id="email-content" class="caliweb-card dashboard-card caliweb-email-content-container back-dark-mode">

                                                    <div class="center-image-content">
                                                        <img src="'.$orglogolight.'" class="caliweb-navbar-logo-img mail-logo-content light-mode" style="width:20%; margin-top:12%;" />
                                                        <img src="'.$orglogodark.'" class="caliweb-navbar-logo-img mail-logo-content dark-mode" style="width:20%; margin-top:12%;" />
                                                    </div>
                                        ';
                                    }

                                } else {

                                    echo 'Error: Unable to fetch email details.';

                                }
                                    

                                echo '
                                                </div>
                                            </div>
                                ';

                                imap_close($inbox);

                            }

                        } else {
                            header("location:/error/genericSystemError");
                        }
                    ?>
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
            fetch(`/dashboard/administration/email/?emailNumber=${emailNumber}`)
                .then(response => response.text())
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

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>