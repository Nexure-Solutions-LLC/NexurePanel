<?php

    function getActiveModulesByFunction($con, $functionName) {

        $sql = "SELECT * FROM nexure_modules WHERE moduleStatus = 'Active' AND `function` = ?";

        $stmt = mysqli_prepare($con, $sql);

        mysqli_stmt_bind_param($stmt, "s", $functionName);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $modules = [];

        if ($result) {

            while ($row = mysqli_fetch_assoc($result)) {

                $modules[] = $row;

            }

        }

        mysqli_stmt_close($stmt);

        return $modules;

    }

    function renderCustomerNavLinks($activeLink, $accountNumber = null) {

        $links = [
            'Dashboard' => '/',
            'Open an account' => '/Onboarding',
            'Billing' => '/Dashboard/Customer/Billing',
            'Support Center' => '/Dashboard/Customer/SupportCenter',
            'Access & Security' => '/Dashboard/Customer/SecurityCenter',
            'Service Information' => '/Dashboard/Customer/ServiceInformation',
            'Sign Off' => '/Logout'
        ];

        echo '<nav class="nexure-navbar-menu display-flex align-center" id="nexure-navbar-js">';

        echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Dashboard</p>';

        foreach ($links as $name => $url) {

            $activeClass = ($activeLink === $name) ? 'active' : '';

            echo "<li class=\"nav-links\"><a class=\"$activeClass\" href=\"$url\">$name</a></li>";

        }

        echo '</nav>';
    }

    function renderAdminNavLinks($activeLink, $modules, $CurrentOnlineAccessAccountRole) {

        $adminLinks = [
            'Dashboard' => '/Dashboard/Admin',
            'Manage Accounts' => '/Dashboard/Admin/Accounts',
            'Create Account' => '/Dashboard/Admin/AccountCreate',
            'Billing Overview' => '/Dashboard/Admin/BillingOverview',
            'Support Dashboard' => '/Dashboard/Admin/Support',
            'Sign Off' => '/Logout'
        ];

        echo '<nav class="nexure-navbar-menu display-flex align-center" id="nexure-navbar-js">';

        echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Admin</p>';

        foreach ($adminLinks as $name => $url) {
            
            $activeClass = ($activeLink === $name) ? 'active' : '';

            echo "<li class=\"nav-links $activeClass\"><a href=\"$url\">$name</a></li>";

        }

        if (!empty($modules)) {

            echo '<li class="nav-links more">';

            echo '<a class="nav-links-clickable more-button" href="#">More</a>';

            echo '<ul class="dropdown">';

            foreach ($modules as $module) {
                $friendlyName = htmlspecialchars($module['moduleName']);
                $modulePath = htmlspecialchars($module['modulePath']);
                echo "<li class=\"nav-links\"><a href=\"$modulePath\" class=\"nav-links-clickable\">$friendlyName</a></li>";
            }

            echo '</ul></li>';

        }

        echo '</nav>';
    }

    function renderPaymentNavLinks($activeLink, $accountNumber, $modules) {

        $basePath = "/Modules/NexureSolutions/FinancialServices";

        $paymentLinks = [
            'Home' => "$basePath/Home/?account_number=$accountNumber",
            'Transactions' => "$basePath/Transactions/?account_number=$accountNumber",
            'Refunds' => "$basePath/Refunds/?account_number=$accountNumber",
            'Disputes' => "$basePath/Disputes/?account_number=$accountNumber",
            'Invoices' => "$basePath/Invoices/?account_number=$accountNumber",
            'Tax' => "$basePath/Tax/?account_number=$accountNumber",
            'Reports' => "$basePath/Reports/?account_number=$accountNumber",
            'Sign Off' => '/Logout'
        ];

        echo '<nav class="nexure-navbar-menu display-flex align-center" id="nexure-navbar-js">';

        echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Payments</p>';

        foreach ($paymentLinks as $name => $url) {

            $activeClass = ($activeLink === $name) ? 'active' : '';

            echo "<li class=\"nav-links\"><a class=\"$activeClass\" href=\"$url\">$name</a></li>";

        }

        if (!empty($modules)) {

            echo '<li class="nav-links more">';

            echo '<a class="nav-links-clickable more-button" href="#">More</a>';

            echo '<ul class="dropdown">';

            foreach ($modules as $module) {

                $friendlyName = htmlspecialchars($module['moduleName']);

                $modulePath = htmlspecialchars($module['modulePath']);

                echo "<li class=\"nav-links\"><a href=\"$modulePath\" class=\"nav-links-clickable\">$friendlyName</a></li>";

            }

            echo '</ul></li>';

        }

        echo '</nav>';
    }


    global $con, $CurrentOnlineAccessAccount, $PageTitle, $PageSubtitle;

    $CurrentOnlineAccessAccountRole = $CurrentOnlineAccessAccount->role->name ?? null;

    $adminModules = getActiveModulesByFunction($con, 'Administration');

    $paymentModules = getActiveModulesByFunction($con, 'FinancialServices');

    $userRole = $CurrentOnlineAccessAccount->fromUserRole($CurrentOnlineAccessAccount->accessType);

    if ($userRole === "Customer") {

        switch ($PageSubtitle) {
            case "Overview":
            case "Account Overview":
                renderCustomerNavLinks('Dashboard', $accountNumber);
                break;
            case "Billing Center":
                renderCustomerNavLinks('Billing', $accountNumber);
                break;
            case "Order Services":
                renderCustomerNavLinks('Open an account', $accountNumber);
                break;
            case "Service Status":
                renderCustomerNavLinks('Service Information', $accountNumber);
                break;
            case "Access and Security":
                renderCustomerNavLinks('Access & Security', $accountNumber);
                break;
            case "Customer Service":
                renderCustomerNavLinks('Support Center', $accountNumber);
                break;
            default:
                renderCustomerNavLinks('Dashboard', $accountNumber);
                break;
        }

    } elseif ($userRole === "Administrator") {

        switch ($PageTitle) {
            case "Dashboard":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Dashboard</p>';
                renderAdminNavLinks('Dashboard', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            case "Tasks":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Tasks</p>';
                renderAdminNavLinks('Tasks', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            case "Leads":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Leads</p>';
                renderAdminNavLinks('Leads', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            case "Contacts":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Contacts</p>';
                renderAdminNavLinks('Contacts', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            case "Customer Accounts":
            case "Services":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Customer Accounts</p>';
                renderAdminNavLinks('Manage Accounts', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            case "Connected Payments":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Payments</p>';
                renderPaymentNavLinks('Dashboard', $accountNumber, $paymentModules);
                break;
            case "Cases":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Cases</p>';
                renderAdminNavLinks('Cases', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            case "Campaigns":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Campaigns</p>';
                renderAdminNavLinks('Campaigns', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            case "Payroll":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Payroll</p>';
                renderAdminNavLinks('Payroll', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
            default:
                renderAdminNavLinks('Dashboard', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
        }
        
    } else {

        renderCustomerNavLinks('Dashboard', $accountNumber);

    }

?>
