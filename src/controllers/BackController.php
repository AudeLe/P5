<?php
    namespace controllers;

    use models\View;


    class BackController extends Controller{

        // Registration on the website
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){
            $this->connectionManager->registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor);

            echo $this->twig->render('registration.html.twig');
        }

        // Connection on the website
        public function connection($loginConnection, $passwordVisitorConnection){
            $this->connectionManager->connection($loginConnection, $passwordVisitorConnection);
        }

        // Log out of the website
        public function logOut(){
            $this->connectionManager->logOut();

            echo $this->twig->render('logout.html.twig');
        }

        public function memberProfile($login){
            $this->connectionManager->memberProfile($login);
            $memberBookList = $this->memberManager->getMemberBookList($login);

            echo $this->twig->render('memberPages/memberProfileView.html.twig', array('memberBookList' => $memberBookList));
        }
    }