<?php

    namespace CaliWebDesign\Search;

    class SearchSystem
    {
        
        private $con;

        public function __construct($dbConnection)
        {
            $this->con = $dbConnection;
        }

        public function search($term)
        {

            $searchTerm = '%' . $term . '%';

            $searchResults = [];

            // Search in Users (Customers)

            $searchResults['customers'] = $this->searchUsers($searchTerm);

            // Search in Cases

            $searchResults['cases'] = $this->searchCases($searchTerm);

            // Search in Tasks

            $searchResults['tasks'] = $this->searchTasks($searchTerm);

            return $searchResults;

        }

        private function searchUsers($searchTerm)
        {

            $query = "SELECT legalName, email, accountNumber FROM caliweb_users WHERE (legalName LIKE ? OR accountNumber LIKE ? OR email LIKE ?)";

            $statement = $this->con->prepare($query);

            $statement->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);

            $statement->execute();

            return $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        }

        private function searchCases($searchTerm)
        {

            $query = "SELECT caseNumber, caseTitle FROM caliweb_cases WHERE (caseNumber LIKE ? OR caseTitle LIKE ?)";

            $statement = $this->con->prepare($query);

            $statement->bind_param('ss', $searchTerm, $searchTerm);

            $statement->execute();

            return $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        }

        private function searchTasks($searchTerm)
        {

            $query = "SELECT taskName, taskDescription FROM caliweb_tasks WHERE (taskName LIKE ? OR taskDescription LIKE ?)";

            $statement = $this->con->prepare($query);

            $statement->bind_param('ss', $searchTerm, $searchTerm);

            $statement->execute();

            return $statement->get_result()->fetch_all(MYSQLI_ASSOC);

        }
    }

    require ($_SERVER['DOCUMENT_ROOT'] . '/configuration/index.php');

    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');

    require ($_SERVER["DOCUMENT_ROOT"] . '/modules/CaliWebDesign/Utility/Backend/index.php');

    $caliemail = $_SESSION['caliid'];

    $currentAccount = new \CaliWebDesign\Accounts\AccountHandler($con);

    $success = $currentAccount->fetchByEmail($caliemail);

    $userrole = $currentAccount->role->name;

    if ($userrole == "Administrator") {

        $searchSystem = new SearchSystem($con);

        $term = isset($_GET['term']) ? $_GET['term'] : '';

        $results = $searchSystem->search($term);


        header('Content-Type: application/json');

        echo json_encode($results);

    } else {

        header('Content-Type: application/json');

        echo json_encode('This feature is unavailable for security reasons.');

    }

?>