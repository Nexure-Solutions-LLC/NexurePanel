<?php

    // This is the Cali Web Design Backend Middleware.
    // This replaces the seperate middleware our former developer Mikey made.
    // It also was moved to Cali Web Design Modules from Cali Web Design Compoents.
    // THIS SOFTWARE IS OPENSOURCE UNDER COMMON DEVELOPMENT AND DISTRIBUTION LICENSE Version 1.0
    // (C) 2024 Nexure Solutions LLC.

    namespace NexureSolutions\Accounts;

    use mysqli;

    require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"] . "/modules/NexureSolutions/Utility/Backend/Schemas/index.php");

    class AccountHandler {
        
        public $id;
        public $password;
        public $accountNotes;
        public $emailVerfied;
        public $firstInteractionDate;
        public $lastInteractionDate;
        public $lang;
        public string $legalName;
        public string $mobileNumber;
        public string $email;

        public \accountStatus $accountStatus;
        public string $statusReason;
        public string $statusDate;

        public string $accountNumber;
        public string $accountDBPrefix;
        public bool $emailVerified;
        public string $registrationDate;
        public string $emailVerifiedDate;


        public ?string $profile_url;
        public ?string $stripe_id;
        public ?string $discord_id;
        public ?string $google_id;
        public \userRole $role;
        public \accessLevel $accessLevel;
        public ?string $ownerAuthorizedEmail;
        private mysqli $sql_connection;


        function __construct($con) {
            
            try {

                $this->sql_connection = $con;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        private function _sanitize(string $data): string {

            try {

                $con = $this->sql_connection;
                $data = stripslashes($data);
                $data = mysqli_real_escape_string($con, $data);
                
                return $data;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        private function _query_user_data(string $att_name, string $att_val): ?array {

            try {

                $con = $this->sql_connection;
                $query = "SELECT * FROM nexure_users WHERE ". $this->_sanitize($att_name) . " = '" . $this->_sanitize($att_val) . "';";
                $exec = mysqli_query($con, $query);
                $array = mysqli_fetch_array($exec);
                
                return $array;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }
        
        private function _lower_and_clear(string $data): string {

            return str_replace(" ", "", strtolower($data));

        }

        private function _join_and_trim(string $data): string {

            $pieces = preg_split('/(?=[A-Z])/', $data);
            $joined = implode(" ", $pieces);
            $joined = trim($joined);
            
            return $joined;

        }


        function transformStringToStatusColor(string $requestedString): ?\statusColor {

            $possible_status_color = array_combine(array_map(fn($item) => $this->_join_and_trim($item), array_column(\statusColor::cases(), 'name')), \statusColor::cases());
            
            return $possible_status_color[$requestedString] ?? null;

        }

        function transformAccountStatusToStatusColor(\accountStatus $requestedAccountStatus): ?\statusColor {

            $reqString = $this->fromAccountStatus($requestedAccountStatus);
            
            return $this->transformStringToStatusColor($reqString);

        }

        function fromAccessLevel(\accessLevel $requestedAccessLevel): ?string {

            $possible_access_levels = array_combine(array_map(fn($item) => $this->_join_and_trim($item), array_column(\accessLevel::cases(), 'name')), \accessLevel::cases());
            
            $idx = array_search($requestedAccessLevel, $possible_access_levels);
            
            if ($idx === false) {

                return null;

            }

            return $idx;
        }

        function fromUserRole(\userRole $requestedUserRole): ?string {

            $possible_user_roles = array_combine(array_map(fn($item) => $item, array_column(\userRole::cases(), 'name')), \userRole::cases());

            $idx = array_search($requestedUserRole, $possible_user_roles);
            
            if ($idx === false) {

                return null;

            }

            return $idx;
        }

        function fromAccountStatus(\accountStatus $requestedAccountStatus): ?string {

            $possible_account_status = array_combine(array_map(fn($item) => $this->_join_and_trim($item), array_column(\accountStatus::cases(), 'name')), \accountStatus::cases());
            
            $idx = array_search($requestedAccountStatus, $possible_account_status);
            
            if ($idx === false) {

                return null;
            }

            return $idx;
        }

        function toAccessLevel(string $requestedAccessLevel): ?\accessLevel {

            $possible_access_levels = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\accessLevel::cases(), 'name')), \accessLevel::cases());
            
            if (!isset($possible_access_levels[$this->_lower_and_clear($requestedAccessLevel)])) {

                return null;

            }

            return $possible_access_levels[$this->_lower_and_clear($requestedAccessLevel)];
        }

        function toRole(string $requestedUserRole): ?\userRole {

            $possible_user_roles = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\userRole::cases(), 'name')), \userRole::cases());
            
            if (!isset($possible_user_roles[$this->_lower_and_clear($requestedUserRole)])) {

                return null;

            }

            return $possible_user_roles[$this->_lower_and_clear($requestedUserRole)];

        }

        function toAccountStatus(string $requestedAccountStatus): ?\accountStatus {

            $possible_account_statuses = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\accountStatus::cases(), 'name')), \accountStatus::cases());
            
            if (!isset($possible_account_statuses[$this->_lower_and_clear($requestedAccountStatus)])) {
                
                return null;

            }

            return $possible_account_statuses[$this->_lower_and_clear($requestedAccountStatus)];
        }

        function changeEmail(string $email): bool {

            try {

                $con = $this->sql_connection;

                $query = "UPDATE `nexure_ownershipinformation` SET emailAddress = '" . $this->_sanitize($email) . "' WHERE emailAddress = '" . $this->_sanitize($this->email) . "';";
                
                $exec = mysqli_query($con, $query);

                $query = "UPDATE `nexure_users` SET email = '" . $this->_sanitize($email) . "' WHERE email = '" . $this->_sanitize($this->email) . "';";
                
                $exec = mysqli_query($con, $query);

                $success = $this->fetchByEmail($email);
                
                return $success;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }


        function changeAttr(string $att_name, string $att_val, bool $useStringSyntax = true): bool {

            try {

                // ALWAYS check if data has changed.

                $success = $this->fetchByEmail($this->email);

                if (!$success) {

                    return false;

                }

                // Check if `att_name` is an actual attribute.

                if (!isset($this->{$att_name})) {

                    return false;

                }

                // Send the SQL query

                $con = $this->sql_connection;

                if ($useStringSyntax) {

                    $query = "UPDATE `nexure_users` SET ".$this->_sanitize($att_name). " = '" . $this->_sanitize($att_val) . "' WHERE email = '" . $this->_sanitize($this->email) . "';";
                
                } else {
                    
                    $query = "UPDATE `nexure_users` SET ".$this->_sanitize($att_name). " = " . $this->_sanitize($att_val) . " WHERE email = '" . $this->_sanitize($this->email) . "';";
                
                }
                $exec = mysqli_query($con, $query);


                // Refresh to updated

                $success = $this->fetchByEmail($this->email);

                return $success;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }


        function multiChangeAttr(array $attributes): bool {

            try {

                if (count($attributes) === 0) {
                    return false;
                }

                // Fetch current data by email

                $success = $this->fetchByEmail($this->email);

                if (!$success) {

                    return false;

                }

                $query = "UPDATE `nexure_users` SET ";

                $setClauses = [];

                foreach ($attributes as $attribute) {

                    $att_name = $this->_sanitize($attribute["attName"]);
                    $att_value = $this->_sanitize($attribute["attValue"]);
                    $useStringSyntax = $attribute["useStringSyntax"] ?? true;

                    // Check if attribute exists in the object

                    if (!isset($this->{$att_name})) {

                        return false;

                    }

                    $setClauses[] = $att_name . " = " . ($useStringSyntax ? "'" : "") . $att_value . ($useStringSyntax ? "'" : "");
                }

                // Combine SET clauses and complete the query

                $query .= implode(', ', $setClauses) . ' WHERE email = "' . $this->_sanitize($this->email) . '";';

                // Execute the SQL query

                $con = $this->sql_connection;

                $exec = mysqli_query($con, $query);

                // Refresh and return success status

                $success = $this->fetchByEmail($this->email);

                return $success;

            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);
                
                return false;

            }

        }


        function fetchByEmail(string $cali_id): bool {

            try {

                $con = $this->sql_connection;

                $data_array = $this->_query_user_data("email", $this->_sanitize($cali_id));

                if (!$data_array) {

                    return false;

                }

                $special_attrs = array(
                    "profileIMG" => "profile_url",
                    "stripeID" => "stripe_id"
                );

                $enum_attrs = array(
                    "employeeAccessLevel",
                    "userrole",
                    "accountStatus"
                );

                $possible_roles = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\userRole::cases(), 'name')), \userRole::cases());
                $possible_access_levels = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\accessLevel::cases(), 'name')), \accessLevel::cases());
                $possible_account_statuses = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\accountStatus::cases(), 'name')), \accountStatus::cases());

                foreach ($data_array as $key => $value) {

                    if (array_key_exists($key, $special_attrs)) {

                        $this->{$special_attrs[$key]} = $value;

                    } elseif (array_search($key, $enum_attrs) !== false) {

                        if ($key == "userrole") {

                            $role_to_be_set = null;

                            if (!isset($possible_roles[$this->_lower_and_clear($value)])) {

                                $role_to_be_set = $possible_roles["customer"];

                            } else {

                                $role_to_be_set = $possible_roles[$this->_lower_and_clear($value)];

                            }

                            $this->role = $role_to_be_set;

                        } elseif ($key == "employeeAccessLevel") {

                            $accessLevelToBeSet = null;
                            
                            if (!isset($possible_access_levels[$this->_lower_and_clear($value)])) {

                                $accessLevelToBeSet = $possible_access_levels[$this->_lower_and_clear("Retail")];

                            } else {

                                $accessLevelToBeSet = $possible_access_levels[$this->_lower_and_clear($value)];

                            }

                            $this->accessLevel = $accessLevelToBeSet;

                        } elseif ($key == "accountStatus") {

                            $accountStatusToBeSet = null;

                            if (!isset($possible_account_statuses[$this->_lower_and_clear($value)])) {

                                $accountStatusToBeSet = $possible_account_statuses[$this->_lower_and_clear("Active")];

                            } else {
                                $accountStatusToBeSet = $possible_account_statuses[$this->_lower_and_clear($value)];

                            }

                            $this->accountStatus = $accountStatusToBeSet;

                        }

                    } else {

                        if ($key != "sql_connection" && !is_int($key)) {

                            $this->{$key} = $value;

                        }

                    }

                }

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

            return true;

        }


        function fetchByAccountNumber(string $accountNumber): bool {

            try {

                $con = $this->sql_connection;

                $data_array = $this->_query_user_data("accountnumber", $this->_sanitize($accountNumber));

                if (!$data_array) {

                    return false;

                }

                $special_attrs = array(
                    "profileIMG" => "profile_url",
                    "stripeID" => "stripe_id"
                );

                $enum_attrs = array(
                    "employeeAccessLevel",
                    "userrole",
                    "accountStatus"
                );

                $possible_roles = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\userRole::cases(), 'name')), \userRole::cases());

                $possible_access_levels = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\accessLevel::cases(), 'name')), \accessLevel::cases());
                
                $possible_account_statuses = array_combine(array_map(fn($item) => $this->_lower_and_clear($item), array_column(\accountStatus::cases(), 'name')), \accountStatus::cases());

                foreach ($data_array as $key => $value) {

                    if (array_key_exists($key, $special_attrs)) {

                        $this->{$special_attrs[$key]} = $value;

                    } elseif (array_search($key, $enum_attrs) !== false) {

                        if ($key == "userrole") {

                            $role_to_be_set = null;

                            if (!isset($possible_roles[$this->_lower_and_clear($value)])) {

                                $role_to_be_set = $possible_roles["customer"];

                            } else {

                                $role_to_be_set = $possible_roles[$this->_lower_and_clear($value)];

                            }

                            $this->role = $role_to_be_set;

                        } elseif ($key == "employeeAccessLevel") {

                            $accessLevelToBeSet = null;

                            if (!isset($possible_access_levels[$this->_lower_and_clear($value)])) {

                                $accessLevelToBeSet = $possible_access_levels[$this->_lower_and_clear("Retail")];

                            } else {

                                $accessLevelToBeSet = $possible_access_levels[$this->_lower_and_clear($value)];

                            }

                            $this->accessLevel = $accessLevelToBeSet;

                        } elseif ($key == "accountStatus") {

                            $accountStatusToBeSet = null;

                            if (!isset($possible_account_statuses[$this->_lower_and_clear($value)])) {

                                $accountStatusToBeSet = $possible_account_statuses[$this->_lower_and_clear("Active")];

                            } else {
                                $accountStatusToBeSet = $possible_account_statuses[$this->_lower_and_clear($value)];

                            }

                            $this->accountStatus = $accountStatusToBeSet;

                        }

                    } else {

                        if ($key != "sql_connection" && !is_int($key)) {

                            $this->{$key} = $value;

                        }

                    }

                }

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

            return true;

        }

    }

    namespace NexureSolutions\Utility;

    class StringHelper {

        public function lower_and_clear(string $data): string {

            return str_replace(" ", "", strtolower($data));

        }

        public function join_and_trim(string $data): string {

            $pieces = preg_split('/(?=[A-Z])/', $data);

            $joined = implode(" ", $pieces);

            $joined = trim($joined);

            return $joined;

        }

        public function sanitize(mysqli $con, string $data): string {

            $data = stripslashes($data);

            $data = mysqli_real_escape_string($con, $data);

            return $data;

        }

    }

    class TransformHelpers
    {

        protected StringHelper $helper;

        function __construct()
        {

            $this->helper = new StringHelper();
            
        }


        function transformStringToStatusColor(string $requestedString): ?\taskStatus {

            $possible_status_color = array_combine(array_map(fn($item) => $this->helper->join_and_trim($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
            return $possible_status_color[$requestedString] ?? null;

        }

        function transformPriorityToPriorityColor(\priorityLevel $requestedPriority): ?\taskStatus {

            $reqString = $this->fromPriorityLevel($requestedPriority);
            return $this->transformStringToStatusColor($reqString);

        }

        function fromPriorityLevel(\priorityLevel $requestedPriority): ?string {

            $possible_priorities = array_combine(array_map(fn($item) => $this->helper->join_and_trim($item), array_column(\priorityLevel::cases(), 'name')), \priorityLevel::cases());
            $idx = array_search($requestedPriority, $possible_priorities);

            if ($idx === false) {

                return null;

            }

            return $idx;

        }

        function fromTaskStatus(\taskStatus $requestedStatus): ?string {

            $possible_statuses = array_combine(array_map(fn($item) => $this->helper->join_and_trim($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());
            $idx = array_search($requestedStatus, $possible_statuses);

            if ($idx === false) {

                return null;

            }

            return $idx;
        }

        function toPriorityLevel(string $requestedPriority): ?\priorityLevel {

            $possible_priorities = array_combine(array_map(fn($item) => $this->helper->lower_and_clear($item), array_column(\priorityLevel::cases(), 'name')), \priorityLevel::cases());

            if (!isset($possible_priorities[$this->helper->lower_and_clear($requestedPriority)])) {

                return null;

            }

            return $possible_priorities[$this->helper->lower_and_clear($requestedPriority)];

        }

        function toTaskStatus(string $requestedStatus): ?\taskStatus {

            $possible_statuses = array_combine(array_map(fn($item) => $this->helper->lower_and_clear($item), array_column(\taskStatus::cases(), 'name')), \taskStatus::cases());

            if (!isset($possible_statuses[$this->helper->lower_and_clear($requestedStatus)])) {

                return null;

            }

            return $possible_statuses[$this->helper->lower_and_clear($requestedStatus)];

        }

    }

    class TaskView {

        public $taskname;
        public $taskdescription;
        public $taskpriority;
        public $taskstartdate;
        public $taskduedate;
        public $assigneduser;
        public $status;
        public $taskInfo;

        public function manageTask($con, $taskID)
        {

            // Prepare the SQL Statement to get all the user's info

            $this->taskInfo = $this->fetchSingleRow($con, 'nexure_tasks', "id = '".mysqli_real_escape_string($con, $taskID)."'");

            if (!$this->taskInfo) {

                header("location: /dashboard/administration/accounts");

                exit;
                
            }

            // Task Specific Data Storage and Variable Declaration

            $this->taskname = $this->taskInfo['taskName'];

            $this->taskdescription = $this->taskInfo['taskDescription'];

            $this->taskpriority = $this->taskInfo['taskPriority'];

            $this->taskstartdate = $this->taskInfo['taskStartDate'];

            $this->taskduedate = $this->taskInfo['taskDueDate'];

            $this->assigneduser = $this->taskInfo['assignedUser'];

            $this->status = $this->taskInfo['status'];

        }

         private function fetchSingleRow($con, $table, $condition) {

            $query = "SELECT * FROM $table WHERE $condition LIMIT 1";

            $result = mysqli_query($con, $query);

            if (!$result) {

                \Sentry\captureException("Query failed: " . mysqli_error($con));

                throw new Exception("Query failed: " . mysqli_error($con));

            }

            $row = mysqli_fetch_array($result);

            mysqli_free_result($result);

            return $row;

        }

    }

    namespace NexureSolutions\Generic;

    use NexureSolutions\Utility;

    class VariableDefinitions
    {
        
        public $panelName;
        public $panelVersionName;
        public $paneldomain;
        public $orgShortName;
        public $orglegalName;
        public $orglogosquare;
        public $orglogolight;
        public $orglogodark;
        public $paymentProcessorName;
        public $dataTimestamp;
        public $datedataOutput;
        public $userId;
        public $apiKey;
        public $licenseKeyfromConfig;
        public $licenseKeyfromDB;
        public $apiKeysecret;
        public $apiKeypublic;
        public $paymentgatewaystatus;
        public $paymentDescriptor;

        public $customerAccountInfo;
        public $legalname;
        public $customeremail;
        public $mobilenumber;
        public $customerStatus;
        public $customerStripeID;
        public $userrole;
        public $dbaccountnumber;
        public $statusreason;
        public $firstinteractiondateformattedfinal;
        public $lastinteractiondateformattedfinal;
        public $businessname;
        public $businessindustry;
        public $websitedomain;
        public $notesResults;

        public $regdate;
        public $regdateformatted;
        public $regdateformattedfinal;

        public $statusdate;
        public $statusdateformatted;
        public $statusdateformattedfinal;

        public $emailverifydate;
        public $emailverifydateformatted;
        public $emailverifydateformattedfinal;
        public $emailverifystatus;

        public $employeeAccountInfo;
        public $employeename;
        public $employeepaytype;
        public $employeeemail;
        public $employeepayrate;
        public $employeecurrentpay;
        public $employeeworkedhours;
        public $employeephonenumber;
        public $employeedepartment;
        public $employeehiredateformattedfinal;
        public $employeerehiredateformattedfinal;
        public $employeetermhiredateformattedfinal;


        public function variablesHeader($con)
        {

            try {

                // Initialize panel information

                $panelinfo = $this->fetchSingleRow($con, 'nexure_panelconfig', 1);
                $this->initializePanelConfig($panelinfo);

                // Set generic variable definitions

                $this->setGenericVariables();

                // Initialize license key

                $this->licenseKeyfromConfig = $_ENV['LICENCE_KEY'];
                $this->licenseKeyfromDB = $panelinfo['panelKey'];

                // Initialize payment processor information

                $paymentgateway = $this->fetchSingleRow($con, 'nexure_paymentconfig', 1);
                $this->initializePaymentProcessor($paymentgateway);
                
            } catch (\Throwable $exception) {

                \Sentry\captureException($exception);

            }

        }

        private function fetchSingleRow($con, $table, $condition) {

            $query = "SELECT * FROM $table WHERE $condition LIMIT 1";

            $result = mysqli_query($con, $query);

            if (!$result) {

                \Sentry\captureException("Query failed: " . mysqli_error($con));

                throw new Exception("Query failed: " . mysqli_error($con));

            }

            $row = mysqli_fetch_array($result);

            mysqli_free_result($result);

            return $row;

        }

        private function initializePanelConfig($panelinfo)
        {

            $this->panelName = $panelinfo['panelName'];

            $this->panelVersionName = $panelinfo['panelVersion'];

            $this->paneldomain = $panelinfo['panelDomain'];

            $this->orgShortName = $panelinfo['organizationShortName'];

            $this->orglegalName = $panelinfo['organization'];

            $this->orglogolight = $panelinfo['organizationLogoLight'];

            $this->orglogodark = $panelinfo['organizationLogoDark'];

            $this->orglogosquare = $panelinfo['organizationLogoSquare'];

            $this->paymentDescriptor = $panelinfo['paymentDescriptor'];

        }

        private function setGenericVariables()
        {

            $this->dataTimestamp = date("M d, Y \a\\t h:i A");

            $this->datedataOutput = "As of " . $this->dataTimestamp;

            $this->userId = $_ENV['IPCHECKAPIUSER'];

            $this->apiKey = $_ENV['IPCHECKAPIKEY'];

        }

        private function initializePaymentProcessor($paymentgateway)
        {

            $this->apiKeysecret = $paymentgateway['secretKey'];

            $this->apiKeypublic = $paymentgateway['publicKey'];

            $this->paymentgatewaystatus = strtolower($paymentgateway['status']);

            $this->paymentProcessorName = $paymentgateway['processorName'];

        }

        public function manageAccount($con, $accountnumber)
        {

            // Prepare the SQL Statement to get all the user's info

            $this->customerAccountInfo = $this->fetchSingleRow($con, 'nexure_users', "accountNumber = '".mysqli_real_escape_string($con, $accountnumber)."'");

            if (!$this->customerAccountInfo) {

                header("location: /dashboard/administration/accounts");

                exit;
                
            }

            // Account Specific Data Storage and Variable Declaration

            $this->legalname = $this->customerAccountInfo['legalName'];

            $this->customeremail = $this->customerAccountInfo['email'];

            $this->mobilenumber = $this->customerAccountInfo['mobileNumber'];

            $this->customerStatus = $this->customerAccountInfo['accountStatus'];

            $this->customerStripeID = $this->customerAccountInfo['stripeID'];

            $this->userrole = $this->customerAccountInfo['userrole'];

            $this->dbaccountnumber = $this->customerAccountInfo['accountNumber'];

            $this->statusreason = $this->customerAccountInfo['statusReason'];

            // Account Notes Section

            $this->notesResults = mysqli_query($con, "SELECT * FROM nexure_accountnotes WHERE accountNumber='".mysqli_real_escape_string($con, $accountnumber)."' ORDER BY id DESC");

            // Get the Interaction Dates Information for the Account Header

            $newInteractionDate = date('Y-m-d H:i:s');

            mysqli_query($con, "UPDATE nexure_users SET lastInteractionDate='$newInteractionDate' WHERE accountNumber='".mysqli_real_escape_string($con, $accountnumber)."'");

            $firstinteractiondate = $this->customerAccountInfo['firstInteractionDate'] ?? null;

            $firstinteractiondateformatted = new \DateTime($firstinteractiondate);

            $this->firstinteractiondateformattedfinal = $firstinteractiondateformatted->format('F j, Y g:i A');

            $lastinteractiondate = mysqli_fetch_assoc(mysqli_query($con, "SELECT lastInteractionDate FROM nexure_users WHERE accountNumber='".mysqli_real_escape_string($con, $accountnumber)."'"))['lastInteractionDate'] ?? null;

            $lastinteractiondateformatted = new \DateTime($lastinteractiondate);

            $this->lastinteractiondateformattedfinal = $lastinteractiondateformatted->format('F j, Y g:i A');

            // Business Specific Data Storage and Variable Declaration for the Customer's Business

            $businessAccountInfo = $this->fetchSingleRow($con, 'nexure_businesses', "email = '".mysqli_real_escape_string($con, $this->customeremail)."'");

            $this->businessname = $businessAccountInfo['businessName'] ?? $this->legalname;

            $this->businessindustry = $businessAccountInfo['businessIndustry'] ?? "Not Assigned";

            $this->websitedomain = $this->getWebsiteDomain($con, $this->customeremail, $businessAccountInfo);

            // Fetch website info

            $websiteAccountInfo = $this->fetchSingleRow($con, 'nexure_websites', "email = '".mysqli_real_escape_string($con, $this->customeremail)."'");

            $this->websitedomain = $websiteAccountInfo['domainName'] ?? 'Not Assigned';

            // Account Dates and Date Formatting

            $regdate = $this->customerAccountInfo['registrationDate'];

            $regdateformatted = new \DateTime($regdate);

            $this->regdateformattedfinal = $regdateformatted->format('F j, Y g:i A');

            $statusdate = $this->customerAccountInfo['statusDate'];

            $statusdateformatted = new \DateTime($statusdate);

            $this->statusdateformattedfinal = $statusdateformatted->format('F j, Y g:i A');

            $emailverifydate = $this->customerAccountInfo['emailVerifiedDate'];

            $emailverifydateformatted = new \DateTime($emailverifydate);

            $this->emailverifydateformattedfinal = $emailverifydateformatted->format('F j, Y g:i A');

            // Email Verification Status

            $this->emailverifystatus = ucfirst($this->customerAccountInfo['emailVerfied']);

        }

        public function manageEmployee($con, $employeeIDNumber) {

            // Same as the manage account function but for staff and the payroll module.

            // Prepare the SQL Statement to get all the employee's info

            $this->employeeAccountInfo = $this->fetchSingleRow($con, 'nexure_payroll', "employeeIDNumber = '" . mysqli_real_escape_string($con, $employeeIDNumber) . "'");

            if (!$this->employeeAccountInfo) {

                header("location: /modules/NexureSolutions/Payroll/ceoSuite/");

                exit;
            }

            // Employee Specific Data Storage and Variable Declaration

            $this->employeename = $this->employeeAccountInfo['employeeName'];

            $this->employeepaytype = $this->employeeAccountInfo['employeePayType'];

            $this->employeeemail = $this->employeeAccountInfo['employeeEmail'];

            $this->employeepayrate = $this->employeeAccountInfo['employeePayRate'];

            $this->employeecurrentpay = $this->employeeAccountInfo['employeeActualPay'];

            $this->employeeworkedhours = $this->employeeAccountInfo['employeeWorkedHours'];

            $this->employeephonenumber = $this->employeeAccountInfo['employeePhoneNumber'];

            $this->employeedepartment = $this->employeeAccountInfo['employeeDepartment'];

            $employeehiredate = $this->employeeAccountInfo['employeeHireDate'];

            $employeehiredateformatted = new \DateTime($employeehiredate);

            $this->employeehiredateformattedfinal = $employeehiredateformatted->format('F j, Y g:i A');

            $employeerehiredate = $this->employeeAccountInfo['employeeRehireDate'];

            $employeerehiredateformatted = new \DateTime($employeerehiredate);

            $this->employeerehiredateformattedfinal = $employeerehiredateformatted->format('F j, Y g:i A');

            $employeetermdate = $this->employeeAccountInfo['employeeTerminationDate'];

            $employeetermdateformatted = new \DateTime($employeetermdate);

            $this->employeetermhiredateformattedfinal = $employeetermdateformatted->format('F j, Y g:i A');

        }

        public function checkForDuplicateAccount($accountNumber, $ownerName, $email, $businessName, $websiteDomain, $industry, $con) {

            $query = "
                SELECT 
                    u.accountNumber AS userAccountNumber,
                    o.legalName AS ownershipLegalName,
                    o.emailAddress AS ownershipEmail,
                    b.businessName AS businessName,
                    w.domainName AS websiteDomain,
                    b.businessIndustry AS businessIndustry
                FROM nexure_users u
                LEFT JOIN nexure_ownershipinformation o ON u.email = o.emailAddress
                LEFT JOIN nexure_businesses b ON u.email = b.email
                LEFT JOIN nexure_websites w ON u.email = w.email
                WHERE u.accountNumber = ? 
                OR (o.legalName = ? AND u.email = ? AND b.businessName = ?)
                OR (w.domainName = ? AND b.businessIndustry = ? AND b.businessName = ?)
            ";
            
            $stmt = $con->prepare($query);

            if ($stmt === false) {

                die('Prepare failed: ' . htmlspecialchars($con->error));

            }

            $stmt->bind_param("sssssss", $accountNumber, $ownerName, $email, $businessName, $websiteDomain, $industry, $businessName);
            
            if (!$stmt->execute()) {

                die('Execute failed: ' . htmlspecialchars($stmt->error));

            }
            
            $result = $stmt->get_result();
            
            if ($result->num_rows >= 2) {

                $duplicates = [];

                while ($row = $result->fetch_assoc()) {

                    $duplicates[] = $row;

                }

                $HTMLCONTENT = "
                
                    <div class='caliweb-card dashboard-card' style='margin-bottom:10px;'>
                        <div class='display-flex align-center'>
                            <div class='no-padding margin-10px-right icon-size-formatted'>
                                <img src='/assets/img/systemIcons/duplicateaccounticon.png' alt='Client Logo and/or Business Logo' style='background-color:#fff9dd;' class='client-business-andor-profile-logo' />
                            </div>
                            <p class='no-padding' style='font-weight:800;'>We found a potential duplicate of this account.</p>
                        </div>
                    </div>

                ";

                return [

                    "status" => $HTMLCONTENT,

                ];

            } else {

                $HTMLCONTENT = "
                
                    <div class='caliweb-card dashboard-card' style='margin-bottom:10px;'>
                        <div class='display-flex align-center'>
                            <div class='no-padding margin-10px-right icon-size-formatted'>
                                <img src='/assets/img/systemIcons/duplicateaccounticon.png' alt='Client Logo and/or Business Logo' style='background-color:#fff9dd;' class='client-business-andor-profile-logo' />
                            </div>
                            <p class='no-padding' style='font-weight:800; font-family: Mona Sans, sans-serif;'>We found no potential duplicates of this account.</p>
                        </div>
                    </div>

                ";

                return [

                    "status" => $HTMLCONTENT,

                ];

            }

        }

        private function getWebsiteDomain($con, $customeremail, $businessAccountInfo)
        {

            if (!$businessAccountInfo) {

                return "Not Assigned";

            }

            $websiteInfo = $this->fetchSingleRow($con, 'nexure_websites', "email = '".mysqli_real_escape_string($con, $customeremail)."'");

            return $websiteInfo['domainName'] ?? "Not Assigned";

        }

        public function getActiveClientModulesOptions($con) {
            $options = '';

            $query = "SELECT * FROM nexure_modules";

            $result = mysqli_query($con, $query);

            if ($result) {

                while ($row = mysqli_fetch_array($result)) {

                    $moduleCatagoryCheckStatus = strtolower($row['moduleStatus']);
                    
                    $moduleCatagoryCheckName = htmlspecialchars($row['moduleFriendlyName']);

                    $moduleCatagoryCheckPosType = strtolower($row['modulePositionType']);

                    $moduleCatagoryCheckDependsOn = strtolower($row['moduleDependsOn']);

                    if ($moduleCatagoryCheckStatus === "active" && 
                        $moduleCatagoryCheckPosType === "client function" && 
                        empty($moduleCatagoryCheckDependsOn)) {

                        $options .= '<option>' . $moduleCatagoryCheckName . '</option>';
                    }

                }

                mysqli_free_result($result);
            }

            return $options;

        }

        public function getTeamsOptions($con) {

            $query = "SELECT * FROM `nexure_available_teams`";

            $result = mysqli_query($con, $query);

            if (!$result) {

                header("location: /error/genericSystemError");
                exit;

            }

            // Start building the options HTML
            $options = '';

            while ($row = mysqli_fetch_assoc($result)) {

                $teamName = $row['teamName'];
                $teamAlias = $row['teamAlias'];
                $teamType = $row['teamType'];

                $formattedOption = sprintf(
                    '%s - %s - %s',
                    htmlspecialchars($teamName),
                    htmlspecialchars($teamAlias),
                    htmlspecialchars($teamType)
                );

                $options .= '<option value="' . htmlspecialchars($formattedOption) . '">' . $formattedOption . '</option>';

            }

            mysqli_free_result($result);

            return $options;

        }

        public function calculateDueDate($accountNumber) {

            global $con;
            
            $query = "SELECT serviceStartDate, serviceType FROM nexure_services WHERE accountNumber = ?";

            $stmt = $con->prepare($query);

            $stmt->bind_param("s", $accountNumber);

            $stmt->execute();

            $stmt->bind_result($serviceStartDate, $serviceType);

            $stmt->fetch();

            $stmt->close();
            
            if (!$serviceStartDate || !$serviceType) {

                return "——";

            }

            return "——";

        }

    }

    class GenericInheritable
    {

        protected mysqli $sql_connection;
        protected string $collectionToQuery;
        protected string $primaryIdentifier;
        protected GenericManager $manager;
        protected NexureSolutions\Utility\StringHelper $helper;

        function __construct(mysqli $con, GenericManager $manager) {

            try {

                $this->sql_connection = $con;
                $this->collectionToQuery = $manager->collectionToQuery;
                $this->primaryIdentifier = $manager->queryingIdentifier;
                $this->primaryIdentifierIsString = $manager->queryingIdentifierIsString;
                $this->manager = $manager;
                $this->helper = new NexureSolutions\Utility\StringHelper();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function _query_generic_data_by_primary(string $att_val): ?array {

            try {

                $con = $this->sql_connection;
                $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $this->primaryIdentifier) . " = ". ($this->primaryIdentifierIsString ? "'" : "") ."" . $this->helper->sanitize($con, $att_val) . "". ($this->primaryIdentifierIsString ? "'" : "") .";";
                return $con->query($query)->fetch_array();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function _query_generic_data_by_specified_attribute(string $att_name, string $att_val): ?array {

            try {

                $con = $this->sql_connection;
                $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $att_name) . " = '" . $this->helper->sanitize($con, $att_val) . "';";
                return $con->query($query)->fetch_array();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        public function fetchByPrimaryIdentifier(string $identifierValue) {

            try {

                $schema = $this->manager->schema;
                $variable_attrs = array_keys($schema);
                $attrs_to_types = array_combine($variable_attrs, array_column(array_values($schema), 0));
                $attrs_to_defaults = array_combine($variable_attrs, array_column(array_values($schema), 1));

                $data_array = $this->_query_task_data($this->primaryIdentifier, (string)$identifierValue);

                if (!$data_array) {

                    return false;

                }

                $key_possibilities = array();

                foreach ($attrs_to_types as $attr => $type) {

                    $key_possibilities[$attr] = array_combine(array_map(fn($item) => $this->helper->lower_and_clear($item), array_column($type::cases(), 'name')), $type::cases());
                
                }


                foreach ($data_array as $key => $value) {

                    if (in_array($key, $variable_attrs)) {

                        $possibles = $key_possibilities[$key];
                        $this->{$key} = isset($possibles[$this->helper->lower_and_clear($value)]) ? $possibles[$this->helper->lower_and_clear($value)] : $possibles[$this->helper->lower_and_clear($attrs_to_defaults[$key])];
                    
                    } else {

                        if ($key != "sql_connection" && !is_int($key)) {

                            $this->{$key} = $value;
                            
                        }

                    }

                }

                return true;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function isSetup(): bool {

            try {

                return isset($this->{$this->primaryIdentifier});

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        public function Refresh(): bool {

            try {

                if (!$this->isSetup()) {
                    
                    return false;

                }

                return $this->fetchByPrimaryIdentifier($this->{$this->primaryIdentifier});

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        public function UpdateAttr(string $att_name, $att_val, bool $rawDataisString): bool {

            try {

                if (!$this->isSetup()) {

                    return false;

                }

                // always refresh data before updating

                $isRefreshed = $this->Refresh();

                if (!$isRefreshed) {

                    return false;

                }

                if (gettype($att_val) == "object") {

                    $att_val = $this->helper->join_and_trim($att_val->name);

                }

                $query = "UPDATE `$this->collectionToQuery` SET $this->helper->sanitize($this->sql_connection, $att_name) = " . ($rawDataisString ? "'" : "") . $this->helper->sanitize($this->sql_connection, $att_val) . ($rawDataisString ? "'" : "") . " WHERE $this->helper->sanitize($this->sql_connection, $this->primaryIdentifier) = " . ($this->primaryIdentifierIsString ? "'" : "") . $this->helper->sanitize($this->sql_connection, $this->{$this->primaryIdentifier}) . ($this->primaryIdentifierIsString ? "'" : "") . ";";
                $this->sql_connection->query($query);
                return $this->Refresh();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        public function MultiUpdateAttr(array $massDataArray): bool {

            try {

                // Example data structure:
                // array( [0] => array(attName, attVal, isString), ... )

                if (!$this->isSetup()) {

                    return false;

                }

                // always refresh data before updating

                $isRefreshed = $this->Refresh();

                if (!$isRefreshed) {

                    return false;

                }

                $baseQuery = "UPDATE `$this->collectionToQuery` SET ";

                if (count($massDataArray) == 0) {

                    // this command can not operate when no attributes are being change
                    // this is to prevent an invalid sql command from being executed
                    // on the server

                    return false;
                }


                foreach ($massDataArray as $index => $data_array) {
                    $attName = $data_array[0];
                    $attValue = $data_array[1];
                    $isString = $data_array[2];

                    $baseQuery = $baseQuery . $this->helper->sanitize($this->sql_connection, $attName) . " = " . ($isString ? "'" : "") . $this->helper->sanitize($this->sql_connection, $attValue) . ($isString ? "'" : "") . ($index == (count($massDataArray) - 1) ? ", " : " ");
                
                };

                // $baseQuery = $baseQuery . "WHERE " . $this->helper->sanitize()

                return true;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }
        
    }

    use NexureSolutions\Utility\StringHelper;
    use Hoa\File\Generic;

    class GenericManager
    {
        
        // Management of Generic subclasses.
        // Management classes will inherit from this class.
        // (CaliTasks, CaliCases, CaliLeads, CaliCampaigns, CaliEmployees)

        protected mysqli $sql_connection;
        public string $collectionToQuery;
        public string $queryingIdentifier;
        protected array $generics;
        public StringHelper $helper;
        public array $schema;
        protected $InheritableSubclass = GenericInheritable::class;
        public bool $queryingIdentifierIsString = true;

        function __construct(mysqli $sql_connection, array $schema) {

            try {

                $this->schema = $schema;
                $this->sql_connection = $sql_connection;
                $this->helper = new StringHelper();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function _setQueryingIdentifier(string $queryIdentifier) {

            try {

                $this->queryingIdentifier = $queryIdentifier;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function _setCollectionToQuery(string $collection) {

            try {

                $this->collectionToQuery = $collection;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function _setQueryingIdentifierType(bool $isString) {

            try {

                $this->queryingIdentifierIsString = $isString;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }



        private function isSetup(): bool {

            try {

                return (isset($this->collectionToQuery) && isset($this->queryingIdentifier));

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function _query_main_identifier(string $att_val): ?array {

            try {

                if (!$this->isSetup()) {

                    return null;

                }

                $con = $this->sql_connection;
                $query = "SELECT * FROM `$this->collectionToQuery` WHERE " . $this->helper->sanitize($con, $this->queryingIdentifier) . " = ". ($this->queryingIdentifierIsString ? "'" : "") ."" . $this->helper->sanitize($con, $att_val) . "". ($this->queryingIdentifierIsString ? "'" : "") .";";
                return $con->query($query)->fetch_array();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function _queryAllData(): ?array {

            try {

                $con = $this->sql_connection;
                $query = "SELECT * FROM `$this->collectionToQuery`";
                $exec = $con->query($query);
                return $exec->fetch_all();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function fetchAllGenerics(): bool {

            try {

                if (!$this->isSetup()) {

                    // GenericManager needs to be setup to function
                    // correctly.

                    return false;

                }

                if (count($this->generics) != 0) {

                    // Fetch all generics should not be used in a case that isn't
                    // of initial fetching.

                    return false;

                }

                $all_data = $this->_queryAllData();

                foreach ($all_data as $_ => $generic) {

                    $GenericItem = new $this->InheritableSubclass(

                        $this->sql_connection, $this

                    );

                    $GenericItem->fetchByPrimaryIdentifier($generic[$this->queryingIdentifier]);

                    $this->generics[$generic[$this->queryingIdentifier]] = $GenericItem;

                }

                return true;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        protected function getOneGenericByPrimaryIdentifier(string $att_val) {

            try {
            
                return $this->generics[$att_val] ?? null;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

    }

    namespace NexureSolutions\Tasks;

    use NexureSolutions\Generic\GenericInheritable;
    use NexureSolutions\Generic\NexureSolutions\Utility\StringHelper;
    use NexureSolutions\Utility\TransformHelpers;
    

    class Task extends GenericInheritable
    {
        public int $id;
        public string $taskName;

        public TransformHelpers $transforms;
        public StringHelper $helper;
        public string $taskDescription;
        public \taskStatus $status;
        public \priorityLevel $taskPriority;
        public string $taskDueDate;
        public string $taskStartDate;
        public string $assignedUser;

        function __construct($con, $manager)
        {
            parent::__construct($con, $manager);

            // primaryIdentifier may not be used at this time
            // because I don't think it supports non-string keys
            // however when it is added it should become more mainstream for
            // fetching-by-primary-identifier

        }


        function updateTask(int $taskId, array $taskData): bool {

            try {

                $con = $this->sql_connection;

                $setString = '';

                foreach ($taskData as $key => $value) {

                    $setString .= $this->helper->sanitize($con, $key) . " = '" . $this->helper->sanitize($con, $value) . "', ";
                }

                $setString = rtrim($setString, ', ');

                $query = "UPDATE `nexure_tasks` SET " . $setString . " WHERE id = " . $this->helper->sanitize($con, (string)$taskId) . ";";

                $exec = mysqli_query($con, $query);

                return (bool) $exec;

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
            
            }

        }

        function refresh(): bool {

            try {

                if (!isset($this->id)) {

                    return false;

                }

                $this->fetchTaskById($this->id);
                return true;

            } catch (\Throwable $exception) {
            
                \Sentry\captureException($exception);
            
            }
            
        }

    }

    use NexureSolutions\Generic\GenericManager;

    class TaskManager extends GenericManager {

        // Task Manager allows for routine administration of CRUD
        // operations on tasks, as well as keeping an internal list
        // of all the tasks that are in the database.
        // This is so that the code can get all of the task objects
        // as well as perform actions that effect more than one task.

        function __construct(mysqli $sql_connection) {

            try {

                parent::__construct(
                    $sql_connection,
                    array(
                        "status" => array(0 => \taskStatus::class, 1 => "Pending"),
                        "taskPriority" => array(0 => \priorityLevel::class, 1 => "Normal")
                    )
                );

                $this->_setQueryingIdentifier("id");
                $this->queryingIdentifierIsString = false;
                $this->InheritableSubclass = Task::class;
                $this->_setCollectionToQuery("nexure_tasks");

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        private function _sanitize(string $data): string {

            try {

                $con = $this->sql_connection;
                $data = stripslashes($data);
                $data = mysqli_real_escape_string($con, $data);
                return $data;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        private function _idQuery(int $task_id): ?array {

            try {

                $query = "SELECT * FROM `nexure_tasks` WHERE id = $this->id;";
                $con = $this->sql_connection;
                $exec = $con->query($query);
                return $exec->fetch_array() ?? null;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        private function _allQuery(): ?array {

            try {

                $con = $this->sql_connection;
                $query = "SELECT * FROM `nexure_tasks`";
                $exec = $con->query($query);
                return $exec->fetch_all();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        public function hasBeenFetched(): bool
        {

            try {

                return $this->isFetched;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        function getAllTasks(): array
        {

            try {

                $this->fetchAllGenerics();

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

            
        }

        function getTasksBySpecifiedAttributes(array $attributes): array
        {

            try {

                $tasks = $this->getAllTasks();
                $exempt_tasks = array();

                foreach ($attributes as $key => $value) {

                    foreach ($tasks as $index => $task) {

                        if (!isset($task->{$key})) {

                            continue;

                        }

                        if ($task->{$key} != $value) {

                            if (in_array($task, $exempt_tasks)) {

                                $exempt_tasks[] = $task;

                            }

                        }

                    }

                }

                

                $final_array = array();

                foreach ($tasks as $i => $t) {

                    if (!in_array($t, $exempt_tasks)) {

                        $final_array[$t->id] = $t;

                    }

                }

                return $final_array;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }

        function upsertInternalTaskById(int $task_id): Task {

            try {

                $con = $this->sql_connection;
                $tasks = $this->tasks;

                if (array_key_exists($task_id, $tasks)) {

                    return $tasks[$task_id];

                }

                $task = new Task($con);
                $task->fetchTaskById($task_id);
                $tasks[$task->id] = $task;

                return $task;

            } catch (\Throwable $exception) {
                
                \Sentry\captureException($exception);
            
            }

        }
        
    }

    namespace NexureSolutions\MarketingCloud;

    class CampaignsSystem {

        protected $con;

        public function __construct($con) {

            $this->con = $con;

        }

        public function loadAdPartners() {

            $query = "SELECT * FROM nexure_modules WHERE moduleName = 'Cali Marketing Cloud'";

            $result = mysqli_query($this->con, $query);

            if (mysqli_num_rows($result) > 0) {

                $adPartners = ['Meta Marketing Services', 'Google Marketing Services', 'X Marketing Services', 'TikTok Marketing Services'];

                $partnersLoaded = [];

                foreach ($adPartners as $partner) {

                    $partnerQuery = "SELECT * FROM nexure_modules WHERE moduleName = ? AND moduleDependsOn = 'Cali Marketing Cloud'";

                    $stmt = mysqli_prepare($this->con, $partnerQuery);

                    mysqli_stmt_bind_param($stmt, "s", $partner);

                    mysqli_stmt_execute($stmt);

                    $partnerResult = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($partnerResult) > 0) {

                        $partnersLoaded[] = $partner;
                    }

                    mysqli_stmt_close($stmt);
                }

                return $partnersLoaded;

            } else {

                return [];
                
            }

        }

    }

    namespace NexureSolutions\Calendar;

    class CalendarComponents
    {

        public $eventsresponse;
        public $accountnumber;

        public function eventsRetrive($con, $accountnumber) {

            $this->eventsresponse = mysqli_query($con, "SELECT eventName, eventDescription, eventTimeDate FROM nexure_events WHERE accountNumber = '$accountnumber' ORDER BY eventTimeDate DESC");

        }

    }

?>