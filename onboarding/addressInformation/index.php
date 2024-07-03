<?php
    include($_SERVER["DOCUMENT_ROOT"]."/assets/php/loginHeader.php");
    echo '<title>Complete onbording of your new account.</title>';
?>

    <section class="login-container">
        <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                <h3 class="caliweb-login-heading"><?php echo $orgshortname; ?> <span style="font-weight:700">Onboarding</span></h3>
                <p style="font-size:12px; margin-top:0%;">Please provide your home address so that we can make sure your location is supported.</p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div class="caliweb-grid caliweb-two-grid">
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="addressline1" class="text-gray-label">Address Line 1</label>
                                <input type="text" class="form-input" name="addressline1" id="addressline1" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="city" class="text-gray-label">City</label>
                                <input type="text" class="form-input" name="city" id="city" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="postalcode" class="text-gray-label">Postal Code</label>
                                <input type="text" class="form-input" name="postalcode" id="postalcode" placeholder="" required="" />
                            </div>
                        </div>
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="addressline2" class="text-gray-label">Address Line 2</label>
                                <input type="text" class="form-input" name="addressline2" id="addressline2" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="state" class="text-gray-label">State</label>
                                <input type="text" class="form-input" name="state" id="state" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="country" class="text-gray-label">Country</label>
                                <input type="text" class="form-input" name="country" id="country" placeholder="" required="" />
                            </div>
                            <div class="form-control">
                                <input type="text" class="form-input" style="display:none;" name="dispnone" id="dispnone" placeholder="" />
                            </div>
                            <div class="mt-5-per" style="display:flex; align-items:center; justify-content:space-between; float:right;">
                                <div class="form-control width-100">
                                    <button class="caliweb-button primary" style="text-align:left; display:flex; align-center; justify-content:space-between;" type="submit" name="submit"><?php echo $LANG_LOGIN_BUTTON; ?><span class="lnr lnr-arrow-right" style=""></span></button>
                                </div>
                            </div>
                        </div>
                    <div>
                </form>
            </div>
        </div>
    </section>
    <div class="caliweb-login-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <!--
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Corporation - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php
    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/loginFooter.php');
?>