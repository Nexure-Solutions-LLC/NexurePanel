<?php
    session_start();

    if(!isset($_SESSION["nexureid"])) {

        if ($pagetitle == "Linked Roles") {

            header("Location: /Login/?referral_url=/Modules/NexureSolutions/Discord/LinkedRoles");
            exit();

        } else {

            header("Location: /Login");
            exit();

        }

    }
    
?>