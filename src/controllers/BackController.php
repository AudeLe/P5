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

        // Modification of the credentials
        public function verifyInformations($login, $password){
            $this->connectionManager->verifyInformations($login, $password);

            echo $this->twig->render('memberPages/editInformationsView.html.twig');
        }

        // Edit the login
        public function editLogin($login){
            $this->connectionManager->editLogin($login);
            $this->connectionManager->logOut();
        }

        // Edit the password
        public function editPassword($password, $confirmPassword){
            $this->connectionManager->editPassword($password, $confirmPassword);
            $this->connectionManager->logOut();
        }

        // Delete the account
        public function deleteAccount($id, $login, $password){
            $this->connectionManager->deleteAccount($id, $login, $password);
        }

        // Access the member's profile
        public function memberProfile($login){
            $memberBookList = $this->memberManager->getMemberBookList($login);

            echo $this->twig->render('memberPages/memberProfileView.html.twig', array('memberBookList' => $memberBookList));
        }

        public function reachFriend($login, $loginFriend){
            $message = $this->memberManager->reachFriend($login, $loginFriend);

            //echo $this->twig->render('memberPages/memberProfileView.html.twig', array('message' => $message), array('message' => $message));
        }
    }