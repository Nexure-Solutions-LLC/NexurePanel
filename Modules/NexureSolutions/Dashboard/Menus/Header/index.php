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
            'Home' => '/Dashboard/Administration/',
            'Tasks' => '/Dashboard/Administration/Tasks',
            'Leads' => '/Dashboard/Administration/Leads',
            'Accounts' => '/Dashboard/Administration/Accounts',
            'Campaigns' => '/Dashboard/Administration/Campaigns',
            'Contacts' => '/Dashboard/Administration/Contacts',
            'Cases' => '/Dashboard/Administration/Cases',
            'Sign Off' => '/Logout'
        ];

        // Define department visibility

        $departmentVisibility = [
            'Support Department' => ['Home', 'Tasks', 'Cases', 'Contacts'],
            'Sales Department' => ['Home', 'Tasks', 'Leads', 'Campaigns', 'Contacts'],
            'Accounting Department' => ['Home', 'Tasks', 'Accounts'],
            'Billing Department' => ['Home', 'Tasks', 'Accounts', 'Contacts', 'Cases'],
            'Board of Directors' => array_keys($adminLinks),
            'Development Department' => array_keys($adminLinks),
        ];

        echo '<nav class="nexure-navbar-menu display-flex align-center" id="nexure-navbar-js">';

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
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Dashboard</p>';
                renderAdminNavLinks('Dashboard', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            case "Tasks":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Tasks</p>';
                renderAdminNavLinks('Tasks', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            case "Leads":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Leads</p>';
                renderAdminNavLinks('Leads', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            case "Contacts":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Contacts</p>';
                renderAdminNavLinks('Contacts', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            case "Customer Accounts":
            case "Services":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Customer Accounts</p>';
                renderAdminNavLinks('Manage Accounts', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            case "Connected Payments":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Payments</p>';
                renderPaymentNavLinks('Dashboard', $accountNumber, $paymentModules);
                echo '</div>';
                break;
            case "Cases":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Cases</p>';
                renderAdminNavLinks('Cases', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            case "Campaigns":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Campaigns</p>';
                renderAdminNavLinks('Campaigns', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            case "Payroll":
                echo '<div class="display-flex align-center"><p class="no-margin no-padding" style="padding-right:20px; padding-top:2px; font-weight:500;">Payroll</p>';
                renderAdminNavLinks('Payroll', $adminModules, $CurrentOnlineAccessAccountRole);
                echo '</div>';
                break;
            default:
                renderAdminNavLinks('Dashboard', $adminModules, $CurrentOnlineAccessAccountRole);
                break;
        }
        
    } else {

        renderCustomerNavLinks('Dashboard', $accountNumber);

    }

?>
