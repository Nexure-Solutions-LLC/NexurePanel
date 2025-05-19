<?php

    session_start();

    include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Headers/index.php");

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | Personal Account Application</title>

    <style>
        body {
            overflow-y: auto !important;
        }
    </style>

    <section class="section nexure-open-online-access-and-account" style="padding-bottom:7%;">
        <div class="container nexure-container">
            <div style="display:flex; align-items:top;">
                <div>
                    <h5 class="font-18px">Need help?</h5>
                    <p class="font-14px margin-top-10px">If you need help with your application, call us at <?php echo $VariableDefinitionHandler->organizationSupportInfo; ?></p>
                </div>
                <div class="margin-left-80px">
                    <h4 class="font-20px">Identity Verification</h4>
                    <p class="font-14px margin-top-20px width-60">For compliance reasons to open an account online, <?php echo $VariableDefinitionHandler->organizationShortName; ?> now collects identity verification documents to open an account.</p>
                    <button id="verifyBtn" class="nexure-button primary margin-top-40px"  style="padding:10px 50px;">Verify Identity</button>
                </div>
            </div>
        </div>
    </section>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Footers/index.php"); ?>
