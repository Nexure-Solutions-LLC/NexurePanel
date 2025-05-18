<?php
    
    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Backend/index.php");

    $CurrentOnlineAccessAccount =  new \NexureSolutions\Accounts\AccountHandler($con);

    $CurrentOnlineAccessAccount->GatherOnlineAccessInformation($con, $nexureid);

    $upperAccessType = $CurrentOnlineAccessAccount->accessType;

    $lowerAccessType = strtolower($upperAccessType);

    switch ($lowerAccessType) {
        case "authorized user":
            header("location:/Dashboard/AuthorizedUser");
            break;
        case "partner":
            header("location:/Dashboard/Partners");
            break;
        case "administrator":
            header("location:/Dashboard/Administration");
            break;
        case 'customer':
            header("location:/Dashboard/Customer");
            break;
        default:
            header("Location: /ErrorHandling/ErrorPages/GenericError");
            break;
    }

?>