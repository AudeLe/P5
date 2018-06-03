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
        }

        /**
         * @param $password
         * @param $confirmPassword
         */
        // Edit the password
        public function editPassword($password, $confirmPassword){
            $this->accountManager->editPassword($password, $confirmPassword);
        }

        /**
         * @param $editMail
         * @param $confirmEditMail
         */
        // Edit the mail
        public function editMail($editMail, $confirmEditMail){
            $this->accountManager->editMail($editMail, $confirmEditMail);
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
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the page allowing to modify the account's informations
        public function accountPage(){
            $members = $this->managingSharedList->managingSharedLists();

            echo $this->twig->render('commonPages/accountView.html.twig', array('members' => $members));
        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the page allowing to delete the account
        public function deleteAccountPage(){
            echo $this->twig->render('memberPages/deletionAccountView.html.twig');
        }
    }