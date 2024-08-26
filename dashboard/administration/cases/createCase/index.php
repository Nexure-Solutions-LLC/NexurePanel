<?php

    $pagetitle = "Cases";
    $pagesubtitle = "Create";
    $pagetype = "Administration";

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

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

        $taskname = stripslashes($_REQUEST['taskname']);
        $taskname = mysqli_real_escape_string($con, $taskname);
        $duedate = stripslashes($_REQUEST['duedate']);
        $duedate = mysqli_real_escape_string($con, $duedate);
        $assigneduser = stripslashes($_REQUEST['assigneduser']);
        $assigneduser = mysqli_real_escape_string($con, $assigneduser);
        $assigneduser = stripslashes($_REQUEST['assigneduser']);
        $assigneduser = mysqli_real_escape_string($con, $assigneduser);
        $taskstatus = stripslashes($_REQUEST['taskstatus']);
        $taskstatus = mysqli_real_escape_string($con, $taskstatus);
        $taskdescription = stripslashes($_REQUEST['taskdescription']);
        $taskdescription = mysqli_real_escape_string($con, $taskdescription);
        $taskpriority = stripslashes($_REQUEST['taskpriority']);
        $taskpriority = mysqli_real_escape_string($con, $taskpriority);

        // System Feilds

        $taskstartdate = date("Y-m-d H:i:s");

        // Database Calls
        
        $taskInsertRequest = "";
        $taskInsertResult = mysqli_query($con, $taskInsertRequest);

        if ($taskInsertResult) {

            header ("location: /dashboard/administration/tasks");
        

        } else {

            header ("location: /error/genericSystemError");

        }

    } else {

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

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
                                        <img src="/assets/img/systemIcons/cases.png" alt="Cases Page Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#assignedagent').on('input', function() {

                var searchTerm = $(this).val().trim().toLowerCase();
                var resultsContainer = $('#assignedagentresults');
                resultsContainer.empty();

                $.ajax({

                    url: '/dashboard/administration/cases/createCase/agentSearchLogic/index.php',
                    dataType: 'json',

                    data: {
                        term: searchTerm
                    },

                    success: function(data) {

                        data.forEach(function(agent) {

                            var item = $('<div class="indivdual-search-div">' + agent.legalName + ' (' + agent.email + ')</div>');

                            item.on('click', function() {

                                $('#assignedagent').val(agent.email);
                                resultsContainer.hide();

                            });

                            resultsContainer.append(item);

                        });

                        if (searchTerm.length > 0) {

                            resultsContainer.show();

                        } else {

                            resultsContainer.hide();

                        }

                    },

                    error: function(xhr, status, error) {

                        console.error('AJAX Error:', status, error);

                    }

                });

            });

            $(document).on('click', function(e) {

                if (!$(e.target).closest('#assignedagentresults').length && !$(e.target).is('#assignedagent')) {

                    $('#assignedagentresults').hide();

                }

            });
            
        });

        $(document).ready(function() {

            $('#customersearch').on('input', function() {

                var searchTerm = $(this).val().trim().toLowerCase();
                var resultsContainer = $('#customersearchresults');
                resultsContainer.empty();

                $.ajax({

                    url: '/dashboard/administration/cases/createCase/customerSearchLogic/index.php',
                    dataType: 'json',

                    data: {
                        term: searchTerm
                    },

                    success: function(data) {

                        data.forEach(function(customer) {

                            var item = $('<div class="indivdual-search-div">' + customer.legalName + ' (' + customer.accountNumber + ')</div>');

                            item.on('click', function() {

                                $('#customersearch').val(customer.legalName);
                                resultsContainer.hide();

                            });

                            resultsContainer.append(item);

                        });

                        if (searchTerm.length > 0) {

                            resultsContainer.show();

                        } else {

                            resultsContainer.hide();

                        }

                    },

                    error: function(xhr, status, error) {

                        console.error('AJAX Error:', status, error);

                    }

                });

            });

            $(document).on('click', function(e) {

                if (!$(e.target).closest('#customersearchresults').length && !$(e.target).is('#customersearch')) {

                    $('customersearchresults').hide();

                }

            });
            
        });
    </script>

<?php

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

    }

?>