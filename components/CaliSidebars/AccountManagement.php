<?php

    function renderSidebar($pagetitle, $pagesubtitle) {

        $sections = [
            "Overview" => "/dashboard/accountManagement/",
            "Account Settings" => [
                "url" => "/dashboard/accountManagement/accountSettings/",
                "subLinks" => [
                    "Integrations" => "/dashboard/accountManagement/accountSettings/integrations",
                    "Show Or Hide Account" => "/dashboard/accountManagement/accountSettings/showOrHideAccounts",
                    "Set Primary Account" => "/dashboard/accountManagement/accountSettings/setPrimaryAccount",
                    "Nickname Account" => "/dashboard/accountManagement/accountSettings/nicknameAccounts",
                    "Manage Custom Groups" => "/dashboard/accountManagement/accountSettings/manageCustomGroups",
                    "Manage Linked Accounts" => "/dashboard/accountManagement/accountSettings/managedLinked"
                ]
            ],
            "Your Personal Details" => [
                "url" => "/dashboard/accountManagement/personalDetails/",
                "subLinks" => [
                    "Address" => "/dashboard/accountManagement/personalDetails/addressInformation",
                    "Phone Number" => "/dashboard/accountManagement/personalDetails/phoneInformation",
                    "Email" => "/dashboard/accountManagement/personalDetails/emailInformation",
                    "Preferred Language" => "/dashboard/accountManagement/personalDetails/languageInformation"
                ]
            ],
            "Sign-In Security" => [
                "url" => "/dashboard/accountManagement/privacyAndSecurity/",
                "subLinks" => [
                    "Username" => "/dashboard/accountManagement/privacyAndSecurity/username",
                    "Password" => "/dashboard/accountManagement/privacyAndSecurity/password",
                    "Security Word" => "/dashboard/accountManagement/privacyAndSecurity/securityWord",
                    "MFA Settings" => "/dashboard/accountManagement/privacyAndSecurity/mfaSettings"
                ]
            ]
        ];

        echo '<aside class="caliweb-sidebar"><ul class="sidebar-list-linked">';
        
        foreach ($sections as $sectionName => $sectionData) {

            if (is_array($sectionData)) {

                echo "<li><a id='" . strtolower(str_replace(' ', '-', $sectionName)) . "-toggle' href='{$sectionData['url']}' class='sidebar-link-a drop-menu-item-sidebar $activeClass' style='margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;'>{$sectionName}<i class='lnr lnr-chevron-down arrow' style='padding-top:2px;'></i></a>";
                echo "<ul id='" . strtolower(str_replace(' ', '-', $sectionName)) . "-menu' class='caliweb-sidebar-sub-menu'>";
                
                foreach ($sectionData['subLinks'] as $subLinkName => $subLinkUrl) {

                    $subActiveClass = ($pagetitle === "Account Management" && $pagesubtitle === $sectionName && $subLinkName === $subLinkName) ? 'active' : '';

                    echo "<li class='sidebar-link account-settings-sidebar-link'><a href='{$subLinkUrl}' class='sidebar-link-a $subActiveClass'>{$subLinkName}</a></li>";

                }

                echo "</ul></li>";

            } else {

                $activeClass = ($pagetitle === "Account Management" && $pagesubtitle === $sectionName) ? 'active' : '';

                echo "<li class='sidebar-link-a drop-menu-item-sidebar $activeClass'><a href='{$sectionData}' class='sidebar-link-a'>{$sectionName}</a></li>";

            }
        }

        echo '</ul></aside>';

    }

    renderSidebar($pagetitle, $pagesubtitle);

?>