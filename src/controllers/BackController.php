<?php
    namespace controllers;

    use models\View;
    use DAO\ConnectionDAO;

    class BackController{

        public function __construct(){
            $this->connectionManager = new ConnectionDAO();
        }

        // Registration on the website
        public function registration($login, $passwordVisitor, $passwordVisitorCheck){
            $this->connectionManager->registration($login, $passwordVisitor, $passwordVisitorCheck);

            $view = new View('registration');
            $view->render(['registration']);
        }

        // Connection on the website
        public function connection($loginConnection, $passwordVisitorConnection){
            $this->connectionManager->connection($loginConnection, $passwordVisitorConnection);
        }

        // Log out of the website
        public function logOut(){
            $this->connectionManager->logOut();

            $view= new View('logOut');
            $view->render([]);
        }
    }