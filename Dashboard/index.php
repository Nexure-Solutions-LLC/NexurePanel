<?php
    
    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Backend/index.php");

    $VariableDefinitionsG = new \NexureSolutions\Generic\VariableDefinitions();

    $VariableDefinitionsG->GatherOnlineAccessInformation($con, $nexureid);

    $upperAccessType = $VariableDefinitionsG->accessType;

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
        case 'personal':
            header("location:/Dashboard/Personal");
            break;
        case 'business':
            header("location:/Dashboard/Business");
            break;
        default:
            header("Location: /ErrorHandling/ErrorPages/GenericError");
            break;
    }

?>