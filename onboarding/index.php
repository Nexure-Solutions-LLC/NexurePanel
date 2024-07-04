<?php
    include($_SERVER["DOCUMENT_ROOT"]."/assets/php/loginHeader.php");
    echo '<title>Complete onbording of your new account.</title>';
?>

    <section class="login-container">
        <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                <h3 class="caliweb-login-heading"><?php echo $orgshortname; ?> <span style="font-weight:700">Onboarding</span></h3>
                <p style="font-size:12px; margin-top:0%;">You have made the first step into getting your account. Now its time to finish and get rolling.</p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div class="caliweb-grid caliweb-two-grid">
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="legalname" class="text-gray-label">Legal Name</label>
                                <input type="text" class="form-input" name="legalname" id="legalname" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="phonenumber" class="text-gray-label">Phone Number</label>
                                <input type="text" class="form-input" name="phonenumber" id="phonenumber" placeholder="" required="" />
                            </div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="emailaddress" class="text-gray-label">Email Address</label>
                                <input type="email" class="form-input" name="emailaddress" id="emailaddress" placeholder="" required="" />
                            </div>
                        </div>
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="dateofbirth" class="text-gray-label">Date of Bith (Must be 14 years or older)</label>
                                <input type="date" class="form-input" name="dateofbirth" id="dateofbirth" placeholder="" requird="" />
                            </div>
                            <div style="border:1px solid green; border-radius:8px; padding:10px; margin-top:0%;">
                                <label style="color:green; font-size:12px;">This is a secure form and will be verified with the IRS.</label>
                                <div class="form-control" style="margin-top:4%;">
                                    <label for="einssnnumber" class="text-gray-label">EIN or SSN Number</label>
                                    <input type="text" class="form-input" name="einssnnumber" id="einssnnumber" placeholder="" requird="" />
                                </div>
                                <p style="font-size:12px; padding:0; margin:0;">Why do we require this? To comply with financial regulations and provide our full rage of services we need to fully verfiy you and your business. We also use this information when we run a soft credit-check.</p>
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