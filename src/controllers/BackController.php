<?php
    namespace controllers;


    class BackController extends Controller{

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the admin's profile
        public function adminProfile(){
            echo $this->twig->render('adminPages/adminProfileView.html.twig');
        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access the member's profile
        public function memberProfile(){
           echo $this->twig->render('memberPages/memberProfileView.html.twig');
        }

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access to the page displaying the website statistics
        public function statisticsApp(){
            $nbMembers = $this->adminManager->countMembers();
            $nbBooks = $this->adminManager->countBooks();
            echo $this->twig->render('adminPages/statisticsView.html.twig', array('nbMembers' => $nbMembers, 'nbBooks' => $nbBooks));
        }


    }