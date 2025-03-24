<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    if (!isset($accountNumber)) {

?>

        <!-- HTML Code for if the user does not submit an account number. -->

<?php 

    } else { 

        $accountNumber = $_GET["account_number"];

        $result = $con->query("SELECT * FROM nexure_users WHERE accountNumber = '$accountNumber'");

        if ($result->num_rows == 0) {

            redirect("/error/genericSystemError");

        }

        $user = $result->fetch_assoc();
        
?>

        <!-- HTML Code for if the user submits a account number. -->

<?php

    }

?>