<?php

    namespace controllers;

    class PageController extends Controller{

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Ask to the visitor to confirm or to refuse the registration
        public function confirmRegistrationPage($login){
            echo $this->twig->render('confirmRegistration.html.twig', array('login' => $login));
        }

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // This page is displayed when the visitor hasn't already confirm his/her registration and try to log in
        public function awaitingRegistrationConfirmation($login){
            echo $this->twig->render('awaitingRegistrationConfirmation.html.twig', array('login' => $login));
        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access to the member's page to search a book
        public function searchBookPage(){
            echo $this->twig->render('commonPages/searchBookView.html.twig');
        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access to the page allowing to register a book
        public function registerBookPage(){
            echo $this->twig->render('commonPages/registerBookView.html.twig');
        }
    }