<?php

    namespace controllers;

    class FrontController extends Controller{

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the home page. It is the default action.
        public function welcome(){
            echo $this->twig->render('home.html.twig');
        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the contact form page
        public function contactForm(){
            echo $this->twig->render('contactForm.html.twig');
        }

        /**
         * @param $loginSeeker
         * @param $emailSeeker
         * @param $subjectMail
         * @param $bodyMail
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Send the email written by the visitor/user
        public function contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail){
            $message = $this->adminManager->contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail);

            echo $this->twig->render('statusContactMail.html.twig', array('message' => $message));
        }

    }