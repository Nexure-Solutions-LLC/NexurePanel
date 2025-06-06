        
        <!-- Nexure Login Footer with copyright text and terms of service/privacy policy links -->

        <div class="nexure-footer">
            <div class="container nexure-container">
                <div class="display-flex align-center justify-content-space-between">
                    <div>
                        <p class="font-12px">&copy; <span id="nexure-year" class="nexure-year"></span> Nexure Solutions LLP - It is illegal to reproduce this website - All Rights Reserved.</p>
                    </div>
                    <div>
                        <a class="font-12px margin-right-10px" href="https://nexuresolutions.com/terms">Terms of Service</a>
                        <a class="font-12px" href="https://nexuresolutions.com/privacy">Privacy Policy</a>
                    </div>
                </div>
            </div>
        </div>
    
    </body>

    <?php 
            
        if ($VariableDefinitionHandler->panelTheme != "NexureDefault") {
            
            echo '<script src="/Themes/'.$VariableDefinitionHandler->panelTheme.'/Assets/js/index.js"" type="text/javascript"></script>';

        }

    ?>

    <script type="text/javascript"  src="/Assets/js/Login.js"></script>
    <script src="https://nexuresolutions.com/assets/js/index.js" type="text/javascript"></script>
</html>