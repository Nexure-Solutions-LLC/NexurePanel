<?php

    $pagetitle = "Cases";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

     include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Headers/index.php');

    // When form submitted, insert values into the database.

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $current_time = time();

        // Check if the last submission time is stored in the session
        
        if (isset($_SESSION['last_submit_time'])) {

            $time_diff = $current_time - $_SESSION['last_submit_time'];

            if ($time_diff < 5) {

                header("Location: /error/rateLimit");
                exit;

            }
        }

        // If the rate limit check passes, update the last submission time

        $_SESSION['last_submit_time'] = $current_time;

        // Personal Information Section

        $casetitle = stripslashes($_REQUEST['casetitle']);
        $casetitle = mysqli_real_escape_string($con, $casetitle);
        $customersearch = stripslashes($_REQUEST['customersearch']);
        $customersearch = mysqli_real_escape_string($con, $customersearch);
        $casestatus = stripslashes($_REQUEST['casestatus']);
        $casestatus = mysqli_real_escape_string($con, $casestatus);
        $assignedagent = stripslashes($_REQUEST['assignedagent']);
        $assignedagent = mysqli_real_escape_string($con, $assignedagent);
        $casedescription = stripslashes($_REQUEST['casedescription']);
        $casedescription = mysqli_real_escape_string($con, $casedescription);

        // System Feilds

        $casecreated = date("Y-m-d H:i:s");

        // Database Calls
        
        $caseInsertRequest = "INSERT INTO `nexure_cases`(`caseNumber`, `emailAddress`, `customerName`, `caseTitle`, `caseCreateDate`, `caseCloseDate`, `caseStatus`, `caseDescription`, `assignedAgent`, `assignedDepartment`, `accountNumber`) VALUES ('','$customersearch','','$casetitle','$casecreated','0000-00-00','$casestatus','$casedescription','$assignedagent','','')";
        $caseInsertResult = mysqli_query($con, $caseInsertRequest);

        if ($caseInsertResult) {

            header ("location: /dashboard/administration/cases");
        

        } else {

            header ("location: /error/genericSystemError");

        }

    }

    echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <form method="POST" action="">
                        <div class="card-header">
                            <div class="display-flex align-center" style="justify-content: space-between;">
                                <div class="display-flex align-center">
                                    <div class="no-padding margin-10px-right icon-size-formatted">
                                        <img src="/assets/img/systemIcons/cases.png" alt="Cases Page Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">Cases</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Create Case</h4>
                                    </div>
                                </div>
                                <div>
                                    <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                    <a href="/dashboard/administration/cases/createCase/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/cases/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fillable-section-holder" style="margin-top:-3% !important; padding-bottom:3%;">
                                <div class="fillable-header">
                                    <p class="fillable-text">Case Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important;">
                                        <div class="form-left-side" style="width:80%;">
                                            <div class="form-control">
                                                <label for="casetitle">Case Title</label>
                                                <input type="text" name="casetitle" id="casetitle" class="form-input" placeholder="Please give a title for your case." required="" />
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="customersearch">Customer</label>
                                                <input type="text" name="customersearch" id="customersearch" class="form-input" placeholder="John Doe" required="" />
                                                <div id="customersearchresults" class="indivdual-search-results"></div>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="casestatus">Case Status</label>
                                                <select type="text" name="casestatus" id="casestatus" class="form-input">
                                                    <option>Please choose an option</option>
                                                    <option>Open</option>
                                                    <option>Closed</option>
                                                    <option>On Hold</option>
                                                    <option>Pending</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-left-side" style="display:block; width:80%;">
                                            <div class="form-control">
                                                <label for="assignedagent">Assigned Agent</label>
                                                <input type="email" name="assignedagent" id="assignedagent" class="form-input" placeholder="me@example.com" required="" />
                                                <div id="assignedagentresults" class="indivdual-search-results"></div>
                                            </div>
                                            <div class="form-control" style="padding-top:10px;">
                                                <label for="casedescription">Case Description</label>
                                                <textarea style="height:150px" type="text" name="casedescription" id="casedescription" class="form-input" required=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/NexureSolutions/Utility/Backend/Dashboard/Footers/index.php');

?>