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

    <section class="section first-dashboard-area-cards">
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

                                    if (preg_match('/=\?([^?]+)\?(B|Q)\?([^?]+)\?=/', $string)) {

                                        $elements = imap_mime_header_decode($string);
                                        $output = '';
                                
                                        foreach ($elements as $element) {

                                            if (strtolower($element->charset) === 'default') {

                                                $output .= $element->text;

                                            } else {

                                                $output .= mb_convert_encoding($element->text, $charset, $element->charset);

                                            }

                                        }
                                
                                        return $output;

                                    } else {

                                        return $string;

                                    }

                                }
                                

                                // Create a listing for all the emails that come in.

                                echo '
                                    <div class="caliweb-card dashboard-card">
                                ';

                                        if ($emails) {
                                            rsort($emails); 

                                            foreach ($emails as $email_number) {
                                                $overview = imap_fetch_overview($inbox, $email_number, 0);
                                                $date = isset($overview[0]->date) ? $overview[0]->date : 'Unknown date';

                                                echo '
                                                    <div class="caliweb-email-listing">
                                                        <div class="caliweb-email-listing-header display-flex align-center" style="justify-content:space-between">
                                                            <div>
                                                                <p style="font-size:12px; font-weight:300;">'.$overview[0]->from.'</p>
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

                                echo '
                                    </div>

                                    <div class="caliweb-card dashboard-card">
                                ';

                                echo '
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

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/dashboardFooter.php');

?>