<?php

    $pagetitle = "Employment Application";
    $pagesubtitle = "Your applying to work at Nexure.";
    $pagetype = "Work History Application Page";

    require($_SERVER["DOCUMENT_ROOT"].'/components/CaliEmployees/Application.php');

    
        
        
    echo '<title>Complete onboarding of employment.</title>';

?>

    <section class="login-container" style="animation: sideDrop 0.3s ease-out;">
        <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                <h3 class="caliweb-login-heading"><?php echo $variableDefinitionX->orgShortName; ?> <span style="font-weight:700">Employee Onboarding</span></h3>
                <p style="font-size:12px; margin-top:0%;">You have made the first step into getting employment. Now its time to finish and get rolling.</p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div class="caliweb-grid caliweb-two-grid no-grid-row-bottom">
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="legalname" class="text-gray-label">Legal Name</label>
                                <input type="text" class="form-input" name="legalname" id="legalname" value="<?php echo $currentAccount->legalName; ?>" required="" readonly />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="phonenumber" class="text-gray-label">Phone Number</label>
                                <input type="number" class="form-input" name="phonenumber" id="phonenumber" value="<?php echo $currentAccount->mobileNumber; ?>" required="" readonly />
                            </div>
                        </div>
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="emailaddress" class="text-gray-label">Email Address</label>
                                <input type="email" class="form-input" name="emailaddress" id="emailaddress" value="<?php echo $currentAccount->email; ?>" required="" readonly />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="dateofbirth" class="text-gray-label">Date of Bith (Must be 14 years or older)</label>
                                <input type="date" class="form-input" name="dateofbirth" id="dateofbirth" placeholder="" requird="" />
                            </div>
                        </div>
                    </div>
                    <div class="onboarding-button-container" style="margin-top:6%;">
                        <button class="onboarding-button" type="submit" name="submit" style="float: right;">
                            <span class="lnr lnr-arrow-right"></span>
                            <span class="tooltip-text">Next Question</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="caliweb-login-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <!--
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; <span id="nexure-year"></span> - Nexure Solutions LLP - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php');

?>