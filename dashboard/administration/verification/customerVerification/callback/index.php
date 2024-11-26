<?php

    include($_SERVER["DOCUMENT_ROOT"]."/lang/en_US.php");
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    session_start();

    $accountnumber = $_GET['account_number'];
    $passorginURL = "/dashboard/administration/accounts/manageAccount/?account_number=$accountnumber";
    $customerAccountQuery = mysqli_query($con, "SELECT * FROM nexure_users WHERE accountNumber = '".$accountnumber."'");
    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
    mysqli_free_result($customerAccountQuery);

    if ($customerAccountInfo != NULL) {

        $customerSystemID = $customerAccountInfo['id'];
        $customerPhoneNumber = $customerAccountInfo['mobileNumber'];
        $customerStatus = $customerAccountInfo['accountStatus'];
        $dbaccountnumber = $customerAccountInfo['accountNumber'];

        if ($accountnumber != $dbaccountnumber) {

            echo '<script>window.location.href = "/dashboard/administration/accounts";</script>';

        }

    } else {

        echo '<script>window.location.href = "/dashboard/administration/accounts";</script>';

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $current_time = time();

        // Check if the last submission time is stored in the session
        
        if (isset($_SESSION['last_submit_time'])) {

            $time_diff = $current_time - $_SESSION['last_submit_time'];

            if ($time_diff < 5) {

                header("Location: /error/rateLimit");
                exit;

            }
        }

        // If the rate limit check passes, update the last submission time

        $_SESSION['last_submit_time'] = $current_time;

        $enteredCode = $_POST['verification_code'];

        if (isset($_SESSION['verification_code']) && $enteredCode == $_SESSION['verification_code']) {

            $enteredCode = $_SESSION['verification_code'];
            header('Location: /dashboard/administration/accounts/manageAccount/?account_number='.$accountnumber);
            exit();

        } else {

            // Verification failed
            
            header('Location: /error/genericSystemError');

        }
        
    }

?>