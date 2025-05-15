<?php

    session_start();

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {

        $legalStructure = htmlspecialchars($_POST['businessLegalStructure']);

        $businessType = htmlspecialchars($_POST['businessType']);

        if (!isset($_SESSION['nexureApplication'])) {

            $_SESSION['nexureApplication'] = [];

        }

        $_SESSION['nexureApplication']['businessLegalStructure'] = $legalStructure;

        $_SESSION['nexureApplication']['businessType'] = $businessType;

        header("Location: /Onboarding/OpenAccount/Application/Business/BusinessInformation");

        exit;

    }

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Headers/index.php");

?>

<section class="section nexure-open-online-access-and-account">
    <div class="container nexure-container">
        <div style="display:flex; align-items:top;">
            <div>
                <h5 class="font-18px">Need help?</h5>
                <p class="font-14px margin-top-10px">If you need help with your application, call us at <?php echo $VariableDefinitionHandler->organizationSupportInfo; ?></p>
            </div>
            <div class="centered-content">
                <h5 class="font-20px">Tell us your business structure and type</h5>
                <p class="font-14px margin-top-20px width-60 centered-content">You can find what structure and type your business is by looking at your formation document or by visiting the Secretary of State's website where your business is registered.</p>
                <form action="" method="POST">
                    <div class="nexure-grid nexure-two-grid centered-content width-60 margin-top-60px gap-row-spacing-30">
                        <div class="form-control" style="text-align:left; align-items:start;">
                            <label for="">Legal business structure</label>
                            <select class="nexure-textbox" name="businessLegalStructure">
                                <option>Sole proprietorship</option>
                                <option>Limited Liability Company (LLC)</option>
                                <option>Corporation</option>
                                <option>Partnership or other</option>
                            </select>
                        </div>
                        <div class="form-control" style="text-align:left; align-items:start;">
                            <label for="">Business type</label>
                            <select class="nexure-textbox" name="businessType">
                                <option>Sole proprietorship</option>
                                <option>Member-managed</option>
                                <option>Manager-managed</option>
                                <option>S-Corporation</option>
                                <option>C-Corporation</option>
                                <option>General Partnership</option>
                                <option>Limited Liability Partnership</option>
                            </select>
                        </div>
                    </div>
                    <div class="width-80">
                        <div class="button-area" style="text-align:right; align-items:end;">
                            <button class="nexure-button primary float-right" style="padding:10px 50px !important;" type="submit" name="submit">Next</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php

    include($_SERVER["DOCUMENT_ROOT"]."/Modules/NexureSolutions/Login/Footers/index.php"); 

?>