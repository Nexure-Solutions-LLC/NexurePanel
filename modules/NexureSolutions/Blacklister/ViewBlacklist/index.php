<?php

    $pagetitle = "Blacklister";
    $pagesubtitle = "View Blacklist";
    $pagetype = "Administration";

    unset($_SESSION['verification_code']);

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/tables/blacklistTables/index.php');

    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

    $accountNumber = $_GET["account_number"];

    if (!isset($accountNumber)) {

        header("location: /error/genericSystemError");

    } else {

        $result = $con->query("SELECT * FROM nexure_users WHERE accountNumber = '$accountNumber'");

        if ($result->num_rows == 0) {

            redirect("/error/genericSystemError");

        }

        $user = $result->fetch_assoc();
        
?>

        <section class="section first-dashboard-area-cards">
            <div class="container width-98">
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <div class="card-header">
                            <div class="display-flex align-center" style="justify-content: space-between;">
                                <div class="display-flex align-center">
                                    <div class="no-padding margin-10px-right icon-size-formatted">
                                        <img src="/assets/img/systemIcons/blacklisterservices.png" alt="Client Logo and/or Business Logo" style="background-color:#fbe7e3;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">Blacklister Services</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">View Blacklists</h4>
                                    </div>
                                </div>
                                <div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="dashboard-table">
                                <?php

                                    blacklistsHomeListingTable(
                                        $con,
                                        "SELECT * FROM nexure_blacklists WHERE accountNumber = '$accountNumber'",
                                        ['Blacklist ID', 'Customer Account Number', 'Title', 'Submitted By', 'Customer Email', 'Status', 'Actions'],
                                        ['blacklistIdentifier', 'accountNumber', 'blacklistTitle', 'submittedBy', 'emailAddress', 'status'],
                                        ['10%', '15%', '15%', '15%', '15%', '10%', '30%'],
                                        [
                                            'Evidence' => "compellingEvidence",
                                            'Edit' => "/modules/NexureSolutions/Blacklister/EditBlacklist/?account_number={accountNumber}",
                                            'Revoke' => "openModal({accountNumber})"
                                        ]
                                    );

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div id="blacklistModal" class="modal">
        <div class="modal-content">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Revoke customer's blacklist?</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">What you are about to do is permanent and can't be undone. Are you sure you would like to revoke this customers blacklist. You will need to remake their blacklist if you would like to restore it.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <a id="deleteLink" href="#" class="caliweb-button secondary red" style="margin-right:20px;">Revoke Blacklist</a>
                <button class="caliweb-button primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("blacklistModal");

        function openModal(accountNumber) {
            deleteLink.href = "/modules/NexureSolutions/Blacklister/RevokeBlacklist/?account_number=" + encodeURIComponent(accountNumber);
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>


<?php

        include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php'); 

    }

?>