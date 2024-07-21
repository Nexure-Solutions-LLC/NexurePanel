<?php

namespace CaliAccounts;

require($_SERVER["DOCUMENT_ROOT"] . '/configuration/index.php');
require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliAccounts/accountStatus.php');
require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliAccounts/accessLevel.php');
require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliAccounts/userrole.php');
require($_SERVER["DOCUMENT_ROOT"] . '/components/CaliAccounts/statusColor.php');

class Account
{
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
    private $sql_connection;


    function __construct($con) {

        $this->sql_connection = $con;

    }

    private function _sanitize(string $data): string {

        $con = $this->sql_connection;
        $data = stripslashes($data);
        $data = mysqli_real_escape_string($con, $data);
        
        return $data;

    }

    private function _query_user_data(string $att_name, string $att_val): ?array {

        $con = $this->sql_connection;
        $query = "SELECT * FROM caliweb_users WHERE ". $this->_sanitize($att_name) . " = '" . $this->_sanitize($att_val) . "';";
        $exec = mysqli_query($con, $query);
        $array = mysqli_fetch_array($exec);
        
        return $array;

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

        $possible_user_roles = array_combine(array_map(fn($item) => $item, array_column(\accessLevel::cases(), 'name')), \accessLevel::cases());
        
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

        $con = $this->sql_connection;

        $query = "UPDATE `caliweb_ownershipinformation` SET emailAddress = '" . $this->_sanitize($email) . "' WHERE emailAddress = '" . $this->_sanitize($this->email) . "';";
        
        $exec = mysqli_query($con, $query);

        $query = "UPDATE `caliweb_users` SET email = '" . $this->_sanitize($email) . "' WHERE email = '" . $this->_sanitize($this->email) . "';";
        
        $exec = mysqli_query($con, $query);

        $success = $this->fetchByEmail($email);
        
        return $success;
    }


    function changeAttr(string $att_name, string $att_val, bool $useStringSyntax = true): bool {

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

            $query = "UPDATE `caliweb_users` SET ".$this->_sanitize($att_name). " = '" . $this->_sanitize($att_val) . "' WHERE email = '" . $this->_sanitize($this->email) . "';";
        
        } else {
            
            $query = "UPDATE `caliweb_users` SET ".$this->_sanitize($att_name). " = " . $this->_sanitize($att_val) . " WHERE email = '" . $this->_sanitize($this->email) . "';";
        
        }
        $exec = mysqli_query($con, $query);


        // Refresh to updated

        $success = $this->fetchByEmail($this->email);

        return $success;

    }

    function fetchByEmail(string $cali_id): bool {

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

        return true;
    }

}

?>