<?php

   include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

<section class="section generic-system-pages">
    <div class="container nexure-container">
        <div style="display:flex; align-items:center;">
            <div>
                <img src="https://nexuresolutions.com/assets/img/logos/NexureWideLogoBlack.svg" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo light-mode" style="margin-top:12%; width:15%;">
                <img src="https://nexuresolutions.com/assets/img/logos/NexureWideLogoWhite.svg" loading="lazy" alt="Nexure Solutions Logo" class="nexure-logo dark-mode" style="margin-top:12%; width:15%;">
                <h6 style="font-weight:700; font-size:25px; margin:0; padding:0; margin-top:5%; margin-bottom:5%;">You have successfully signed off we hope you have a Good <span id="lblGreetings"></span>.</h6>
                <p class="nexure-login-sublink license-text-dark width-100">Your session has expired. You will be automatically redirected to the login page. If your not redirected within <span id="countdown"></span> seconds then use <a href="/">this link</a> to return to the login page.</p>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php");

?>