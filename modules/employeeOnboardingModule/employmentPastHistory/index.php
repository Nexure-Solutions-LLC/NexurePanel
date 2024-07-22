<?php

    $pagetitle = "Employment Application";
    $pagesubtitle = "Your applying to work at Cali Web Design.";
    $pagetype = "Work History Application Page";

    require($_SERVER["DOCUMENT_ROOT"].'/components/CaliEmployees/Application.php');

    echo '<title>Complete onboarding of employment.</title>';

?>

    <section class="login-container">
        <div class="container caliweb-container bigscreens-are-strange" style="height: 100%;width:50%; margin-top:4%;">
            <div class="caliweb-login-box-header" style="text-align:left; margin-bottom:4%;">
                <h3 class="caliweb-login-heading"><?php echo $orgshortname; ?> <span style="font-weight:700">Employment Application | Work Experience</span></h3>
                <p style="font-size:12px; margin-top:0%;">Please provide your home address so that we can make sure your location is supported.</p>
            </div>
            <div class="caliweb-login-box-body">
                <form action="" method="POST" id="caliweb-form-plugin" class="caliweb-ix-form-login">
                    <div id="employer-forms-container">
                        <div class="caliweb-grid" id="employer-form-1">
                            <div class="caliweb-header">
                                <header class="header-text">
                                    Employer 
                                    <span class="icon-container" style="margin-left: 98%; ;" onclick="addNewForm()">
                                        <span class="icon-trash lnr lnr-plus" style="font-size: 20px"></span>
                                    </span>
                                </header>
                            </div>    
                            <div style="margin-left:0">
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="addressline1" class="text-gray-label">Company Name</label>
                                    <input type="text" class="form-input" name="addressline1[]" id="addressline1" placeholder="" required="" />
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="city" class="text-gray-label">Dates Of Employment</label>
                                    <input type="date" class="form-input" name="dateofbirth_start[]" id="dateofbirth_start" placeholder="" required="" /> 
                                    <input type="date" class="form-input" name="dateofbirth_end[]" id="dateofbirth_end" placeholder="" required="" /> 
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="jobtitle" class="text-gray-label">Job Title</label>
                                    <input type="text" class="form-input" name="jobtitle[]" id="jobtitle" placeholder="" required="" />
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="responsibility" class="text-gray-label">Job Responsibility </label>
                                    <input type="text" class="form-input" name="responsibility[]" id="responsibility" placeholder="" required="" />
                                </div>
                                <div class="form-control" style="margin-top:-2%;">
                                    <label for="reason" class="text-gray-label">Reason For Leaving</label>
                                    <input type="text" class="form-input" name="reason[]" id="reason" placeholder="" required="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top:-2%;">
                        <div id="tooltip" class="tooltip" style="display: none;">You have reached the maximum number of forms.</div>
                    </div>
                    <div class="onboarding-button-container">
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
                    <p class="caliweb-login-footer-text">&copy; 2024 - Cali Web Design Corporation - All rights reserved. It is illegal to copy this website.</p>
                </div>
                <!-- DO NOT REMOVE THE CALI WEB DESIGN COPYRIGHT TEXT -->
                <div class="list-links-footer">
                    <a href="<?php echo $paneldomain; ?>/terms">Terms of Service</a>
                    <a href="<?php echo $paneldomain; ?>/privacy">Privacy Policy</a>
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
                newForm.classList.add('caliweb-grid');
                newForm.id = `employer-form-${formCount}`;
                newForm.innerHTML = `
                    <div class="caliweb-header">
                        <header class="header-text">
                            Employer 
                            <span class="icon-container" style="margin-left: 98%; ;" onclick="deleteForm(${formCount})">
                                <span class="icon-trash lnr lnr-trash" style="font-size: 20px"></span>
                                <span class="icon-overlay">X</span>
                            </span>
                        </header>

                    </div>    
                    <div style="margin-left:0">
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="addressline1-${formCount}" class="text-gray-label">Company Name</label>
                            <input type="text" class="form-input" name="addressline1[]" id="addressline1-${formCount}" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="city-${formCount}" class="text-gray-label">Dates Of Employment</label>
                            <input type="date" class="form-input" name="dateofbirth_start[]" id="dateofbirth_start-${formCount}" placeholder="" required="" /> 
                            <input type="date" class="form-input" name="dateofbirth_end[]" id="dateofbirth_end-${formCount}" placeholder="" required="" /> 
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="jobtitle-${formCount}" class="text-gray-label">Job Title</label>
                            <input type="text" class="form-input" name="jobtitle[]" id="jobtitle-${formCount}" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="responsibility-${formCount}" class="text-gray-label">Job Responsibility </label>
                            <input type="text" class="form-input" name="responsibility[]" id="responsibility-${formCount}" placeholder="" required="" />
                        </div>
                        <div class="form-control" style="margin-top:-2%;">
                            <label for="reason-${formCount}" class="text-gray-label">Reason For Leaving</label>
                            <input type="text" class="form-input" name="reason[]" id="reason-${formCount}" placeholder="" required="" />
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
            }, 3000); // it hides the tooltip after 3 seconds
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

    include($_SERVER["DOCUMENT_ROOT"].'/assets/php/loginFooter.php');
    
?>
