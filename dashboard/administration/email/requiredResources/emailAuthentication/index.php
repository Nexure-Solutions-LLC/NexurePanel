<?php

    if ($pagetitle == "Cali Mail") {

        // Autenticate into the email server.

        $hostName = '{mail.NexureSolutionsservices.com:993/imap/ssl}INBOX'; // DO NOT MODIFY THIS LINE

        $emailWebClientLoginQuery = "SELECT * FROM nexure_calimail WHERE assignedUser = '$caliemail'";
        $emailWebClientLoginResult = mysqli_query($con, $emailWebClientLoginQuery);

    } else {

        header("location:/error/genericSystemError");

    }

?>