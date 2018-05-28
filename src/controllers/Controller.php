<?php

    namespace controllers;

    use DAO\AccountDAO;
    use DAO\AdministratorDAO;
    use DAO\BookDAO;
    use DAO\ConnectionDAO;
    use DAO\ManagingSharedListDAO;
    use DAO\MemberDAO;

    use controllers\AccountController;

    // TWIG
    use \Twig_Loader_Filesystem;
    use \Twig_Environment;
    use \Twig_Extension_Debug;

    class Controller{

        protected $twig;

        protected $accountManager;
        protected $adminManager;
        protected $bookManager;
        protected $connectionManager;
        protected $managingSharedList;
        protected $memberManager;

        protected $accountController;

        /**
         * Controller constructor.
         */
        public function __construct(){

            $this->accountManager = new AccountDAO();
            $this->adminManager = new AdministratorDAO();
            $this->bookManager = new BookDAO();
            $this->connectionManager = new ConnectionDAO();
            $this->managingSharedList = new ManagingSharedListDAO();
            $this->memberManager = new MemberDAO();

            $this->accountController = new AccountController();

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

        /**
         * @param $errorMessage
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Display the error on the specified page
        public function errorManagement($errorMessage){

            echo $this->twig->render('errorView.html.twig', array('errorMessage' => $errorMessage));
        }
    }