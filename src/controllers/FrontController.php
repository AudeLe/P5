<?php

    namespace controllers;

    class FrontController extends Controller{

        public function welcome(){

            echo $this->twig->render('home.html.twig');
        }

        public function contactForm(){
            echo $this->twig->render('contactForm.html.twig');
        }

        public function contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail){
            $message = $this->adminManager->contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail);

            echo $this->twig->render('statusContactMail.html.twig', array('message' => $message));
        }

    }