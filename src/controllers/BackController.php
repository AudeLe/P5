<?php
    namespace controllers;


    class BackController extends Controller{

        // Access the admin's profile
        public function adminProfile($login){
            echo $this->twig->render('adminPages/adminProfileView.html.twig');
        }

        // Access the member's profile
        public function memberProfile($login){
            //$memberBookList = $this->memberManager->getMemberBookList($login);

            echo $this->twig->render('memberPages/memberProfileView.html.twig');
        }



        public function statisticsApp(){
            $nbMembers = $this->adminManager->countMembers();
            $nbBooks = $this->adminManager->countBooks();
            echo $this->twig->render('adminPages/statisticsView.html.twig', array('nbMembers' => $nbMembers, 'nbBooks' => $nbBooks));
        }


    }