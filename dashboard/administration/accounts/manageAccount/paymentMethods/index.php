<?php

    $pagetitle = "Customer Accounts";
    $pagesubtitle = "Payment Methods";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');
    
    echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

    unset($_SESSION['stripe_token']);
    unset($_SESSION['ACCOUNTNUMBERCUST']);
    unset($_SESSION['stripe_id']);

    $accountnumber = $_GET['account_number'] ?? '';

    if (!$accountnumber) {
        header("location: /dashboard/administration/accounts");
        exit;
    }

    $accountnumberEsc = mysqli_real_escape_string($con, $accountnumber);

    $manageAccountDefinitionR = new \NexureSolutions\Generic\VariableDefinitions();
    $manageAccountDefinitionR->manageAccount($con, $accountnumber);

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <?php include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Account/Headers/index.php'); ?>
                <div class="caliweb-two-grid special-caliweb-spacing account-grid-modified">
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <?php include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Account/Menus/index.php'); ?>
                            <div class="caliweb-card dashboard-card">
                                <div class="card-header">
                                    <div class="display-flex align-center" style="justify-content:space-between;">
                                        <div>
                                            <p class="no-padding">Payment Methods</p>
                                        </div>
                                        <div class="display-flex align-center">
                                            <a href="/dashboard/administration/accounts/createPaymentMethod/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Add Payment Method</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-table">
                                        <?php

                                            if ($variableDefinitionX->apiKeysecret != "" && $variableDefinitionX->paymentgatewaystatus == "active") {

                                                if ($variableDefinitionX->paymentProcessorName == "Stripe") {

                                                    require($_SERVER["DOCUMENT_ROOT"].'/modules/paymentModule/stripe/index.php');

                                                }
                                            
                                            }
                                            
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="caliweb-card dashboard-card">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content:space-between;">
                                    <div>
                                        <p class="no-padding">Notes and Activity</p>
                                    </div>
                                    <div class="display-flex align-center">
                                        <a href="/dashboard/administration/accounts/addActivityNote/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin" style="padding:6px 24px;">Place Note</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (mysqli_num_rows($manageAccountDefinitionR->notesResults) == 0): ?>
                                    <p class="font-14px no-padding" style="margin-top:10px; margin-bottom:10px;">No notes have been made for this account.</p>
                                <?php endif; ?>
                                <?php 
                                    while ($row = mysqli_fetch_assoc($manageAccountDefinitionR->notesResults)): 
                                    $addedAtDateModify = DateTime::createFromFormat('d-m-Y h:i:sa', $row['added_at'])->format('M d, Y \a\\t h:i A');
                                ?>
                                    <div class="caliweb-card dashboard-card note-card" style="margin-bottom:10px;">
                                        <div class="card-header">
                                            <div class="display-flex align-center">
                                                <div class="no-padding margin-20px-right icon-size-formatted" style="height: 40px; width: 40px;">
                                                    <img src="/assets/img/systemIcons/notesicon.png" alt="Notes Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                                </div>
                                                <div>
                                                    <p class="no-padding font-12px"><strong>Note Added</strong></p>
                                                    <p class="no-padding font-12px">Author: <?= $row["added_by"] ?></p>
                                                    <p class="no-padding font-12px">Date: <?= $addedAtDateModify ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="no-padding font-12px"><?= $row['content'] ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="paymentMethodModal" class="modal">
        <div class="modal-content">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Delete customer's payment information?</h6>
            <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">What you are about to do is permanent and can't be undone. Are you sure you would like to delete this customer's payment method. You will no longer be able to charge this customer unless you add a new card on file.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <a id="deleteLink" href="#" class="caliweb-button secondary red" style="margin-right:20px;">Delete Payment Method</a>
                <button class="caliweb-button primary" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("paymentMethodModal");

        function openModal(accountNumber, paymentMethodId) {
            deleteLink.href = "/dashboard/administration/accounts/deletePaymentMethod/?account_number=" + encodeURIComponent(accountNumber) + "&payment_method_id=" + encodeURIComponent(paymentMethodId);
            modal.style.display = "block";
        }

        function closeModal() {
            modal.style.display = "none";
        }
    </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>