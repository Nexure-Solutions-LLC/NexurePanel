<?php

    // The Cali Web Design Panel allows for custom themeing
    // DO NOT MODIFY THE MAIN STYLESHEET IN ASSETS OF THE ROOT
    // DIRECTORY

    // USE OVERRIDES IN CSS TO MAKE STYLING CHANGES IN A THEME
    // FOLDER. CHANGES TO THE ROOT STYLESHEETS WILL BE
    // OVERWRITTEN DURING UPDATES.

    // THIS FILE LOADS IN THE THEMES DO NOT BREAK IT.

    // Perform query
    
    $panelthemeresult = mysqli_query($con, "SELECT * FROM nexure_panelconfig WHERE id = '1'");
    $panelthemeinfo = mysqli_fetch_array($panelthemeresult);
    
    // Free result set

    mysqli_free_result($panelthemeresult);

    $paneltheme = $panelthemeinfo['panelTheme'];

    if ($paneltheme == "") {

        // Do nothing if no themes are found and load default styles

    } else {

        include($_SERVER["DOCUMENT_ROOT"]."/dashboard/company/themes/".$paneltheme."/index.php");
    }


?>