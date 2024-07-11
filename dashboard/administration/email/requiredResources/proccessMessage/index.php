<?php

    $pagetitle = "Cali Mail";
    $pagesubtitle = "Message Proccessing Script";

    // System Imports that are required

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/dashboard/administration/email/requiredResources/requiredFunctions/index.php');

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
            $message = imap_fetchbody($inbox, $emailNumber, 2);
            
            // Decode the email content if necessary
            $message = quoted_printable_decode($message);
            
            $subject = decodeMimeStr($overview->subject);
            $date = formatDate($overview->date);
            $from = $overview->from;
            $to = $overview->to;

            echo $message;

            imap_close($inbox);
        }
        
    } else {

        header("location:/error/genericSystemError");

    }

?>