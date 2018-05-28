<?php

    namespace controllers;

    class ConnectionController extends Controller{
        // Registration on the website
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){
            $this->connectionManager->registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor);

            echo $this->twig->render('awaitingRegistrationConfirmation.html.twig', array('login' => $login));
        }



        public function confirmRegistration($login){
            $this->connectionManager->confirmRegistration($login);

            echo $this->twig->render('registration.html.twig', array('login' => $login));
        }

        public function refuseRegistration($login){
            $this->connectionManager->refuseRegistration($login);

            echo $this->twig->render('home.html.twig');
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
    }