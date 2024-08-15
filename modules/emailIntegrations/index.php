<?php

    /*

        This is the email integrations module loader, this will determine script
        type and load the most relevent email template, we do it this way so that
        users can chnage the templates of the emails without messing with the panel
        functions. 

        (C) Cali Web Design Services 2024 - It is illegal to copy, reproduce, 
        host, decompile or disassemble this software without permission from 
        Cali Web Design.

        This loader will work with any Cali Web Design Panel Software or versions.

    */

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    $panelresult = mysqli_query($con, "SELECT * FROM caliweb_panelconfig WHERE id = 1");
    $panelinfo = mysqli_fetch_array($panelresult);
    mysqli_free_result($panelresult);

    $variableDefinitionX->panelName = $panelinfo['panelName'];
    $variableDefinitionX->orgShortName = $panelinfo['organizationShortName'];
    $variableDefinitionX->orglogolight = $panelinfo['organizationLogoLight'];
    $variableDefinitionX->orglogodark = $panelinfo['organizationLogoDark'];

    function guidv4($data = null) {

        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.

        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);
    
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    
    $emailID = guidv4();

    $emailVerificationCode = $_SESSION['verification_code'];

    switch ($scriptType) {
        case "Reset Password":
            include($_SERVER["DOCUMENT_ROOT"].'/modules/emailIntegrations/templates/resetPassword/index.php');
            break;
        case "Account Opened":
            include($_SERVER["DOCUMENT_ROOT"].'/modules/emailIntegrations/templates/openedAccount/index.php');
            break;
        case "Account Closed":
            include($_SERVER["DOCUMENT_ROOT"].'/modules/emailIntegrations/templates/closedAccount/index.php');
            break;
        case "Account Denied":
            include($_SERVER["DOCUMENT_ROOT"].'/modules/emailIntegrations/templates/deniedAccount/index.php');
            break;
        case "Accout Suspended":
            include($_SERVER["DOCUMENT_ROOT"].'/modules/emailIntegrations/templates/suspendedAccount/index.php');
            break;
        case "Account Terminated":
            include($_SERVER["DOCUMENT_ROOT"].'/modules/emailIntegrations/templates/terminatedAccount/index.php');
            break;
    }
    
?>