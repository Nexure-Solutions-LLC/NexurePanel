<?php
    $pagetitle = "Account Management";
    $pagesubtitle = 'General';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';
?>
<section class="section first-dashboard-area-cards">
    <div class="container caliweb-container">
        <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
            <div class="caliweb-settings-sidebar">
                <div class="caliweb-card dashboard-card sidebar-card">
                    <aside class="caliweb-sidebar">
                        <ul class="sidebar-list-linked">
                            <li class="sidebar-link-a drop-menu-item-sidebar">
                                <a href="/dashboard/accountManagement/" class="sidebar-link-a">Overview</a>
                            </li>
                            <li>
                                <a id="account-settings-toggle" href="/dashboard/accountManagement/accountSettings/" class="sidebar-link-a drop-menu-item-sidebar active" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                    Account Settings
                                    <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                </a>
                                <ul id="account-settings-menu" class="caliweb-sidebar-sub-menu">
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Integrations</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Show Or Hide Account</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Set Primary Account</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Nickname Account</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Manage Custom Groups</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Manage Linked Accounts</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a id="personal-details-toggle" href="/dashboard/accountManagement/personalDetails/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                    Your Personal Details
                                    <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                </a>
                                <ul id="personal-details-menu" class="caliweb-sidebar-sub-menu">
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Address</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Phone Number</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Email</a>
                                    </li>
                                    <li class="sidebar-link account-settings-sidebar-link">
                                        <a href="#" class="sidebar-link-a">Preferred Language</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a id="privacy-security-toggle" href="/dashboard/accountManagement/privacyAndSecurity/" class="sidebar-link-a drop-menu-item-sidebar" style="margin-bottom: 4px; font-size: 15px; display:flex; justify-content: space-between;">
                                    Sign-In Security
                                    <i class="lnr lnr-chevron-down arrow" style="padding-top:2px;"></i>
                                </a>
                                <ul id="privacy-security-menu" class="caliweb-sidebar-sub-menu">
                                    <li class="sidebar-link">
                                        <a href="#" class="sidebar-link-a">Username</a>
                                    </li>
                                    <li class="sidebar-link">
                                        <a href="#" class="sidebar-link-a">Password</a>
                                    </li>
                                    <li class="sidebar-link">
                                        <a href="#" class="sidebar-link-a">Security Word</a>
                                    </li>
                                    <li class="sidebar-link">
                                        <a href="#" class="sidebar-link-a">MFA Settings</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </aside>
                </div>
            </div>
            <div class="caliweb-card dashboard-card">
                <h4 class="text-bold font-size-20 no-padding">d</h4>
                <!-- DO NOT PUT USER PERSONAL INFO IN THIS FILE -->
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggles = [
            { buttonId: 'account-settings-toggle', menuId: 'account-settings-menu' },
            { buttonId: 'personal-details-toggle', menuId: 'personal-details-menu' },
            { buttonId: 'privacy-security-toggle', menuId: 'privacy-security-menu' }
        ];

        toggles.forEach(toggle => {
            const button = document.getElementById(toggle.buttonId);
            const menu = document.getElementById(toggle.menuId);

            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                const isMenuVisible = menu.style.display === 'block';
                menu.style.display = isMenuVisible ? 'none' : 'block';
                const arrow = button.querySelector('.arrow');
                arrow.style.transform = isMenuVisible ? 'rotate(0deg)' : 'rotate(180deg)';
            });
        });
    });
</script>
