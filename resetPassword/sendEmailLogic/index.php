<?php

    // Set the type of script so that the panel knows what email template
    // to use. Installers of this panel can chnage the email templates
    // in the /modules/emailIntegrations/templates.

    $scriptType = "Reset Password";

    $emailVerificationCode = $_SESSION['verification_code'];

    // Include the email templates loader file

    include($_SERVER["DOCUMENT_ROOT"]."/modules/emailIntegrations/index.php"); 

?>