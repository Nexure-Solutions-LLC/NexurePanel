<?php

    $pagetitle = "Customer Accounts";
    $pagesubtitle = "List";
    $pagetype = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/tables/accountTables/index.php');

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content: space-between;">
                            <div class="display-flex align-center">
                                <div class="no-padding margin-10px-right icon-size-formatted">
                                    <img src="/assets/img/systemIcons/accountsicon.png" alt="Client Logo and/or Business Logo" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                </div>
                                <div>
                                    <p class="no-padding font-14px">Accounts</p>
                                    <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List Accounts</h4>
                                </div>
                            </div>
                            <div>
                                <a href="/dashboard/administration/accounts/createAccount/" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Create New</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dashboard-table">
                            <?php

                                accountsHomeListingTable(
                                    $con,
                                    "SELECT * FROM nexure_users WHERE userrole <> 'administrator' AND userrole <> 'authorized user'",
                                    ['Company/Account Number', 'Owner', 'Phone', 'Type', 'DB Prefix', 'Status', 'Actions'],
                                    ['accountNumber', 'legalName', 'mobileNumber', 'userrole', 'accountDBPrefix', 'accountStatus'],
                                    ['30%', '20%', '15%', '15%', '10%', '20%'],
                                    [
                                        'View' => "/dashboard/administration/accounts/manageAccount/?account_number={accountNumber}",
                                        'Edit' => "/dashboard/administration/accounts/editAccount/?account_number={accountNumber}",
                                        'Delete' => "openModal({accountNumber})"
                                    ]
                                );

                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="accountModal" class="modal">
        <div class="modal-content">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Delete customer's account?</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">What you are about to do is permanent and can't be undone. Are you sure you would like to delete this customer. You will need to remake their account if you would like to restore it.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <a id="deleteLink" href="#" class="caliweb-button secondary red" style="margin-right:20px;">Delete Account</a>
                <button class="caliweb-button primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("accountModal");

        function openModal(accountNumber) {
            deleteLink.href = "/dashboard/administration/accounts/deleteAccount/?account_number=" + encodeURIComponent(accountNumber);
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>

<?php 

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php'); 

?>