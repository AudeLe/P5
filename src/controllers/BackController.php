<?php
    namespace controllers;

    use models\View;
    use DAO\ConnectionDAO;

    class BackController extends Controller{

        /*public function __construct(){
            $this->connectionManager = new ConnectionDAO();
        }*/

        // Registration on the website
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor, $birthDateVisitor){
            $this->connectionManager->registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor, $birthDateVisitor);

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