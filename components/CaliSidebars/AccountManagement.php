<?php

    function renderSidebar($pagetitle, $pagesubtitle) {

        $sections = [
            "Overview" => "/dashboard/accountManagement/",
            "Account Settings" => [
                "url" => "/dashboard/accountManagement/accountSettings/",
                "subLinks" => [
                    "Integrations" => "#",
                    "Show Or Hide Account" => "#",
                    "Set Primary Account" => "#",
                    "Nickname Account" => "#",
                    "Manage Custom Groups" => "#",
                    "Manage Linked Accounts" => "#"
                ]
            ],
            "Your Personal Details" => [
                "url" => "/dashboard/accountManagement/personalDetails/",
                "subLinks" => [
                    "Address" => "#",
                    "Phone Number" => "#",
                    "Email" => "#",
                    "Preferred Language" => "#"
                ]
            ],
            "Sign-In Security" => [
                "url" => "/dashboard/accountManagement/privacyAndSecurity/",
                "subLinks" => [
                    "Username" => "#",
                    "Password" => "#",
                    "Security Word" => "#",
                    "MFA Settings" => "#"
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