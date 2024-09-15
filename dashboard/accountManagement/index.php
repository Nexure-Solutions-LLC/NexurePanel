<?php

$pagetitle = "Account Management";
$pagesubtitle = 'General';
$pagetype = "";

include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

$accountModulesLookupQuery = "SELECT * FROM caliweb_modules WHERE moduleStatus = 'Active' AND modulePositionType = 'Authentication'";
$accountModulesLookupResult = mysqli_query($con, $accountModulesLookupQuery);

echo '<title>' . $pagetitle . ' | ' . $pagesubtitle . '</title>';

?>

<section class="section first-dashboard-area-cards">
    <div class="container caliweb-container">
        <div class="caliweb-one-grid special-caliweb-spacing">
            <div class="caliweb-card dashboard-card" style="overflow-y:scroll; height:85vh;">
                <h3 style="font-size:18px; margin-top:10px;">Account Settings</h3>
                <div class="caliweb-grid caliweb-two-grid newRedesignedSettingsSpacing">
                    <div class="settings-area">
                        <div class="profile-edit">
                            <p style="font-weight:800; padding-bottom:4%; margin-top:0; padding-top:0;">Edit the information on file</p>
                            <form class="" method="POST" action="">
                                <div class="form-control">
                                    <label for="emailAddress">Email Address</label>
                                    <input class="form-input" type="email" name="emailAddress" placeholder="me@example.com" />
                                </div>
                                <div class="form-control textAreaSpacing">
                                    <label for="emailAddress">Phone Number</label>
                                    <input class="form-input" type="text" name="phoneNumber" placeholder="1123456789" />
                                </div>
                                <div class="form-control textAreaSpacing">
                                    <label for="emailAddress">Password</label>
                                    <input class="form-input" type="password" name="password" placeholder="Super Secret Password" />
                                </div>
                                <div class="form-control textAreaSpacing">
                                    <label for="emailAddress">Confirm Password</label>
                                    <input class="form-input" type="password" name="confirmPassword" placeholder="Super Secret Password" />
                                </div>
                                <div class="form-control buttonArea">
                                    <button name="submit" type="submit" class="caliweb-button primary">Update Information</button>
                                </div>
                            </form>
                        </div>
                        <div class="integrations">
                            <p style="font-weight:800; padding-bottom:4%;">Integrations</p>
                            <?php

                            if (mysqli_num_rows($accountModulesLookupResult) > 0) {

                                while ($accountModulesLookupRow = mysqli_fetch_assoc($accountModulesLookupResult)) {

                                    $accountModulesName = $accountModulesLookupRow['moduleName'];

                                    if ($accountModulesName == "Cali OAuth") {

                                        include($_SERVER["DOCUMENT_ROOT"] . "/modules/CaliWebDesign/Oauth/index.php");
                                    }
                                }
                            }

                            ?>
                        </div>
                        <div class="integrations">
                            <p style="font-weight:800; padding-bottom:4%;">Danger Zone</p>
                            <div class="" style="padding-top:6%;">
                                <button class="caliweb-button primary red">Close Account</button>
                                <button class="caliweb-button primary">Delete Personal Data</button>
                            </div>
                        </div>
                    </div>
                    <div class="profile-area">
                        <div class="caliweb-card dashboard-card account-center-cards">
                            <div class="" style="display:flex; justify-content:space-between; vertical-align:top;">
                                <div class="profileInfoText">
                                    <p style="font-weight:800;">Current Information on File</p>
                                    <div class="accountInfoArea">
                                        <p style="font-size:14px; margin-bottom:10px;">Legal Name: <?php echo $currentAccount->legalName; ?></p>
                                        <p style="font-size:14px; margin-bottom:10px;">Phone Number: <?php echo $currentAccount->mobileNumber; ?></p>
                                        <p style="font-size:14px; margin-bottom:10px;">Email Address: <?php echo $currentAccount->email; ?></p>
                                        <p style="font-size:14px; margin-bottom:10px;">Account Number: <?php echo $currentAccount->accountNumber; ?></p>
                                        <p style="font-size:14px; margin-bottom:10px;">Email Verified: True</p>
                                        <p style="font-size:14px;">Account Status: Active</p>
                                    </div>
                                </div>
                                <div class="profileInfoProfileImage">
                                    <img src="" class="profileImage" style="height:70px; width:70px;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<?php

include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>