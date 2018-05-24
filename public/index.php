<?php
    require('../config/dev.php');

    // Start a session
    session_start();
    session_regenerate_id(true);

    // Call the autoloader
    $loader = require '../vendor/autoload.php';
    $loader->addPsr4('HaB\\', __DIR__);

    // Call and execute the Router
    $router = new config\Router();
    $router->requestRouter();