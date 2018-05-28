<?php

    namespace controllers;

    class PageController extends Controller{
        public function confirmRegistrationPage($login){
            echo $this->twig->render('confirmRegistration.html.twig', array('login' => $login));
        }

        public function awaitingRegistrationConfirmation($login){
            echo $this->twig->render('awaitingRegistrationConfirmation.html.twig', array('login' => $login));
        }

        // Access to the member's page to search a book
        public function searchBookPage(){

            echo $this->twig->render('commonPages/searchBookView.html.twig');

        }

        public function registerBookPage($login){
            echo $this->twig->render('commonPages/registerBookView.html.twig');
        }
    }