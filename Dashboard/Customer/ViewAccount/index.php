<?php

    $PageTitle = "Customer Dashboard";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

    $accountNumber = $_GET['account_number'] ?? null;

    if ($accountNumber) {
        $VariableDefinitionHandler->GatherSingleAccountDetails($con, $accountNumber);
        $account = $VariableDefinitionHandler->selectedAccountDetails;
    } else {
        $account = null;
    }

?>

<title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | <?php echo $PageTitle; ?></title>

<section class="section dashboard">
    <div class="container nexure-container">
        <div class="nexure-grid nexure-one-grid no-row-gap">
            <div class="nexure-card">
                <div class="card-header">
                    <p class="margin-bottom-20px">Overview / Account: <?= htmlspecialchars($account['accountDisplayName']) ?> (... <?= substr($account['accountNumber'], -4) ?>)</p>
                </div>
                <div class="card-body">
                    <div>
                        <div class="display-flex align-center">
                            <h3 class="font-16px"><?= htmlspecialchars($account['accountDisplayName']) ?> (... <?= substr($account['accountNumber'], -4) ?>)</h3>
                            <span class="padding-left-10px padding-right-10px"> | </span>
                            <a href="" class="brand-link">See full account number <span class="lnr lnr-chevron-right"></span></a>
                        </div>
                        <p class="font-12px" style="text-transform:uppercase;"><?= htmlspecialchars($account['headerName']) ?></p>
                    </div>
                    <div>
                        <div class="nexure-grid nexure-three-grid no-row-gap margin-top-30px">
                            <div>
                                <h3 class="font-30px" style="font-weight:300;">
                                    $<?= $account['balance'] ?>
                                </h3>
                                <p class="font-12px">Current Balance</p>
                            </div>
                            <div>
                                <h3 class="font-18px" style="font-weight:300;">
                                    $<?= number_format($account['creditLimit'], 2) ?>
                                </h3>
                                <p class="font-12px">Credit Limit</p>
                            </div>
                            <div>
                                <h3 class="font-18px" style="font-weight:300;">
                                    <?= htmlspecialchars($account['dueDate']) ?>
                                </h3>
                                <p class="font-12px">Due Date</p>
                            </div>
                            <div class="margin-top-30px">
                                <h3 class="font-18px" style="font-weight:300;">
                                    $<?= number_format($account['minimumPayment'], 2) ?>
                                </h3>
                                <p class="font-12px">Minimum Amount Due</p>
                            </div>
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
                    <p class="margin-bottom-20px">Services</p>
                </div>
                <div class="card-body">
                    <div class="nexure-table-container">
                        <table class="nexure-table-plugin nexure-table-domains">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Amount</th>
                                    <th>Order Date</th>
                                    <th>Render Date</th>
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
                                            <td class="width-20"><?= date('F j Y', strtotime($service['renderDate'])) ?></td>
                                            <td class="width-20"><span class="account-status-badge <?= strtolower($service['status']) === 'active' ? 'green' : 'red' ?>"><?= htmlspecialchars($service['status']) ?></span></td>
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

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Footers/index.php");

?>
