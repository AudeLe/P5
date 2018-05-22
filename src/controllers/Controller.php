<?php

    namespace controllers;

    use \Twig_Loader_Filesystem;
    use \Twig_Environment;
    use \Twig_Extension_Debug;

    use DAO\ConnectionDAO;
    use DAO\BookDAO;
    use DAO\MemberDAO;

    class Controller{

        protected $twig;

        public function __construct(){

            $this->bookManager = new BookDAO();
            $this->connectionManager = new ConnectionDAO();
            $this->memberManager = new MemberDAO();

            //Twig configuration
            $loader = new Twig_Loader_Filesystem('../templates');
            $this->twig = new Twig_Environment($loader, array(
                'cache' => false,
                'debug' => true
            ));

            // Accessing the $_SESSION datas through Twig
            $this->twig->addGlobal('session', $_SESSION);

            // Adding te debug extension
            $this->twig->addExtension(new Twig_Extension_Debug());

        }
    }