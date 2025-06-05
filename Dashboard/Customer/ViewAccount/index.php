<?php

    $PageTitle = "Customer Dashboard";

    $accountNumber = $_GET['account_number'] ?? null;

    if ($accountNumber == "" || $accountNumber == NULL) {

        header("Location: /Dashboard");
        
    }

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

    $CurrentOnlineAccessAccount->GatherSingleAccountDetails($con, $accountNumber);
        
    $account = $CurrentOnlineAccessAccount->selectedAccountDetails;

?>

<title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>

<section class="section dashboard">
    <div class="container nexure-container">
        <div class="nexure-grid nexure-one-grid no-row-gap">
            <div class="nexure-card">
                <div class="card-header">
                    <div class="display-flex justify-content-space-between">
                        <div>
                            <p class="margin-bottom-10px">Overview / Account: <?= htmlspecialchars($account['accountDisplayName']) ?> (... <?= substr($account['accountNumber'], -4) ?>)</p>
                        </div>
                        <div>
                            <?php if (
                                strtolower($account['accountType']) != "service account" &&
                                strtolower($account['accountType']) != "credit card" &&
                                strtolower($account['accountType']) != "line of credit"
                            ): ?> 
                            <?php else: ?>
                                <a href="javascript:void(0)" onclick="openPaymentModal()" class="nexure-button secondary">Pay Balance</a>
                            <?php endif; ?>
                        </div>
                    </div>    
                </div>
                <div class="card-body margin-top-10px margin-bottom-10px">
                    <div>

                         <?php if (strtolower($account['accountStatus']) === 'restricted'): ?>
                            <div class="restricted-notice margin-bottom-30px" style="margin-top:-10px;">
                                <p class="font-12px">We have restricted this account and reopened it to protect your service. If you have any questions, please contact us.</p>
                            </div>
                        <?php endif; ?>

                        <div class="display-flex align-center">
                            <h3 class="font-16px"><?= htmlspecialchars($account['accountDisplayName']) ?> (... <?= substr($account['accountNumber'], -4) ?>)</h3>
                            <span class="padding-left-10px padding-right-10px"> | </span>
                            <a href="javascript:void(0);" onclick="openModal()" class="brand-link display-flex align-center" style="margin-top:-3px;">See full account number <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                        <p class="font-12px" style="text-transform:uppercase;"><?= htmlspecialchars($account['headerName']) ?></p>
                    </div>
                    <div>
                        <div class="nexure-grid nexure-three-grid no-row-gap margin-top-30px">
                            <div>
                                <h3 class="font-30px" style="font-weight:300;">
                                    <?= strtolower($account['accountStatus']) === 'restricted' ? '— —' : '$' . $account['balance'] ?>
                                </h3>
                                <p class="font-12px">Current Balance</p>
                            </div>
                            <div>
                                <h3 class="font-18px" style="font-weight:300;">
                                    <?= strtolower($account['accountStatus']) === 'restricted' ? '— —' : '$' . number_format($account['creditLimit'], 2) ?>
                                </h3>
                                <p class="font-12px">Credit Limit</p>
                            </div>
                            <?php if (strtolower($account['accountType']) != "service account" || strtolower($account['accountType']) != "credit card" || strtolower($account['accountType']) != "line of credit"): ?>
                                <div>
                                    <h3 class="font-18px" style="font-weight:300;">
                                    <?= strtolower($account['accountStatus']) === 'restricted' ? '— —' : '$' . $account['balance'] ?>
                                </h3>
                                <p class="font-12px">Present Balance</p>
                                </div>
                            <?php else: ?>
                                <div>
                                    <h3 class="font-18px" style="font-weight:300;">
                                        <?= strtolower($account['accountStatus']) === 'restricted' ? '— —' : htmlspecialchars($account['dueDate']) ?>
                                    </h3>
                                    <p class="font-12px">Due Date</p>
                                </div>
                            <?php endif; ?>
                            <?php if (strtolower($account['accountType']) != "service account" 
                                && strtolower($account['accountType']) != "credit card" 
                                && strtolower($account['accountType']) != "line of credit"): ?>
                                <!-- No Content Here -->
                            <?php else: ?>
                                <div class="margin-top-30px">
                                    <h3 class="font-18px" style="font-weight:300;">
                                        <?= strtolower($account['accountStatus']) === 'restricted' ? '— —' : '$' . number_format($account['minimumPayment'], 2) ?>
                                    </h3>
                                    <p class="font-12px">Minimum Amount Due</p>
                                </div>
                            <?php endif; ?>
                            <div class="margin-top-30px">
                                <span class="account-status-badge <?= strtolower($account['accountStatus']) === 'open' ? 'green' : 'red' ?>">
                                    <?= htmlspecialchars($account['accountStatus']) ?>
                                </span>
                                <p class="font-12px margin-top-10px">Account Status</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="nexure-grid nexure-four-grid no-row-gap margin-top-10px centered-content">
                        <div class="border-right">
                            <a href="" class="brand-link">Invoices</a>
                        </div>
                        <div class="border-right">
                            <a href="" class="brand-link">Quotes</a>
                        </div>
                        <div class="border-right">
                            <a href="" class="brand-link">Files</a>
                        </div>
                        <div>
                            <a href="" class="brand-link">Support Cases</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nexure-card margin-top-20px">
                <div class="card-header">
                    <?php if (strtolower($account['accountType']) != "service account"): ?>
                        <p class="margin-bottom-20px">Transactions</p>
                    <?php else: ?>
                        <p class="margin-bottom-20px">Services</p>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="nexure-table-container">
                        <table class="nexure-table-plugin nexure-table-domains">
                            <thead>
                                <tr>
                                    <th><?php if (strtolower($account['accountType']) != "service account"): ?>Transaction<?php else: ?>Service<?php endif; ?> Name</th>
                                    <th>Amount</th>
                                    <th><?php if (strtolower($account['accountType']) != "service account"): ?>Posted<?php else: ?>Ordered<?php endif; ?></th>
                                    <?php if (strtolower($account['accountType']) != "service account"): ?><?php else: ?><th>Rendered</th><?php endif; ?>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($account['services']) && is_array($account['services'])): ?>
                                    <?php foreach ($account['services'] as $service): ?>
                                        <tr>
                                            <td class="width-30"><?= htmlspecialchars($service['serviceName']) ?></td>
                                            <td class="width-20">$<?= number_format($service['amount'], 2) ?></td>
                                            <td class="width-20"><?= date('F j Y', strtotime($service['orderDate'])) ?></td>
                                            <?php if (strtolower($account['accountType']) != "service account"): ?><?php else: ?><td class="width-20"><?= date('F j Y', strtotime($service['renderDate'])) ?></td><?php endif; ?>
                                            <td class="width-20"><span class="account-status-badge <?= in_array(strtolower($service['status']), ['active', 'posted']) ? 'green' : 'red' ?>"><?= htmlspecialchars($service['status']) ?></span></td>
                                            <td class="width-40"><a href="" class="nexure-button primary">View</a><a href="" class="nexure-button secondary">Invoice</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6">No services available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="accountModal" class="modal">
    <div class="modal-content">
        <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Full Account Number</h6>
        <p style="font-size:14px; padding-top:30px; padding-bottom:30px;">Full Account Number: <?php echo $accountNumber; ?></p>
        <p style="font-size:14px; padding-bottom:30px;">This account number will be used to identify your account. Keep this number safe.</p>
        <div style="display:flex; align-items:right; justify-content:right;">
            <button class="nexure-button primary" onclick="closeModal()">Close</button>
        </div>
    </div>
</div>

<div id="paybalanceModal" class="modal">
    <div class="modal-content">
        <form method="POST" action="/Dashboard/Customer/ViewAccount/MakePayment/?account_number=<?php echo $accountNumber; ?>">
            <h6 style="font-size:16px; font-weight:800; padding:0; margin:0;">Pay account balance?</h6>
            <div style="font-size:14px; padding-top:30px; padding-bottom:30px;">
                <div class="form-control">
                    <input class="nexure-textbox grey-400" id="balanceNumber" type="numeric" maxlenghth="10" name="balanceNumber" style="width:25%;" placeholder="65.00" />
                </div>
            </div>
            <p style="font-size:14px; padding-bottom:10px;">Please specify how much you would like to pay. Payments will post immediatly, you may need to refresh the page if the balance does not update.</p>
            <p style="font-size:14px; padding-bottom:10px;">NOTE: You can make a payment larger than the balance if you want to prepay for services.</p>
            <p style="font-size:14px; padding-bottom:30px;">Please do not include the currency, simply type the numeric value.</p>
            <div style="display:flex; align-items:right; justify-content:right;">
                <button class="nexure-button primary" type="submit" name="submit">Submit Payment</button>
                <a class="nexure-button secondary" href="javascript:void(0)" onclick="closePaymentModal()">Close</a>
            </div>
        </form>
    </div>
</div>

<script>
    var modal = document.getElementById("accountModal");
    var modalPayBalance = document.getElementById("paybalanceModal");

    function openModal() {
        modal.style.display = "block";
    }

    function closeModal() {
        modal.style.display = "none";
    }

    function openPaymentModal() {
        modalPayBalance.style.display = "block";
    }

    function closePaymentModal() {
        modalPayBalance.style.display = "none";
    }
</script>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>
