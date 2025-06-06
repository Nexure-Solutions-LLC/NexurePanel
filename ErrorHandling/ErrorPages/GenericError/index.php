<?php

    $PageTitle = "Generic system error";

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

<section class="section generic-system-pages">
    <div class="container nexure-container">
        <div style="display:flex; align-items:center;">
            <div>
                <img src="<?php echo $VariableDefinitionHandler->organizationWideLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo light-mode" style="margin-top:12%; width:12%;">
                <img src="<?php echo $VariableDefinitionHandler->organizationWideLogoDark; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo dark-mode" style="margin-top:12%; width:12%;">
                <h6 class="secondary-font" style="font-weight:300; font-size:25px; margin:0; padding:0; margin-top:4%; margin-bottom:3%;"><?php echo $LANG_GENERICERROR_TITLE; ?></h6>
                <p class="nexure-login-sublink license-text-dark width-80" style="margin-bottom:3%;"><?php echo $LANG_GENERICERROR_SUBTEXT; ?></p>
                <?php

                    if (isset($_SESSION['error_log_file'])) {

                        $errorLogFilePath = $_SESSION['error_log_file'];

                        if (file_exists($errorLogFilePath) && is_readable($errorLogFilePath)) {

                            $errorLogContent = file_get_contents($errorLogFilePath);

                            echo "<pre>$errorLogContent</pre>";

                        } else {

                            echo '';

                        }

                        unset($_SESSION['error_log_file']);
                    }

                ?>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php");

?>