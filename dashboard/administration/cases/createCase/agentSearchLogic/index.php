<?php
     
    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');

    header('Content-Type: application/json');

    $term = isset($_GET['term']) ? $_GET['term'] : '';

    $query = "SELECT legalName, email FROM nexure_users WHERE userrole = 'administrator' AND (legalName LIKE ? OR email LIKE ?)";

    $statement = $con->prepare($query);

    $searchTerm = '%' . $term . '%';

    $statement->bind_param('ss', $searchTerm, $searchTerm);

    $statement->execute();

    $result = $statement->get_result();

    $filteredData = [];

    while ($row = $result->fetch_assoc()) {

        $filteredData[] = $row;

    }

    echo json_encode($filteredData);

?>