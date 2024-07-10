<?php
    $sql = "SELECT * FROM caliweb_modules WHERE moduleStatus = 'Active' AND `modulePositionType` = 'Staff Function'";
    $moduleresult = mysqli_query($con, $sql);

    if ($userrole == "Customer" || $userrole == "customer") {

        if ($pagetitle == "Client" && $pagesubtitle == "Overview") {
            echo '
                <ul class="caliweb-nav-links">
                    <li class="nav-links active"><a href="/dashboard/customers/" class="nav-links-clickable">Overview</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/accessAndSecurityCenter" class="nav-links-clickable">Access & Security Center</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/serviceStatus" class="nav-links-clickable">Service Status</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/supportCenter" class="nav-links-clickable">Customer Service</a></li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>
            ';

        } else if ($pagetitle == "Client" && $pagesubtitle == "Account Overview") {

            echo '
                <ul class="caliweb-nav-links">
                    <li class="nav-links active"><a href="/dashboard/customers/" class="nav-links-clickable">Overview</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/billingCenter?account_number='.$accountnumber.'" class="nav-links-clickable">Billing</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/accessAndSecurityCenter" class="nav-links-clickable">Access & Security Center</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/serviceStatus" class="nav-links-clickable">Service Status</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/supportCenter" class="nav-links-clickable">Customer Service</a></li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>
            ';

        } else if ($pagetitle == "Client" && $pagesubtitle == "Billing Center") {

            echo '
                <ul class="caliweb-nav-links">
                    <li class="nav-links"><a href="/dashboard/customers/" class="nav-links-clickable">Overview</a></li>
                    <li class="nav-links active"><a href="/dashboard/customers/billingCenter?account_number='.$accountnumber.'" class="nav-links-clickable">Billing</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/accessAndSecurityCenter" class="nav-links-clickable">Access & Security Center</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/serviceStatus" class="nav-links-clickable">Service Status</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/supportCenter" class="nav-links-clickable">Customer Service</a></li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>
            ';

        } else if ($pagetitle == "Client" && $pagesubtitle == "Access and Security") {

            echo '
                <ul class="caliweb-nav-links">
                    <li class="nav-links"><a href="/dashboard/customers/" class="nav-links-clickable">Overview</a></li>
                    <li class="nav-links active"><a href="/dashboard/customers/accessAndSecurityCenter" class="nav-links-clickable">Access & Security Center</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/serviceStatus" class="nav-links-clickable">Service Status</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/supportCenter" class="nav-links-clickable">Customer Service</a></li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>
            ';

        } else if ($pagetitle == "Client" && $pagesubtitle == "Customer Service") {

            echo '
                <ul class="caliweb-nav-links">
                    <li class="nav-links"><a href="/dashboard/customers/" class="nav-links-clickable">Overview</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/accessAndSecurityCenter" class="nav-links-clickable">Access & Security Center</a></li>
                    <li class="nav-links"><a href="/dashboard/customers/serviceStatus" class="nav-links-clickable">Service Status</a></li>
                    <li class="nav-links active"><a href="/dashboard/customers/supportCenter" class="nav-links-clickable">Customer Service</a></li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>
            ';

        }
    } else if ($userrole == "Administrator" || $userrole == "administrator") {
        if ($pagetitle == "Administration Dashboard") {

            echo '

                <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Dashboard</p>
                <ul class="caliweb-nav-links">
                    <li class="nav-links active"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/tasks" class="nav-links-clickable">Tasks</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/leads" class="nav-links-clickable">Leads</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/accounts" class="nav-links-clickable">Accounts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/campaigns" class="nav-links-clickable">Campaigns</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/contacts" class="nav-links-clickable">Contacts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/cases" class="nav-links-clickable">Cases</a></li>
            ';

            echo '
                    <li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">
                            
                        
            
            ';

            if (mysqli_num_rows($moduleresult) > 0) {
                while ($modulerow = mysqli_fetch_assoc($moduleresult)) {
                    echo '<li class="nav-links"><a href="'.$modulerow['modulePath'].'" class="nav-links-clickable">'.$modulerow['moduleFreindlyName'].'</a></li>';
                }
            }

            echo '
                            <li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>
                            <li class="nav-links"><a href="/dashboard/administration/email" class="nav-links-clickable">Corporate Email</a></li>
                        </ul>
                    </li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';

        } else if ($pagetitle == "Your Calendar and Planner") {

           echo '

               <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Calendar</p>
               <ul class="caliweb-nav-links">
                   <li class="nav-links"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                   <li class="nav-links"><a href="/dashboard/administration/tasks" class="nav-links-clickable">Tasks</a></li>
                   <li class="nav-links"><a href="/dashboard/administration/leads" class="nav-links-clickable">Leads</a></li>
                   <li class="nav-links"><a href="/dashboard/administration/accounts" class="nav-links-clickable">Accounts</a></li>
                   <li class="nav-links"><a href="/dashboard/administration/campaigns" class="nav-links-clickable">Campaigns</a></li>
                   <li class="nav-links"><a href="/dashboard/administration/contacts" class="nav-links-clickable">Contacts</a></li>
                   <li class="nav-links "><a href="/dashboard/administration/cases" class="nav-links-clickable">Cases</a></li>
                   ';

            echo '
                    <li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">
                    
                
    
            ';

            if (mysqli_num_rows($moduleresult) > 0) {
                while ($modulerow = mysqli_fetch_assoc($moduleresult)) {
                    echo '<li class="nav-links"><a href="'.$modulerow['modulePath'].'" class="nav-links-clickable">'.$modulerow['moduleFreindlyName'].'</a></li>';
                }
            }

            echo '
                            <li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>
                            <li class="nav-links"><a href="/dashboard/administration/email" class="nav-links-clickable">Corporate Email</a></li>
                        </ul>
                    </li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';

        } else if ($pagetitle == "Customer Accounts") {

            echo '

                <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Customer Cloud</p>
                <ul class="caliweb-nav-links">
                    <li class="nav-links"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/tasks" class="nav-links-clickable">Tasks</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/leads" class="nav-links-clickable">Leads</a></li>
                    <li class="nav-links active"><a href="/dashboard/administration/accounts" class="nav-links-clickable">Accounts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/campaigns" class="nav-links-clickable">Campaigns</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/contacts" class="nav-links-clickable">Contacts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/cases" class="nav-links-clickable">Cases</a></li>
            ';

            echo '

                    <li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">
                            
                        
            
            ';

            if (mysqli_num_rows($moduleresult) > 0) {
                while ($modulerow = mysqli_fetch_assoc($moduleresult)) {
                    echo '<li class="nav-links"><a href="'.$modulerow['modulePath'].'" class="nav-links-clickable">'.$modulerow['moduleFreindlyName'].'</a></li>';
                }
            }

            echo '
                            <li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>
                            <li class="nav-links"><a href="/dashboard/administration/email" class="nav-links-clickable">Corporate Email</a></li>
                        </ul>
                    </li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';

        } else if ($pagetitle == "Connected Payments" && $pagesubtitle == "Home") {

            echo '

                <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Payments Cloud</p>
                <ul class="caliweb-nav-links">
                    <li class="nav-links active"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/tasks" class="nav-links-clickable">Invoices</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/leads" class="nav-links-clickable">Payments Links</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/accounts" class="nav-links-clickable">Integrations</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/campaigns" class="nav-links-clickable">Revenue Recovery</a></li>
            ';

            echo '
                    <li class="nav-links"><a href="/" class="nav-links-clickable">Return to Dashboard</a></li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';

        } else if ($pagetitle == "Tasks") {

            echo '

              <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Employee Cloud</p>
              <ul class="caliweb-nav-links">
                  <li class="nav-links"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                  <li class="nav-links active"><a href="/dashboard/administration/tasks" class="nav-links-clickable">Tasks</a></li>
                  <li class="nav-links"><a href="/dashboard/administration/leads" class="nav-links-clickable">Leads</a></li>
                  <li class="nav-links"><a href="/dashboard/administration/accounts" class="nav-links-clickable">Accounts</a></li>
                  <li class="nav-links"><a href="/dashboard/administration/campaigns" class="nav-links-clickable">Campaigns</a></li>
                  <li class="nav-links"><a href="/dashboard/administration/contacts" class="nav-links-clickable">Contacts</a></li>
                  <li class="nav-links"><a href="/dashboard/administration/cases" class="nav-links-clickable">Cases</a></li>
            ';

            echo '

                    <li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">
                            
                        
            
            ';

            if (mysqli_num_rows($moduleresult) > 0) {
                while ($modulerow = mysqli_fetch_assoc($moduleresult)) {
                    echo '<li class="nav-links"><a href="'.$modulerow['modulePath'].'" class="nav-links-clickable">'.$modulerow['moduleFreindlyName'].'</a></li>';
                }
            }

            echo '
                            <li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>
                            <li class="nav-links"><a href="/dashboard/administration/email" class="nav-links-clickable">Corporate Email</a></li>
                        </ul>
                    </li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';

        } else if ($pagetitle == "Cases") {

            echo '

                <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Customer Cloud</p>
                <ul class="caliweb-nav-links">
                    <li class="nav-links"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/tasks" class="nav-links-clickable">Tasks</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/leads" class="nav-links-clickable">Leads</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/accounts" class="nav-links-clickable">Accounts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/campaigns" class="nav-links-clickable">Campaigns</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/contacts" class="nav-links-clickable">Contacts</a></li>
                    <li class="nav-links active"><a href="/dashboard/administration/cases" class="nav-links-clickable">Cases</a></li>
            ';
  
            echo '

                    <li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">
                            
                        
            
            ';

            if (mysqli_num_rows($moduleresult) > 0) {
                while ($modulerow = mysqli_fetch_assoc($moduleresult)) {
                    echo '<li class="nav-links"><a href="'.$modulerow['modulePath'].'" class="nav-links-clickable">'.$modulerow['moduleFreindlyName'].'</a></li>';
                }
            }

            echo '
                            <li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>
                            <li class="nav-links"><a href="/dashboard/administration/email" class="nav-links-clickable">Corporate Email</a></li>
                        </ul>
                    </li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';

        } else if ($pagetitle == "Payroll") {

            echo '

                <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Employee Cloud</p>
                <ul class="caliweb-nav-links">
                    <li class="nav-links"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/tasks" class="nav-links-clickable">Tasks</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/leads" class="nav-links-clickable">Leads</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/accounts" class="nav-links-clickable">Accounts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/campaigns" class="nav-links-clickable">Campaigns</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/contacts" class="nav-links-clickable">Contacts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/cases" class="nav-links-clickable">Cases</a></li>
            ';

            echo '

                    <li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">
                            
                        
            
            ';

            if (mysqli_num_rows($moduleresult) > 0) {
                while ($modulerow = mysqli_fetch_assoc($moduleresult)) {
                    echo '<li class="nav-links"><a href="'.$modulerow['modulePath'].'" class="nav-links-clickable">'.$modulerow['moduleFreindlyName'].'</a></li>';
                }
            }

            echo '
                            <li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>
                            <li class="nav-links"><a href="/dashboard/administration/email" class="nav-links-clickable">Corporate Email</a></li>
                        </ul>
                    </li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';

        } else if ($pagetitle == "Cali Mail") {
            echo '

                <p class="no-margin no-padding" style="padding-right:20px; padding-top:7px; font-weight:500;">Cali Mail</p>
                <ul class="caliweb-nav-links">
                    <li class="nav-links"><a href="/dashboard/administration/" class="nav-links-clickable">Home</a></li>
                    <li class="nav-links active"><a href="/dashboard/administration/email" class="nav-links-clickable">Inbox</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/email/compose" class="nav-links-clickable">Compose</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/contacts" class="nav-links-clickable">Contacts</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/email/sent" class="nav-links-clickable">Sent</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/email/junk" class="nav-links-clickable">Junk</a></li>
                    <li class="nav-links"><a href="/dashboard/administration/email/trash" class="nav-links-clickable">Trash</a></li>
                    <li class="nav-links more">
                        <a class="nav-links-clickable more-button" href="#">More</a>
                        <ul class="dropdown">
                            <li class="nav-links"><a href="/dashboard/administration/email/preferences" class="nav-links-clickable">Preferences</a></li>
                            <li class="nav-links"><a href="/dashboard/administration/settings" class="nav-links-clickable">System Settings</a></li>
                        </ul>
                    </li>
                    <li class="nav-links"><a href="/logout" class="nav-links-clickable">Sign Off</a></li>
                </ul>

            ';
        }
    }
?>