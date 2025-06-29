<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

<title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | Onboarding</title>

<style>
    .nexure-card:hover {
        border:1px solid #ddd !important;
    }

    .dark-mode .nexure-card:hover {
        border:1px solid #333 !important;
    }
</style>

<section class="section nexure-open-online-access-and-account">
    <div class="container nexure-container">
        <div style="display:flex; align-items:top;">
            <div>
                <h5 class="font-18px">Need help?</h5>
                <p class="font-14px margin-top-10px">If you need help with your application, call us at <?php echo $VariableDefinitionHandler->organizationSupportInfo; ?></p>
            </div>
            <div class="margin-left-80px">
                <h5 class="font-20px">Let's open your <?php echo $VariableDefinitionHandler->organizationShortName; ?> account</h5>
                <div class="nexure-card margin-top-30px width-70" style="text-align:left; align-items:start; border:1px solid transparent !important;">
                    <div class="card-body">
                        <p><strong>Before you start, you should know:</strong></p>
                        <div>
                            <p class="margin-top-10px">You may need your:</p>
                            <ul>
                                <li class="font-14px secondary-font">Driver's license or state ID</li>
                                <li class="font-14px secondary-font">Social Security number</li>
                                <li class="font-14px secondary-font">Mobile device with working camera for secure ID capture</li>
                            </ul>
                        </div>
                        <div>
                            <p class="margin-top-10px">Privately held businesses with the following structures can apply online:</p>
                            <ul>
                                <li class="font-14px secondary-font">Sole proprietorships</li>
                                <li class="font-14px secondary-font">Corporations</li>
                                <li class="font-14px secondary-font">Limited liability companies (LLCs) managed by a single member or manager</li>
                            </ul>
                        </div>
                        <div>
                            <p class="margin-top-10px">We'll save your progress throughout the application if you have a <?php echo $VariableDefinitionHandler->organizationShortName; ?> username and password.</p>
                            <ul>
                                <li class="font-14px secondary-font">Sign back in to start where you left off.</li>
                                <li class="font-14px secondary-font">New here? You'll create your username and password in the application.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer">
                        <p class="font-14px">Have a different business type? Call <?php echo $VariableDefinitionHandler->organizationShortName; ?> at <?php echo $VariableDefinitionHandler->organizationSupportInfo; ?> and we'll help you out.</p>
                    </div>
                </div>
                <div class="margin-top-50px display-flex align-center justify-content-space-between width-75">
                    <p class="font-14px width-50">We're required by law to ask for names, addresses, taxpayer ID numbers and other information to help us identify you, the business and its beneficial owners.</p>
                    <a href="/Onboarding/OpenAccount/Application/" class="nexure-button primary" style="padding:10px 24px !important;">Get started</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php"); 

?>