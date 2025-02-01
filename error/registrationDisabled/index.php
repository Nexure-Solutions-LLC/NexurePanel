<?php
    ob_start();
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    // Perform query
    
    $result = mysqli_query($con, "SELECT * FROM nexure_panelconfig WHERE id = '1'");
    $panelinfo = mysqli_fetch_array($result);

    // Free result set

    mysqli_free_result($result);

    $isregenabled = $panelinfo['isRegEnabled'];

    if ($isregenabled == "True" || $isregenabled == "true") {

        header("location:/registration");

    } else {

?>
<!-- Universal Rounded Floating Nexure Header Bar start -->   
<?php require($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Headers/index.php"); ?>

    <!-- 
        Unique Website Title Tag Start 
        The Page Title specified what page the user is on in 
        the browser tab and should be included for SEO
    -->
    <title><?php echo $variableDefinitionX->orgShortName; ?> - Registration Disabled</title>
    <!-- Unique Website Title Tag End -->

    <section class="section" style="padding-top:5%; padding-left:5%;">
        <div class="container caliweb-container">
                <h3 class="caliweb-login-heading license-text-dark"><?php echo $LANG_REG_DISABLED_TITLE_PAR_1; ?> <span style="font-weight:700;"><?php echo $LANG_REG_DISABLED_TITLE_PAR_2; ?></span></h3>
                <p class="caliweb-login-sublink license-text-dark" style="font-weight:700; padding-top:0; margin-top:0;"><?php echo $LANG_REG_DISABLED_TITLE; ?></p>
                <p class="caliweb-login-sublink license-text-dark width-50"><?php echo $LANG_REG_DISABLED_TEXT; ?></p>
            </div>
        </div>
    </section>

    <div class="caliweb-login-footer license-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <!-- 
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Nexure Solutions LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php include($_SERVER["DOCUMENT_ROOT"]."/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php"); ?>

<?php } ?>
