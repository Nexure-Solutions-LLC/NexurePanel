<?php

require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');
require($_SERVER["DOCUMENT_ROOT"].'/authentication/index.php');

header('Content-Type: application/json');

$term = isset($_GET['term']) ? $_GET['term'] : '';

$searchTerm = '%' . $term . '%';

// Initialize an array to hold the categorized results
$searchResults = [];

// Query for Users (Customers)
$userQuery = "SELECT legalName, email, accountNumber FROM caliweb_users WHERE (legalName LIKE ? OR accountNumber LIKE ? OR email LIKE ?)";
$userStatement = $con->prepare($userQuery);
$userStatement->bind_param('sss', $searchTerm, $searchTerm, $searchTerm);
$userStatement->execute();
$userResult = $userStatement->get_result();

$searchResults['customers'] = [];
while ($row = $userResult->fetch_assoc()) {
    $searchResults['customers'][] = $row;
}

// Query for Cases
$casesQuery = "SELECT caseNumber, caseTitle FROM caliweb_cases WHERE (caseNumber LIKE ? OR caseTitle LIKE ?)";
$casesStatement = $con->prepare($casesQuery);
$casesStatement->bind_param('ss', $searchTerm, $searchTerm);
$casesStatement->execute();
$casesResult = $casesStatement->get_result();

$searchResults['cases'] = [];
while ($row = $casesResult->fetch_assoc()) {
    $searchResults['cases'][] = $row;
}

// Query for Tasks
$tasksQuery = "SELECT taskName, taskDescription FROM caliweb_tasks WHERE (taskName LIKE ? OR taskDescription LIKE ?)";
$tasksStatement = $con->prepare($tasksQuery);
$tasksStatement->bind_param('ss', $searchTerm, $searchTerm);
$tasksStatement->execute();
$tasksResult = $tasksStatement->get_result();

$searchResults['tasks'] = [];
while ($row = $tasksResult->fetch_assoc()) {
    $searchResults['tasks'][] = $row;
}

// You can add more queries for other categories like projects, invoices, etc.

// Output the categorized results as JSON
echo json_encode($searchResults);

?>
