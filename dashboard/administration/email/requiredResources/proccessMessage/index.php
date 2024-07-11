<?php

    $pagetitle = "Cali Mail";
    $pagesubtitle = "Message Proccessing Script";

    // System Imports that are required

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');
    include($_SERVER["DOCUMENT_ROOT"].'/dashboard/administration/email/requiredResources/requiredFunctions/index.php');

    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);

    // Set Cali ID and Authenticate

    $caliemail = $_SESSION['caliid'];

    include($_SERVER["DOCUMENT_ROOT"].'/dashboard/administration/email/requiredResources/emailAuthentication/index.php');

    // Using authenication check to see if it was successful

    if (mysqli_num_rows($emailWebClientLoginResult) > 0) {

        $emailNumber = intval($_GET['emailNumber']);

        while ($emailWebClientLoginRow = mysqli_fetch_assoc($emailWebClientLoginResult)) {

            $caliMailUsername = $emailWebClientLoginRow['email'];
            $caliMailDomain = $emailWebClientLoginRow['domain'];
            $caliMailPasword = $emailWebClientLoginRow['password'];
            $caliMailBuiltEmail = $caliMailUsername.'@'.$caliMailDomain;

            $inbox = imap_open($hostName, $caliMailBuiltEmail, $caliMailPasword) or die('Cannot connect to IMAP server: ' . imap_last_error());
            $overview = imap_fetch_overview($inbox, $emailNumber, 0)[0];
            $structure = imap_fetchstructure($inbox, $emailNumber);

            // Extract all parts

            extractParts($structure, 1, $inbox, $emailNumber, $plainText, $htmlText);

            // Choose HTML if available, otherwise use plain text

            $message = !empty($htmlText) ? $htmlText : nl2br($plainText);
            $clean_html = $purifier->purify($message);

            $subject = decodeMimeStr($overview->subject);
            $date = formatDate($overview->date);
            $from = decodeMimeStr($overview->from);
            $to = decodeMimeStr($overview->to);

            echo '
            
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
                            <div>
                                <a class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" href="javascript:void(0);" onclick="openModal()">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="caliweb-email-body">

                        <iframe sandbox="allow-same-origin allow-scripts" style="border:0; width:100%; height:95vh; overflow:hidden !important;" srcdoc="'.htmlspecialchars($clean_html).'"></iframe>

                    </div>
                </div>
            
            ';

            imap_close($inbox);
        }
        
    } else {

        header("location:/error/genericSystemError");

    }

?>