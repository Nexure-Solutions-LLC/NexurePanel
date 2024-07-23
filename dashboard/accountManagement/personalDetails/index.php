<?php

    $pagetitle = "Account Management";
    $pagesubtitle = 'Personal Details';
    $pagetype = "";

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliHeaders/Dashboard.php');

    echo '<title>'.$pagetitle.' - '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container caliweb-container">
            <div class="caliweb-two-grid special-caliweb-spacing setttings-shifted-spacing">
                <div class="caliweb-settings-sidebar">
                    <div class="caliweb-card dashboard-card sidebar-card" style="overflow-y: scroll;">
                        <?php

                            include($_SERVER["DOCUMENT_ROOT"].'/components/CaliSidebars/AccountManagement.php');

                        ?>
                    </div>
                </div>
                <div class="caliweb-card dashboard-card">
                    <div class="card-header">
                        <div class="display-flex align-center" style="justify-content:space-between;">
                            <div>
                                <h3 class="font-size-20 no-padding">Personal Details</h3>
                            </div>
                            <div>
                                <a href="/dashboard/accountManagement/personalDetails/editpersonalinformation" class="careers-link" style="text-decoration:none;"><span class="display-flex align-center">Edit Information <span class="lnr lnr-chevron-right" style="margin-left:10px;"></span></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <h3 class="font-size-20 no-padding" style="font-weight:300;">Primary</h3>
                            <div class="caliweb-horizantal-spacer" style="margin-top:20px; margin-bottom:20px;"></div>
                            <p class="font-14px">Your primary address is where you live—typically, what's on your driver's license or other government-issued ID. If you'd like us to send your mail for any accounts to a different address or addresses, choose "Add" above. (If you don't see an "Add" button, you aren't authorized to add an address.)</p>
                        </div>

                        <div style="margin-left:auto; margin-right:auto; width:60%;">
                            <div class="caliweb-grid caliweb-two-grid" style="margin-top:5%;">
                                <div>
                                    <p>Business Address</p>
                                </div>
                                <div>
                                    <p>

                                        <?php

                                            $ownershipAddressQuery = mysqli_query($con, "SELECT * FROM caliweb_ownershipinformation WHERE emailAddress = '$caliemail'");
                                            $ownershipAddressRole = mysqli_fetch_array($ownershipAddressQuery);
                                            mysqli_free_result($ownershipAddressQuery);

                                            $ownershipAddressLine1 = $ownershipAddressRole['addressline1'];
                                            $ownershipAddressLine2 = $ownershipAddressRole['addressline2'];
                                            $ownershipAddressCity = $ownershipAddressRole['city'];
                                            $ownershipAddressState = $ownershipAddressRole['state'];
                                            $ownershipAddressPostalCode = $ownershipAddressRole['postalcode'];

                                            echo $ownershipAddressLine1 ,' ', $ownershipAddressLine2;
                                            echo '<br>';
                                            echo $ownershipAddressCity ,' ', $ownershipAddressState ,' ', $ownershipAddressPostalCode;

                                        ?>

                                    </p>
                                </div>
                            </div>
                            <div class="caliweb-grid caliweb-two-grid" style="margin-top:-8%;">
                                <div>
                                    <p>Business Email</p>
                                </div>
                                <div>
                                    <p>

                                        <?php

                                            echo $currentAccount->email;

                                        ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Dashboard.php');

?>