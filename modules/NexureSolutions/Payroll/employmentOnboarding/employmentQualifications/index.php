<?php

    $pagetitle = "Employment Application";
    $pagesubtitle = "Your applying to work at Nexure.";
    $pagetype = "Work History Application Page";

    require($_SERVER["DOCUMENT_ROOT"].'/components/CaliEmployees/Application.php');
        
    echo '<title>Complete onboarding of employment.</title>';

?>

    <section class="login-container" style="animation: sideDrop 0.3s ease-out;">
        <div class="container caliweb-container bigscreens-are-strange" style="width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:7%;">
                <h3 class="caliweb-login-heading"><?php echo $variableDefinitionX->orgShortName; ?> <span style="font-weight:700">Employee Onboarding</span></h3>
                <p style="font-size:12px; margin-top:0%;">You have made the first step into getting employment. Now its time to finish and get rolling.</p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div class="caliweb-grid caliweb-two-grid no-grid-row-bottom">
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label  class="text-gray-label">Check All That Apply</label>
                                <div class="caliweb-grid caliweb-two-grid no-grid-row-bottom">
                                    <div class="check-box" >
                                        <input type="checkbox" id="communicationskills" name="communicationskills" value="communicationskills">
                                        <label for="communicationskills">Communication Skills</label>
                                    </div>
                                    <div class="check-box">
                                        <input type="checkbox" id="marketingskills" name="marketingskills" value="marketingskills">
                                        <label for="marketingskills">Marketing Skills</label>
                                    </div>
                                    <div class="check-box">
                                        <input type="checkbox" id="graphicdesignskills" name="graphicdesignskills" value="graphicdesignskills">
                                        <label for="graphicdesignskills">Graphic Design Skills</label>
                                    </div>
                                    <div class="check-box">
                                        <input type="checkbox" id="graphicdesignskills" name="timemanagementskills" value="timemanagementskills">
                                        <label for="timemanagementskills">Time Management</label>
                                    </div>
                                    <div class="check-box">
                                        <input type="checkbox" id="contentmangementskills" name="contentmangementskills" value="contentmangementskills">
                                        <label for="contentmangementskills">Content Management Skills</label>
                                    </div>
                                    <div class="check-box">
                                        <input type="checkbox" id="technicalcomputerskills" name="technicalcomputerskills" value="technicalcomputerskills">
                                        <label for="technicalcomputerskills">Technical Computer Skills</label>
                                    </div>
                                    <div class="check-box">
                                        <input type="checkbox" id="webanalyticsskills" name="webanalyticsskills" value="webanalyticsskills">
                                        <label for="webanalyticsskills">Web Analytics Skills</label>
                                    </div>
                                    <div class="check-box">
                                        <input type="checkbox" id="publicspeakingskills" name="publicspeakingskills" value="publicspeakingskills">
                                        <label for="publicspeakingskills">Public Speaking</label>
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                        <div>
                            <div class="form-control" style="margin-top:-2%;">
                                <label for="jobrelatedtraining" class="text-gray-label">Summarize Your Skills And Qualifications</label>
                                <input type="text" style="height:250px;" class="form-input" name="jobrelatedtraining" id="jobrelatedtraining" value="" required=""  />
                            </div>
                        </div>
                    </div>
                    <div class="onboarding-button-container" style="margin-top:6%;">
                        <button class="onboarding-button" type="submit" name="submit" style="float: right;">
                            <span class="lnr lnr-arrow-right"></span>
                            <span class="tooltip-text">Next Question</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="caliweb-login-footer">
        <div class="container caliweb-container">
            <div class="caliweb-grid-2">
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <!--
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; <span id="nexure-year"></span> - Nexure Solutions LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE Nexure COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Login/Footers/index.php');

?>