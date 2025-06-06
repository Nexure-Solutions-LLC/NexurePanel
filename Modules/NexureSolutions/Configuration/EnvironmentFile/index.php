<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    // Load environment variables from .env file

    $dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
    $dotenv->load();

?>