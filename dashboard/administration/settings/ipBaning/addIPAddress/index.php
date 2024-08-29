<?php

    $pagetitle = "Settings";
    $pagesubtitle = "System Setup";
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

        $ipAddress = stripslashes($_REQUEST['ipAddress']);
        $ipAddress = mysqli_real_escape_string($con, $ipAddress);
        $listType = stripslashes($_REQUEST['listType']);
        $listType = mysqli_real_escape_string($con, $listType);
        
        $ipquery    = "INSERT INTO `caliweb_networks`(`ipAddress`, `listType`) VALUES ('$ipAddress','$listType')";
        $ipresult   = mysqli_query($con, $ipquery);

        if ($ipresult) {

            header("Location: /dashboard/administration/settings/ipBaning/");

        } else {

            header ("location: /error/genericSystemError");

        }

    }

    include($_SERVER["DOCUMENT_ROOT"].'/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

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
                                        <img src="/assets/img/systemIcons/settingsicon.png" alt="Settings Icon" style="background-color:#e3f8fa;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">Settings</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0px; padding-top:5px;">List IP Address</h4>
                                    </div>
                                </div>
                                <div>
                                    <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                    <a href="/dashboard/administration/settings/ipBaning/addIPAddress" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/settings/ipBaning/" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="fillable-section-holder" style="margin-top:-3% !important;">
                                <div class="fillable-header">
                                    <p class="fillable-text">IP Address Information</p>
                                </div>
                                <div class="fillable-body">
                                    <div class="caliweb-grid caliweb-two-grid" style="grid-row-gap:0px !important; grid-column-gap:100px !important; margin-bottom:4%;">
                                        <div class="form-left-side" style="width:80%;">
                                            
                                            <div class="form-control" style="margin-top:20px;">
                                                <label for="listType">Listing Type</label>
                                                <select class="form-input" name="listType">
                                                    <option>Please make a selection</option>
                                                    <option>Whitelist</option>
                                                    <option>Blacklist</option>
                                                </select>
                                            </div>

                                            <div class="form-control" style="margin-top:20px;">
                                                <label for="ipAddress">IP Address</label>
                                                <input class="form-input" name="ipAddress" type="text" placeholder="111.111.999.999" />
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

?>