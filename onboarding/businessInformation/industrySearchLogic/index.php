<?php

header('Content-Type: application/json');

$naicsData = [

    ["code" => "1111", "name" => "Oilseed and Grain Farming"],
    ["code" => "1112", "name" => "Vegetable and Melon Farming"],
    ["code" => "1113", "name" => "Fruit and Tree Nut Farming"],

];

$term = isset($_GET['term']) ? $_GET['term'] : '';

$filteredData = array_filter($naicsData, function($industry) use ($term) {

    return stripos($industry['name'], $term) !== false;

});

echo json_encode(array_values($filteredData));

?>