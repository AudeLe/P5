<?php

    namespace controllers;

    class ConnectionController extends Controller{
        /**
         * @param $login
         * @param $passwordVisitor
         * @param $passwordVisitorCheck
         * @param $emailVisitor
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Registration on the website
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){
            $this->connectionManager->registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor);

            echo $this->twig->render('awaitingRegistrationConfirmation.html.twig', array('login' => $login));
        }

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Confirm the registration on the website
        public function confirmRegistration($login){
            $this->connectionManager->confirmRegistration($login);

            echo $this->twig->render('registration.html.twig', array('login' => $login));
        }

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Refuse the registration on the website
        public function refuseRegistration($login){
            $this->connectionManager->refuseRegistration($login);

            echo $this->twig->render('home.html.twig');
        }

        /**
         * @param $loginConnection
         * @param $passwordVisitorConnection
         */
        // Connection on the website
        public function connection($loginConnection, $passwordVisitorConnection){
            $this->connectionManager->connection($loginConnection, $passwordVisitorConnection);
        }

        /**
         *
         */
        // Log out of the website
        public function logOut(){
            $this->connectionManager->logOut();


        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        public function logOutPage(){
            echo $this->twig->render('logOut.html.twig');
        }
    }