<?php

    $pagetitle = "Employment Application";
    $pagesubtitle = "Your applying to work at Cali Web Design.";
    $pagetype = "Work History Application Page";

    require($_SERVER["DOCUMENT_ROOT"].'/components/CaliEmployees/Application.php');

    echo '<title>Complete onboarding of employment.</title>';

?>

    <section class="login-container" style="animation: sideDrop 0.3s ease-out;">
        <div class="container caliweb-container bigscreens-are-strange" style="height: 100%;width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:4%;">
                <h3 class="caliweb-login-heading"><?php echo $variableDefinitionX->orgShortName; ?> <span style="font-weight:700">Employment Application</span></h3>
                <p style="font-size:12px; margin-top:0%;">Please provide your work experiance from previous employers.</p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div id="employer-forms-container">
                        <div class="caliweb-header">
                            <div class="header-text" style="justify-content:space-between;">
                                Refrence
                                <span class="icon-container" onclick="addNewForm()">
                                    <span class="icon-trash lnr lnr-plus-circle" style="font-size: 20px"></span>
                                </span>
                            </div>
                        </div>    
                        <div class="caliweb-grid caliweb-one-grid no-grid-row-bottom" id="employer-form-1">
                            <div style="margin-left:0">
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="refrenceName" class="text-gray-label">Refrence Legal Name</label>
                                    <input type="text" class="form-input" name="refrenceName" id="refrenceName" placeholder="" required="" />
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="companyJobTitle" class="text-gray-label">Company & Job Title</label>
                                    <input type="text" class="form-input" name="companyJobTitle" id="companyJobTitle" placeholder="" required="" /> 
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="refrencePhoneNumber" class="text-gray-label">Refrence Phone Number</label>
                                    <input type="text" class="form-input" name="refrencePhoneNumber" id="refrencePhoneNumber" placeholder="" required="" /> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:-2%;">
                        <div id="tooltip" class="tooltip" style="display: none;">You have reached the maximum number of forms.</div>
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
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <!--
                    THIS TEXT IS TO GIVE CREDIT TO THE AUTHORS AND REMOVING IT
                    MAY CAUSE YOUR LICENSE TO BE REVOKED.
                -->
                <div class="">
                    <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Services LLC - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $variableDefinitionX->paneldomain; ?>/privacy">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

    <script>

        let formCount = 1;
        const maxForms = 3;

        function addNewForm() {

            if (formCount < maxForms) {

                formCount++;
                const container = document.getElementById('employer-forms-container');
                const newForm = document.createElement('div');
                newForm.classList.add('caliweb-grid', 'caliweb-one-grid', 'no-grid-row-bottom', 'mt-10-per');
                newForm.id = `employer-form-${formCount}`;

                newForm.innerHTML = `
                   <div style="margin-left:0">
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="refrenceName" class="text-gray-label">Refrence Legal Name</label>
                                    <input type="text" class="form-input" name="refrenceName" id="refrenceName" placeholder="" required="" />
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="companyJobTitle" class="text-gray-label">Company & Job Title</label>
                                    <input type="text" class="form-input" name="companyJobTitle" id="companyJobTitle" placeholder="" required="" /> 
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="refrencePhoneNumber" class="text-gray-label">Refrence Phone Number</label>
                                    <input type="text" class="form-input" name="refrencePhoneNumber" id="refrencePhoneNumber" placeholder="" required="" /> 
                                </div>
                            </div>
                `;

                container.appendChild(newForm);

            } else {

                showTooltip();

            }

        }

        function showTooltip() {

            const tooltip = document.getElementById('tooltip');
            tooltip.style.display = 'block';

            setTimeout(() => {

                tooltip.style.display = 'none';

            }, 3000);

        }

        function deleteForm(formId) {

            const form = document.getElementById(`employer-form-${formId}`);

            if (form) {

                form.remove();
                formCount--;

            }

        }
    </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/components/CaliFooters/Login.php');
    
?>
