<?php
    namespace controllers;


    class BackController extends Controller{

        // Registration on the website
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){
            $this->connectionManager->registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor);

            echo $this->twig->render('awaitingRegistrationConfirmation.html.twig', array('login' => $login));
        }

        public function confirmRegistrationPage($login){
            echo $this->twig->render('confirmRegistration.html.twig', array('login' => $login));
        }

        public function confirmRegistration($login){
            $this->connectionManager->confirmRegistration($login);

            echo $this->twig->render('registration.html.twig', array('login' => $login));
        }

        public function refuseRegistration($login){
            $this->connectionManager->refuseRegistration($login);

            echo $this->twig->render('home.html.twig');
        }

        public function awaitingRegistrationConfirmation($login){
            echo $this->twig->render('awaitingRegistrationConfirmation.html.twig', array('login' => $login));
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

        // Modification of the credentials
        public function verifyInformations($login, $password){
            $this->connectionManager->verifyInformations($login, $password);

            echo $this->twig->render('commonPages/editInformationsView.html.twig');
        }

        // Edit the login
        public function editLogin($login){
            $this->connectionManager->editLogin($login);
            $this->connectionManager->logOut();
        }

        // Edit the password
        public function editPassword($password, $confirmPassword){
            $this->connectionManager->editPassword($password, $confirmPassword);
            $this->connectionManager->logOut();
        }

        // Edit the mail
        public function editMail($editMail, $confirmEditMail){
            $this->connectionManager->editMail($editMail, $confirmEditMail);
            $this->connectionManager->logOut();
        }

        // Delete the account
        public function deleteAccount($id, $login, $password){
            $this->connectionManager->deleteAccount($id, $login, $password);
        }

        // Access the admin's profile
        public function adminProfile($login){
            echo $this->twig->render('adminPages/adminProfileView.html.twig');
        }

        // Access the member's profile
        public function memberProfile($login){
            //$memberBookList = $this->memberManager->getMemberBookList($login);

            echo $this->twig->render('memberPages/memberProfileView.html.twig');
        }

        // Access to the member's book list
        public function getMemberBookList($login){
            $memberBookList = $this->memberManager->getMemberBookList($login);
            $totalBooks = $this->memberManager->nbBooks();

            echo $this->twig->render('commonPages/bookListView.html.twig', array('memberBookList' => $memberBookList, 'totalBooks' => $totalBooks));
        }

        // Access to the member's page to search a book
        public function searchBookPage(){

            echo $this->twig->render('commonPages/searchBookView.html.twig');

        }

        public function registerBookPage($login){
            echo $this->twig->render('commonPages/registerBookView.html.twig');
        }

        // Access to the member's page to ask a friend to share their booklist
        public function friendsPage($login){
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('commonPages/friendsCircleView.html.twig', array('friends' => $friends));
        }

        public function reachFriend($login, $loginFriend){
            $message = $this->memberManager->reachFriend($login, $loginFriend);
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('commonPages/friendsCircleView.html.twig', array('message' => $message, 'friends' => $friends));
        }

        public function accountPage($login){
            $members = $this->memberManager->managingSharedLists();

            echo $this->twig->render('commonPages/accountView.html.twig', array('members' => $members));
        }

        public function deleteAccountPage($login){
            echo $this->twig->render('memberPages/deletionAccountView.html.twig');
        }

        public function shareBookList($login, $loginFriend){
            echo $this->twig->render('commonPages/shareBookListView.html.twig', array('login' => $login, 'loginFriend' => $loginFriend));
        }

        public function shareBookListWithFriend($login, $loginFriend){
            $message = $this->memberManager->shareBookListWithFriend($login, $loginFriend);

            echo $this->twig->render('commonPages/resultAskShare.html.twig', array('message' => $message));
        }

        public function notShare($login, $loginFriend){
            $message = $this->memberManager->notShare($login, $loginFriend);

            echo $this->twig->render('commonPages/resultAskShare.html.twig', array('message' => $message));
        }

        public function searchBookFriendPage($login){
            $friends = $this->memberManager->reminderFriends($login);
            echo $this->twig->render('commonPages/searchBookFriendView.html.twig', array('friends' => $friends));
        }

        public function deleteSharedBooklist($login, $loginFriend){
            $this->memberManager->deleteSharedBooklist($login, $loginFriend);
            $this->friendsPage($login);
            //echo $this->twig->render('memberPages/friendsCircleView.html.twig');
        }

        public function stopSharingBooklist($login, $loginFriend){
            $message = $this->memberManager->stopSharingBooklist($login, $loginFriend);
            $this->accountPage($login);
        }

        public function statisticsApp(){
            $nbMembers = $this->adminManager->countMembers();
            $nbBooks = $this->adminManager->countBooks();
            echo $this->twig->render('adminPages/statisticsView.html.twig', array('nbMembers' => $nbMembers, 'nbBooks' => $nbBooks));
        }

        public function contactForm(){
            echo $this->twig->render('contactForm.html.twig');
        }

        public function contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail){
            $message = $this->adminManager->contactAdmin($loginSeeker, $emailSeeker, $subjectMail, $bodyMail);

            echo $this->twig->render('statusContactMail.html.twig', array('message' => $message));
        }
    }