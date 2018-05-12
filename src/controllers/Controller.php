<?php

    namespace controllers;

    use \Twig_Loader_Filesystem;
    use \Twig_Environment;

    use DAO\ConnectionDAO;

    class Controller{

        protected $twig;

        function __construct(){

            $this->connectionManager = new ConnectionDAO();

            //Twig configuration
            $loader = new Twig_Loader_Filesystem('../templates');
            $this->twig = new Twig_Environment($loader, array(
                'cache' => false
            ));


        }

    }