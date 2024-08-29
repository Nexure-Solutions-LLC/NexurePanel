<?php

    // Initialize page variables

    unset($_SESSION['pagetitle']);
    $_SESSION['pagetitle'] = "File Management";
    $pagetitle = "File Management";
    $pagesubtitle = "File Upload";
    $pagetype = "Administration";

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');

    // Retrieve account number from query parameters and check the account number and if its present

    $accountnumber = $_GET['account_number'] ?? '';

    if (empty($accountnumber)) {

        header("Location: /dashboard/administration/accounts");
        exit;

    }

    // Fetch customer account information

    $accountnumberEscaped = mysqli_real_escape_string($con, $accountnumber);

    $query = "SELECT * FROM caliweb_users WHERE accountNumber = '$accountnumberEscaped'";
    $result = mysqli_query($con, $query);
    
    $customerAccountInfo = mysqli_fetch_array($result);
    mysqli_free_result($result);

    // Check the customer information value to ensure its not empty or null

    if (!$customerAccountInfo) {

        echo '<script type="text/javascript">window.location = "/error/genericSystemError"</script>';
        exit;

    }

    // Handle form submission

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Check the payment proccessor to see if its stripe. When form is submitted

        if ($variableDefinitionX->paymentProcessorName === "Stripe") {

            require($_SERVER["DOCUMENT_ROOT"] . '/modules/paymentModule/stripe/internalPayments/index.php');

        }

    }

    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Headers/index.php');

    echo '<title>' . htmlspecialchars($pagetitle) . ' | ' . htmlspecialchars($pagesubtitle) . '</title>';

?>

    <section class="section first-dashboard-area-cards">
        <div class="container width-98">
            <div class="caliweb-one-grid special-caliweb-spacing">
                <div class="caliweb-card dashboard-card">
                    <form action="/dashboard/administration/accounts/fileUpload/uploadLogic/?account_number=<?php echo $accountnumber;?>" method="POST" enctype="multipart/form-data">
                        <div class="card-header">
                            <div class="display-flex align-center" style="justify-content: space-between;">
                                <div class="display-flex align-center">
                                    <div class="no-padding margin-10px-right icon-size-formatted">
                                        <img src="/assets/img/systemIcons/fileuploadicon.png" alt="Create Services Icon" style="background-color:#ffe6e2;" class="client-business-andor-profile-logo" />
                                    </div>
                                    <div>
                                        <p class="no-padding font-14px">File Uploads</p>
                                        <h4 class="text-bold font-size-20 no-padding" style="padding-bottom:0; padding-top:5px;">Uploading a File</h4>
                                    </div>
                                </div>
                                <div>
                                    <button class="caliweb-button primary no-margin margin-10px-right" style="padding:6px 24px;" type="submit" name="submit">Save</button>
                                    <a href="/dashboard/administration/accounts/fileUpload/?account_number=<?php echo urlencode($accountnumber); ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Clear Form</a>
                                    <a href="/dashboard/administration/accounts/manageAccount/?account_number=<?php echo urlencode($accountnumber); ?>" class="caliweb-button secondary no-margin margin-10px-right" style="padding:6px 24px;">Exit</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="caliweb-grid caliweb-two-grid" style="margin-top:-2%; grid-row-gap:0;">
                                <div class="form-control">
                                    <label for="file">Select file to upload:</label><br>
                                    <input class="form-input" type="file" id="file" name="file" style="margin-top:20px;" required><br><br>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

                        

<?php

    include($_SERVER["DOCUMENT_ROOT"] . "/modules/paymentModule/stripe/internalPayments/clientside.php");
    include($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/Dashboard/Footers/index.php');

?>

