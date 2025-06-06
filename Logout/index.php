<?php

    ob_start();
    
    session_start();

    // Destroy session

    // Maintain language

    if (isset($_SESSION["lang"])) {

        $lang = $_SESSION["lang"];
        
    } else {

        $lang = "EN_US";

    }

    if(session_destroy()) {

        session_start();

        $_SESSION["lang"] = $lang;

        // Redirecting To Home Page

        header("Location: /Logout/Success");

    }
    
?>