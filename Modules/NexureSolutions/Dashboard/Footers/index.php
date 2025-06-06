        <section class="preloader">
            <div style="margin-left:auto;margin-right:auto;max-width:80%;">
                <div class="logo" style="margin-top:-6%;">
                    <img src="<?php echo $VariableDefinitionHandler->organizationWideLogo; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo light-mode" style="width:150px;">
                    <img src="<?php echo $VariableDefinitionHandler->organizationWideLogoDark; ?>" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo dark-mode" style="width:150px;">
                </div>
                <div style="margin-top:6%;" class="loading-bar">
                    <div class="loading-bar-inner"></div>
                </div>
            </div>
        </section>
        
        <?php 
                
            if ($VariableDefinitionHandler->panelTheme != "NexureDefault") {
                
                echo '<script src="/Themes/'.$VariableDefinitionHandler->panelTheme.'/Assets/js/index.js"" type="text/javascript"></script>';

            }

        ?>

        <script type="text/javascript"  src="/Assets/js/Dashboard.js"></script>
        <script src="https://nexuresolutions.com/assets/js/index.js" type="text/javascript"></script>
    </body>
</html>