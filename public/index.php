<?php
    require('../config/dev.php');
    require('../vendor/autoload.php');
    // Start a session
    session_start();
    session_regenerate_id(true);

    // Call the autoloader here ?
    //$loader = require '../vendor/autoload.php';

    // Call and execute the Router
    $router = new HaB\config\Router();
    $router->requestRouter();