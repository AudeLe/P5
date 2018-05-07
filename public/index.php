<?php
    require('../config/dev.php');

    // Start a session
    session_start();
    session_regenerate_id(true);

    // Call the autoloader here ?

    // Call and execute the Router
    $router = new HaB\config\Router();
    $router->requestRouter();