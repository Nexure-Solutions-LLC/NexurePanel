<?php

    session_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_POST['theme'])) {

            $_SESSION['theme'] = $_POST['theme'];

            echo json_encode(['status' => 'success', 'theme' => $_POST['theme']]);

        } else {

            echo json_encode(['status' => 'error', 'message' => 'Theme not set']);

        }

    } else {

        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);

    }

?>