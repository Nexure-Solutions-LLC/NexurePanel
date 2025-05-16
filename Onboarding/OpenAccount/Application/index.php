<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

<title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | Onboarding Account Selection</title>

<section class="section nexure-open-online-access-and-account">
    <div class="container nexure-container">
        <div style="display:flex; align-items:top;">
            <div>
                <h5 class="font-18px">Need help?</h5>
                <p class="font-14px margin-top-10px">If you need help with your application, call us at <?php echo $VariableDefinitionHandler->organizationSupportInfo; ?></p>
            </div>
            <div class="centered-content">
                <h5 class="font-20px">Tell us the kind of account your opening</h5>
                <p class="font-14px margin-top-20px"><?php echo $VariableDefinitionHandler->organizationShortName; ?> offers two accounts personal and business. Choose the account that best fits your need.</p>
                <div class="width-60 centered-content margin-top-60px">
                    <a href="/Onboarding/OpenAccount/Application/Personal" class="onboarding-card-link">
                        <div class="nexure-card margin-top-40px" style="text-align:left; align-items:start;">
                            <div class="display-flex align-center">
                                <div>
                                    <span class="lnr lnr-home font-20px"></span>
                                </div>
                                <div class="margin-left-20px">
                                    <p><strong>Personal Account</strong></p>
                                    <p>Personal accounts are best for individuals who are not a business that need service for their project or portfolio. This is for non-commercial use.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="/Onboarding/OpenAccount/Application/Business" class="onboarding-card-link">
                        <div class="nexure-card margin-top-20px" style="text-align:left; align-items:start;">
                            <div class="display-flex align-center">
                                <div>
                                    <span class="lnr lnr-briefcase font-20px"></span>
                                </div>
                                <div class="margin-left-20px">
                                    <p><strong>Business Account</strong></p>
                                    <p>Business accounts are best for corporations, partnerships, or LLCs who are in need service for their business operations. This is for commercial use.</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php"); 

?>