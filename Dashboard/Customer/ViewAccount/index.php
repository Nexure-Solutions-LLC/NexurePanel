<?php

    $PageTitle = "Customer Dashboard";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Dashboard/Headers/index.php");

?>

    <title>Nexure Unified Panel | <?php echo $PageTitle; ?></title>


    <section class="section dashboard">
        <div class="container nexure-container">
            <div class="nexure-grid nexure-one-grid no-row-gap">
                <div class="nexure-card">
                    <div class="card-header">
                        <p class="margin-bottom-20px">Overview / Account: Nexure Enterprise Unlimited (... 1234)</p>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="display-flex align-center"><h3 class="font-16px">Nexure Enterprise Unlimited (... 1234)</h3> <span class="padding-left-10px padding-right-10px"> | </span> <a href="" class="brand-link">See full account number <span class="lnr lnr-chevron-right"></span></a></div>
                            <p class="font-12px" style="text-transform:uppercase;">TESTING ORGANIZATION II LTD.</p>
                        </div>
                        <div>
                            <div class="nexure-grid nexure-three-grid no-row-gap margin-top-30px">
                                <div>
                                    <h3 class="font-30px" style="font-weight:300;">$14.99<h3>
                                    <p class="font-12px">Current Balance</p>
                                </div>
                                <div>
                                    <h3 class="font-18px" style="font-weight:300;">$250,000.00<h3>
                                    <p class="font-12px">Credit Limit</p>
                                </div>
                                <div>
                                    <h3 class="font-18px" style="font-weight:300;">May 30 2025<h3>
                                    <p class="font-12px">Due Date</p>
                                </div>
                                <div class="margin-top-30px">
                                    <h3 class="font-18px" style="font-weight:300;">$14.99<h3>
                                    <p class="font-12px">Minimum Amount Due</p>
                                </div>
                                <div class="margin-top-30px">
                                    <span class="account-status-badge green">Open</span>
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
                                    <tr>
                                        <td class="width-30">Business Web Hosting Unlimited</td>
                                        <td class="width-20">$14.99</td>
                                        <td class="width-20">May 13 2025</td>
                                        <td class="width-20">May 30 2025</td>
                                        <td class="width-20"><span class="account-status-badge green">Active</span></td>
                                        <td class="width-40"><a href="" class="nexure-button primary" style="padding:4px 24px;">View</a><a href="" class="nexure-button secondary" style="padding:4px 24px;">Invoice</a></td>
                                    </tr>
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