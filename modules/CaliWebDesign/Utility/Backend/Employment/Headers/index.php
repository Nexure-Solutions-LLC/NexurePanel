    <div class="caliweb-card dashboard-card custom-padding-account-card">
        <div class="card-header-account">
            <div class="display-flex align-center">
                <div class="no-padding margin-10px-right icon-size-formatted">
                    <img src="/assets/img/customerBusinessLogos/defaultstore.png" alt="Client Logo and/or Business Logo" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                </div>
                <div>
                    <p class="no-padding font-14px" style="padding-bottom:4px;">Employee</p>
                    <h4 class="text-bold font-size-16 no-padding display-flex align-center">
                        <?php echo $manageEmployeeDefinitionE->employeename; ?> - <?php echo $employeeIDNumber; ?>

                        <span class='account-status-badge green'>Active</span>
                    </h4>
                </div>
            </div>
            <div class="display-flex align-center">
                <a href="" class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;">Edit</a>
            </div>
        </div>
        <div class="card-body width-100 macBook-styling-hotfix">
            <div class="display-flex align-center width-100 padding-20px-no-top macBook-padding-top">

                <?php

                    $employeeDetails = [
                        "Department" => $manageEmployeeDefinitionE->employeedepartment,
                        "Phone Number" => $manageEmployeeDefinitionE->employeephonenumber,
                        "Hire Date" => $manageEmployeeDefinitionE->employeehiredateformattedfinal,
                        "Termination Date" => $manageEmployeeDefinitionE->employeetermhiredateformattedfinal,
                        "Rehire Date" => $manageEmployeeDefinitionE->employeerehiredateformattedfinal,
                        "Current Pay" => "$" . $manageEmployeeDefinitionE->employeecurrentpay,
                        "Worked Hours" => $manageEmployeeDefinitionE->employeeworkedhours
                    ];

                    foreach ($employeeDetails as $label => $value) {

                        echo '<div class="width-100">';
                            echo '<p class="no-padding font-14px">' . htmlspecialchars($label) . '</p>';
                            echo '<p class="no-padding font-14px">' . htmlspecialchars($value) . '</p>';
                        echo '</div>';

                    }

                ?>

            </div>
        </div>
    </div>