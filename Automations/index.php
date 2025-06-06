<?php

require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/EnvironmentFile/index.php');

require($_SERVER["DOCUMENT_ROOT"] . '/Modules/NexureSolutions/Configuration/Database/index.php');

/**
 * Automatically close accounts that have been restricted for over 30 days
*/

function autoCloseRestrictedAccounts($con) {

    $now = new DateTime();

    $sql = "SELECT accountNumber, restrictedDate FROM nexure_accounts WHERE status = 'restricted' AND restrictedDate IS NOT NULL";

    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {

            $restrictedDate = new DateTime($row['restrictedDate']);

            $interval = $restrictedDate->diff($now);

            if ($interval->days >= 30) {

                $accountNumber = mysqli_real_escape_string($con, $row['accountNumber']);

                $update = "UPDATE nexure_accounts SET status = 'closed' WHERE accountNumber = '$accountNumber'";

                mysqli_query($con, $update);

            }

        }

    }

}

autoCloseRestrictedAccounts($con);

?>
