<?php

    namespace controllers;

    class AccountController extends Controller{
        /**
         * @param $login
         * @param $password
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Verify the informations in order to modify of the credentials
        public function verifyInformations($login, $password){
            $this->accountManager->verifyInformations($login, $password);

            echo $this->twig->render('commonPages/editInformationsView.html.twig');
        }

        /**
         * @param $login
         */
        // Edit the login
        public function editLogin($login){
            $this->accountManager->editLogin($login);
            $this->connectionManager->logOut();
        }

        /**
         * @param $password
         * @param $confirmPassword
         */
        // Edit the password
        public function editPassword($password, $confirmPassword){
            $this->accountManager->editPassword($password, $confirmPassword);
            $this->connectionManager->logOut();
        }

        /**
         * @param $editMail
         * @param $confirmEditMail
         */
        // Edit the mail
        public function editMail($editMail, $confirmEditMail){
            $this->accountManager->editMail($editMail, $confirmEditMail);
            $this->connectionManager->logOut();
        }

        /**
         * @param $id
         * @param $login
         * @param $password
         */
        // Delete the account
        public function deleteAccount($id, $login, $password){
            $this->accountManager->deleteAccount($id, $login, $password);
        }

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the page allowing to modify the account's informations
        public function accountPage($login){
            $members = $this->managingSharedList->managingSharedLists();

            echo $this->twig->render('commonPages/accountView.html.twig', array('members' => $members));
        }

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the page allowing to delete the account
        public function deleteAccountPage($login){
            echo $this->twig->render('memberPages/deletionAccountView.html.twig');
        }
    }