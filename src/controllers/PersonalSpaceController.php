<?php

    namespace controllers;

    class PersonalSpaceController extends Controller{

        /**
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access to the member's book list
        public function getMemberBookList(){
            $memberBookList = $this->memberManager->getMemberBookList();
            $totalBooks = $this->memberManager->nbBooks();

            echo $this->twig->render('commonPages/bookListView.html.twig', array('memberBookList' => $memberBookList, 'totalBooks' => $totalBooks));
        }

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Access to the member's page to ask a friend to share their booklist
        public function friendsPage($login){
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('commonPages/friendsCircleView.html.twig', array('friends' => $friends));
        }

        /**
         * @param $login
         * @param $loginFriend
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Send an email to the friend to know if he/she wants to share his/her booklist
        public function reachFriend($login, $loginFriend){
            $message = $this->memberManager->reachFriend($login, $loginFriend);
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('commonPages/friendsCircleView.html.twig', array('message' => $message, 'friends' => $friends));
        }

        /**
         * @param $login
         * @param $loginFriend
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Confirm if you want to share or not your booklist with the person who asked you
        public function shareBookList($login, $loginFriend){
            echo $this->twig->render('commonPages/shareBookListView.html.twig', array('login' => $login, 'loginFriend' => $loginFriend));
        }

        /**
         * @param $login
         * @param $loginFriend
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Display the result of the choice to share or not the booklist
        public function shareBookListWithFriend($login, $loginFriend){
            $message = $this->managingSharedList->shareBookListWithFriend($login, $loginFriend);

            echo $this->twig->render('commonPages/resultAskShare.html.twig', array('message' => $message));
        }

        /**
         * @param $login
         * @param $loginFriend
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // If the member asked doesn't want to share his/her booklist
        public function notShare($login, $loginFriend){
            $message = $this->memberManager->notShare($login, $loginFriend);

            echo $this->twig->render('commonPages/resultAskShare.html.twig', array('message' => $message));
        }

        /**
         * @param $login
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Allows a member to search for a specific book within his/her friends booklists
        public function searchBookFriendPage($login){
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('commonPages/searchBookFriendView.html.twig', array('friends' => $friends));
        }

        /**
         * @param $login
         * @param $loginFriend
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Allows to delete someone we added previously
        public function deleteSharedBooklist($login, $loginFriend){
            $this->managingSharedList->deleteSharedBooklist($login, $loginFriend);
            $this->friendsPage($login);
        }

        /**
         * @param $login
         * @param $loginFriend
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Allows the member sharing his/her booklist to stop sharing it with someone in particular
        public function stopSharingBooklist($login, $loginFriend){
            $this->managingSharedList->stopSharingBooklist($login, $loginFriend);

            $account = new AccountController();
            $account->accountPage($login);
        }
    }