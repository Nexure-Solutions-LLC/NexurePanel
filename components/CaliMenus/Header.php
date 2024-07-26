<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $sql = "SELECT * FROM caliweb_modules WHERE moduleStatus = 'Active' AND `modulePositionType` = 'Staff Function'";
    $moduleresult = mysqli_query($con, $sql);

    $departmentsql = "SELECT * FROM caliweb_payroll WHERE employeeEmail = '$currentAccount->email'";
    $departmentresult = mysqli_query($con, $departmentsql);

    function renderNavLinks($activeLink, $accountNumber = null) {

        $links = [
            'Overview' => '/dashboard/customers/',
            'Billing' => '/dashboard/customers/billingCenter?account_number=' . $accountNumber,
            'Access & Security Center' => '/dashboard/customers/accessAndSecurityCenter',
            'Service Status' => '/dashboard/customers/serviceStatus',
            'Customer Service' => '/dashboard/customers/supportCenter',
            'Sign Off' => '/logout'
        ];

        echo '<ul class="caliweb-nav-links">';

        foreach ($links as $name => $url) {

            $activeClass = $activeLink === $name ? 'active' : '';
            echo "<li class=\"nav-links $activeClass\"><a href=\"$url\" class=\"nav-links-clickable\">$name</a></li>";

        }

        echo '</ul>';
    }

    function renderAdminNavLinks($activeLink, $moduleresult, $departmentresult, $currentAccountRole) {

        if (mysqli_num_rows($departmentresult) > 0) {

            while ($departmentrow = mysqli_fetch_assoc($departmentresult)) {

                $adminLinks = [
                    'Home' => '/dashboard/administration/',
                    'Tasks' => '/dashboard/administration/tasks',
                    'Leads' => '/dashboard/administration/leads',
                    'Accounts' => '/dashboard/administration/accounts',
                    'Campaigns' => '/dashboard/administration/campaigns',
                    'Contacts' => '/dashboard/administration/contacts',
                    'Cases' => '/dashboard/administration/cases',
                    'Sign Off' => '/logout'
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

                // Determine which links to show based on department
                
                $visibleLinks = ($currentAccountRole === 'Executive') ? array_keys($adminLinks) : $departmentVisibility[$departmentrow['employeeDepartment']];
                
                echo '<ul class="caliweb-nav-links">';
                
                foreach ($adminLinks as $name => $url) {

                    if (in_array($name, $visibleLinks)) {

                        $activeClass = $activeLink === $name ? 'active' : '';
                        echo "<li class=\"nav-links $activeClass\"><a href=\"$url\" class=\"nav-links-clickable\">$name</a></li>";
                    
                    }
                    
                }

                echo '<li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">';
                
                if (mysqli_num_rows($moduleresult) > 0) {

                    while ($modulerow = mysqli_fetch_assoc($moduleresult)) {

                        echo '<li class="nav-links"><a href="'.$modulerow['modulePath'].'" class="nav-links-clickable">'.$modulerow['moduleFriendlyName'].'</a></li>';

                    }

                }

                echo '<li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>';
                echo '<li class="nav-links"><a href="/dashboard/administration/email" class="nav-links-clickable">Corporate Email</a></li>';
                echo '</ul></li></ul>';

            }

        }

    }

    if ($currentAccount->fromUserRole($currentAccount->role) == "Customer") {

        switch ($pagetitle) {
            case "Client":
                switch ($pagesubtitle) {
                    case "Overview":
                        renderNavLinks('Overview', $currentAccount->accountNumber);
                        break;
                    case "Account Overview":
                        renderNavLinks('Overview', $currentAccount->accountNumber);
                        break;
                    case "Billing Center":
                        renderNavLinks('Billing', $currentAccount->accountNumber);
                        break;
                    case "Service Status":
                        renderNavLinks('Service Status', $currentAccount->accountNumber);
                        break;
                    case "Access and Security":
                        renderNavLinks('Access & Security Center', $currentAccount->accountNumber);
                        break;
                    case "Customer Service":
                        renderNavLinks('Customer Service', $currentAccount->accountNumber);
                        break;
                    default:
                        renderNavLinks('Overview', $currentAccount->accountNumber);
                        break;
                }
                break;
            case in_array($pagetitle, ["Account Management - Customer", "Account Management - Authorized User", "Account Management - Partner"]) ? $pagetitle : false:
                renderNavLinks('Overview', $currentAccount->accountNumber);
                break;

            default:
                renderNavLinks('Overview');
                break;
        }


    } elseif ($currentAccount->fromUserRole($currentAccount->role) == "Administrator") {

        switch ($pagetitle) {
            case "Administration Dashboard":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Dashboard</p>';
                renderAdminNavLinks('Home', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
            case "Your Calendar and Planner":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Calendar</p>';
                renderAdminNavLinks('Tasks', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
            case "Customer Accounts":
            case "Services":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Customer Cloud</p>';
                renderAdminNavLinks('Accounts', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
            case "Connected Payments":
                if ($pagesubtitle == "Home") {
                    echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Payments Cloud</p>';
                    renderAdminNavLinks('Dashboard', $moduleresult, $departmentresult, $currentAccount->role->name);
                }
                break;
            case "Tasks":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Employee Cloud</p>';
                renderAdminNavLinks('Tasks', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
            case "Cases":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Customer Cloud</p>';
                renderAdminNavLinks('Cases', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
            case "Campaigns":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Marketing Cloud</p>';
                renderAdminNavLinks('Campaigns', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
            case "Payroll":
                echo '<p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Employee Cloud</p>';
                renderAdminNavLinks('Dashboard', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
            default:
                renderAdminNavLinks('Dashboard', $moduleresult, $departmentresult, $currentAccount->role->name);
                break;
        }

    }

?>