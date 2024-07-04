<?php

    $outsidesitecalled = False;
    $oauthkey = "";
    $siteid = "";

    if ($siteid && $oauthkey == "" || $siteid && $oauthkey == NULL) {

    } else {

        // Loads the Cali Web Design Login Button when called.

        if ($outsidesitecalled == "True") {
            echo '<button class="" name="submit" id="caliwebdesignoauthbutton">Login with Cali Web Design</button>';
        } else {
            echo '';
        }
    }

?>