<?php

    namespace controllers;

    class AccountController extends Controller{
        // Modification of the credentials
        public function verifyInformations($login, $password){
            $this->accountManager->verifyInformations($login, $password);

            echo $this->twig->render('commonPages/editInformationsView.html.twig');
        }

        // Edit the login
        public function editLogin($login){
            $this->accountManager->editLogin($login);
            $this->connectionManager->logOut();
        }

        // Edit the password
        public function editPassword($password, $confirmPassword){
            $this->accountManager->editPassword($password, $confirmPassword);
            $this->connectionManager->logOut();
        }

        // Edit the mail
        public function editMail($editMail, $confirmEditMail){
            $this->accountManager->editMail($editMail, $confirmEditMail);
            $this->connectionManager->logOut();
        }

        // Delete the account
        public function deleteAccount($id, $login, $password){
            $this->accountManager->deleteAccount($id, $login, $password);
        }

        public function accountPage($login){
            $members = $this->memberManager->managingSharedLists();

            echo $this->twig->render('commonPages/accountView.html.twig', array('members' => $members));
        }

        public function deleteAccountPage($login){
            echo $this->twig->render('memberPages/deletionAccountView.html.twig');
        }
    }