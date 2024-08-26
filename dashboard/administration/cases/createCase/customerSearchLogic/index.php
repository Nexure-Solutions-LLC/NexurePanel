<?php

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
    require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');

    header('Content-Type: application/json');

    $term = isset($_GET['term']) ? $_GET['term'] : '';

    $query = "SELECT legalName, email, accountNumber FROM caliweb_users WHERE (legalName LIKE ? OR accountNumber LIKE ? OR email LIKE ?)";

    $statement = $con->prepare($query);

    $searchTerm = '%' . $term . '%';

    $statement->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);

    $statement->execute();

    $result = $statement->get_result();

    $filteredData = [];

    while ($row = $result->fetch_assoc()) {

        $filteredData[] = $row;

    }

    echo json_encode($filteredData);

?>