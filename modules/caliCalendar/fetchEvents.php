<?php

    require($_SERVER["DOCUMENT_ROOT"].'/configuration/index.php');

    $where_sql = '';

    if(!empty($_GET['start']) && !empty($_GET['end'])){

        $where_sql .= " WHERE start BETWEEN '".$_GET['start']."' AND '".$_GET['end']."' ";

    }

    $sql = "SELECT * FROM events $where_sql";
    $result = $con->query($sql);

    $eventsArr = array();
    
    if($result->num_rows > 0){

        while($row = $result->fetch_assoc()){
            array_push($eventsArr, $row);
        }

    }

    echo json_encode($eventsArr);

?>