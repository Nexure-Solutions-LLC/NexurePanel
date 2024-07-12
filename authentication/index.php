<?php
    session_start();

    if(!isset($_SESSION["caliid"])) {

        header("Location: /login");
        exit();

    }
    
?>