<?php

    namespace controllers;

    use \Twig_Loader_Filesystem;
    use \Twig_Environment;

    use DAO\ConnectionDAO;
    use DAO\BookDAO;
    use DAO\MemberDAO;

    class Controller{

        protected $twig;

        function __construct(){

            $this->bookManager = new BookDAO();
            $this->connectionManager = new ConnectionDAO();
            $this->memberManager = new MemberDAO();

            //Twig configuration
            $loader = new Twig_Loader_Filesystem('../templates');
            $this->twig = new Twig_Environment($loader, array(
                'cache' => false
            ));

            // Accessing the $_SESSION datas through Twig
            $this->twig->addGlobal('session', $_SESSION);

        }

    }