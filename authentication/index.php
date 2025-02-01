<?php
    session_start();

    if(!isset($_SESSION["caliid"])) {

        if ($pagetitle == "Linked Roles") {

            header("Location: /login/?referral_url=/modules/NexureSolutions/Discord/linkedRoles");
            exit();

        } else {

            header("Location: /login");
            exit();

        }

    }
    
?>