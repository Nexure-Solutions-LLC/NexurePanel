<?php

    if ($pagetitle == "Cali Mail") {

        // Autenticate into the email server.

        $hostName = '{mail.caliwebdesignservices.com:993/imap/ssl}INBOX'; // DO NOT MODIFY THIS LINE

        $emailWebClientLoginQuery = "SELECT * FROM caliweb_calimail WHERE assignedUser = '$caliemail'";
        $emailWebClientLoginResult = mysqli_query($con, $emailWebClientLoginQuery);

    } else {

        header("location:/error/genericSystemError");

    }

?>