<?php
    $pagetitle = "Client";
    $pagesubtitle = "Order Services";
    $pagetype = "Client";

    $accountnumber = $_GET['account_number'];

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>



<?php include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php'); ?>