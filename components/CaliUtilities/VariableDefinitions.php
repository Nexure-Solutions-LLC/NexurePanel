<?php

    namespace CaliUtilities;

    use mysqli;

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');

    class VariableDefinitions {

        public $panelName;
        public $panelVersionName;
        public $paneldomain;
        public $orgShortName;
        public $orglegalName;
        public $orglogosquare;
        public $orglogolight;
        public $orglogodark;
        public $dataTimestamp;
        public $datedataOutput;
        public $userId;
        public $apiKey;
        public $licenseKeyfromConfig;
        public $licenseKeyfromDB;

        public function variablesHeader($con) {

            try {

                // Connect to the database to initialize the panelinfo variable

                $panelresult = mysqli_query($con, "SELECT * FROM caliweb_panelconfig WHERE id = 1");
                $panelinfo = mysqli_fetch_array($panelresult);
                mysqli_free_result($panelresult);

                // Panel Configuration Definitions

                $this->panelName = $panelinfo['panelName'];
                $this->panelVersionName = $panelinfo['panelVersion'];
                $this->paneldomain = $panelinfo['panelDomain'];
                $this->orgShortName = $panelinfo['organizationShortName'];
                $this->orglegalName = $panelinfo['organization'];
                $this->orglogolight = $panelinfo['organizationLogoLight'];
                $this->orglogodark = $panelinfo['organizationLogoDark'];
                $this->orglogosquare = $panelinfo['organizationLogoSquare'];

                // Generic Variable Definitions

                $this->dataTimestamp = date("M d, Y \a\\t h:i A");
                $this->datedataOutput = "As of " . $this->dataTimestamp;
                $this->userId = $_ENV['IPCHECKAPIUSER'];
                $this->apiKey = $_ENV['IPCHECKAPIKEY'];

                // License Key Variable Definitions

                $this->licenseKeyfromConfig = $_ENV['LICENCE_KEY'];
                $this->licenseKeyfromDB = $panelinfo['panelKey'];

                // Payment Proccessing Variable Definitions
                // Perform payment processor check query

                $paymentproccessresult = mysqli_query($con, "SELECT * FROM caliweb_paymentconfig WHERE id = '1'");
                $paymentgateway = mysqli_fetch_array($paymentproccessresult);

                // Free payment processor check result set

                mysqli_free_result($paymentproccessresult);

                $this->apiKeysecret = $paymentgateway['secretKey'];
                $this->apiKeypublic = $paymentgateway['publicKey'];
                $this->paymentgatewaystatus = strtolower($paymentgateway['status']);
                $this->paymentProcessorName = $paymentgateway['processorName'];


            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
            
            }

        }

    }


?>