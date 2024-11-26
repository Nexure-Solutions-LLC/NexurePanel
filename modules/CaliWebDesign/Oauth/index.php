<?php

    // Cali Web Design OAuth Module
    // Version: 1.0.4
    // (C) Copyright Nexure Solutions LLC - All rights reserved
    // DISMANTLING, REVERSE ENGINEERING, OR MODIFICATION OF THIS MODULE IS PROHIBITED.

    // This module is included in the Cali Panel software by default
    // the Cali Web Design OAuth Module will allow you to authenticate
    // using your external accounts into the Cali Panel for example
    // Google, Discord, Clever, and Okta. This can save time instead
    // of needing to fill our registration form our you can just quickly
    // authenticate.

    // Load Custom login Modules Such as Sign-In with Discord, Google, or Okta Authentication

    $authModulesLookupQuery = "SELECT * FROM nexure_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
    $authModulesLookupResult = mysqli_query($con, $authModulesLookupQuery);

    if (mysqli_num_rows($authModulesLookupResult) > 0) {

        while ($authModulesLookupRow = mysqli_fetch_assoc($authModulesLookupResult)) {

            $authModulesName = $authModulesLookupRow['moduleName'];

            // Check to see if each OAuth Modules are enabled and sets the dependency to Present
            // This extra check is to prevent users from trying to load a module from the
            // URL bar directly, it requires this module to load it.

            $pathPrefix = $_SESSION['pagetitle'] == "Account Management" ? "/accountAuthorization" : "";

            switch ($authModulesName) {
                case "Discord OAuth":
                    $authModulesDepends = "Present";
                    include $_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Oauth/discord{$pathPrefix}/index.php";
                    break;
                case "GitHub OAuth":
                    $authModulesDepends = "Present";
                    include $_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Oauth/github{$pathPrefix}/index.php";
                    break;
                case "Google OAuth":
                    $authModulesDepends = "Present";
                    include $_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Oauth/google{$pathPrefix}/index.php";
                    break;
                case "Okta OAuth":
                    $authModulesDepends = "Present";
                    include $_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Oauth/okta{$pathPrefix}/index.php";
                    break;
                case "Apple OAuth":
                    $authModulesDepends = "Present";
                    include $_SERVER["DOCUMENT_ROOT"]."/modules/CaliWebDesign/Oauth/apple{$pathPrefix}/index.php";
                    break;
            }
        }
    }
  
?>
