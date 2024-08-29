<?php
    $pagetitle = "Administration Add Card to File";
    $pagesubtitle = "";
    $pagetype = "";

    require($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Utility/Backend/System/Dashboard.php");

    $accountnumber = $_SESSION['ACCOUNTNUMBERCUST'];

    $query = "SELECT * FROM caliweb_users WHERE accountNumber = '$accountnumber'";

    $result = mysqli_query($con, $query);

    if (!$result) {

        die('Error in query: ' . mysqli_error($con));

    }

    // Fetch the results

    $customerAccountInfo = mysqli_fetch_array($result);

    mysqli_free_result($result);

    $stripeid = $customerAccountInfo['stripeID'];

    $_SESSION['stripe_id'] = $stripeid;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Checks type of payment processor.

        $pagetitle = "Administration Add Card to File";
        $pagesubtitle = "";
        $pagetype = "";

        if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

            if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                include($_SERVER["DOCUMENT_ROOT"]."/modules/paymentModule/stripe/internalPayments/index.php");

            } else {

                header ("location: /error/genericSystemError");

            }

        } else {

            header ("location: /error/genericSystemError");

        }

    }

?>