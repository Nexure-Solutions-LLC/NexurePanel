<?php

    session_start();

    include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Headers/index.php");

?>

    <title><?php echo $VariableDefinitionHandler->organizationShortName; ?> Unified Panel | Fee Disclosures</title>

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
                    <h4 class="font-20px"><?php echo $VariableDefinitionHandler->organizationShortName; ?> Fee Disclosures</h4>
                    <p class="font-14px margin-top-20px width-60"><?php echo $VariableDefinitionHandler->organizationShortName; ?> has additional fees that may be incurred on your account. We advise all customers to look at the fee disclosures before they finish opening the account.</p>
                    <iframe src="<?php echo $VariableDefinitionHandler->feeDisclosureLink; ?>" class="margin-top-50px margin-bottom-50px" style="height:800px; width:80%; border:0; outline:0;"></iframe>
                    <br><br>
                    <a class="nexure-button primary" style="padding:10px 50px;" href="">Accept</a>
                </div>
            </div>
        </div>
    </section>

<?php include($_SERVER["DOCUMENT_ROOT"] . "/Modules/NexureSolutions/Login/Footers/index.php"); ?>