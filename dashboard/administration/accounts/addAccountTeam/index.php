<?php

    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = $pagetitle = "Customer Accounts";
    $_SESSION['pagesubtitle'] = $pagesubtitle = "Assign Account Teams";
    $pagetype = "Administration";

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    $accountnumber = $_GET['account_number'];

    if ($accountnumber == "") {

        header("location: /dashboard/administration/accounts");
        exit;

    }

    $customerAccountQuery = mysqli_query($con, "SELECT * FROM caliweb_users WHERE accountNumber = '".$accountnumber."'");
    $customerAccountInfo = mysqli_fetch_array($customerAccountQuery);
    mysqli_free_result($customerAccountQuery);

    if (!$customerAccountInfo) {

        header("location: /dashboard/administration/accounts");
        exit();

    }

    if ($customerAccountInfo != NULL) {

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

            // Retrieve and sanitize the input

            $teaminfo = mysqli_real_escape_string($con, $_POST["teaminfo"]);

            $parts = explode(' - ', $teaminfo);

            if (count($parts) === 3) {

                $teamName = $parts[0];

                $teamTitle = $parts[1];

                $teamRole = isset($parts[2]) ? $parts[2] : '';

                $insertQuery = "INSERT INTO `caliweb_teamslisting` (`accountNumber`, `teamName`, `teamTitle`, `teamRole`, `teamIconFile`) VALUES ($accountnumber, ?, ?, ?, ?)";

                $stmt = $con->prepare($insertQuery);

                $teamIconFile = 'teamsdefault.png';

                $stmt->bind_param('ssss', $teamName, $teamTitle, $teamRole, $teamIconFile);

                if ($stmt->execute()) {

                   header("location: /dashboard/administration/accounts/manageAccount/profile/?account_number=$accountnumber");

                } else {

                    header("location: /error/genericSystemError");
                    exit;

                }

                $stmt->close();

            }
            

        }

        echo '<title>'.$pagetitle.' | '.$pagesubtitle.'</title>';

?>

        <style>
            input[type=number] {
                -moz-appearance:textfield;
            }

            input[type=number]::-webkit-outer-spin-button,
            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            textarea {
                resize: none;
            }
        </style>

        <section class="section first-dashboard-area-cards">
            <div class="container width-98">
                <div class="caliweb-one-grid special-caliweb-spacing">
                    <div class="caliweb-card dashboard-card">
                        <form method="POST" action="">
                            <div class="card-header">
                                <div class="display-flex align-center" style="justify-content: space-between;">
                                    <div class="display-flex align-center">
                                        <div class="no-padding margin-10px-right icon-size-formatted">
                                            <img src="/assets/img/systemIcons/cases.png" alt="Write Account Notice Icon" style="background-color:#f5e6fe;" class="client-business-andor-profile-logo" />
                                        </div>
                                        <div>
                                            <p class="no-padding font-14px">Accounts</p>
                                            <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">Team Assignment</h4>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                        <a href="/dashboard/administration/accounts/addAccountTeam/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                        <a href="/dashboard/administration/accounts/manageAccount/profile/?account_number=<?php echo $accountnumber; ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="fillable-section-holder" style="margin-top:-3% !important;">
                                    <div class="fillable-header">
                                        <p class="fillable-text">Team Information</p>
                                    </div>
                                    <div class="fillable-body">
                                        <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important; margin-bottom:4%;">
                                            <div class="form-left-side" style="width:80%;">
                                                
                                                <div class="form-control" style="margin-top:20px;">
                                                    <label for="type">Teams</label>
                                                    <select type="text" name="teaminfo" id="teaminfo" class="form-input">
                                                        <option>Please choose an option</option>
                                                        <?php

                                                            $variableDefinitionsP = new \CaliWebDesign\Generic\VariableDefinitions();
                                                            $options = $variableDefinitionsP->getTeamsOptions($con);

                                                        ?>
                                                        <?php echo $options; ?>
                                                    </select>
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

        include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

    } else {

        // this used to return genericSystemError however its more fit for a redirection as it is
        // an invalid account number

        header("location: /dashboard/administration/accounts");

    }

?>