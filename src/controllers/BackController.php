<?php
    namespace controllers;

    use models\View;


    class BackController extends Controller{

        // Registration on the website
        public function registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor){
            $this->connectionManager->registration($login, $passwordVisitor, $passwordVisitorCheck, $emailVisitor);

            echo $this->twig->render('registration.html.twig');
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

            echo $this->twig->render('memberPages/editInformationsView.html.twig');
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

        // Delete the account
        public function deleteAccount($id, $login, $password){
            $this->connectionManager->deleteAccount($id, $login, $password);
        }

        // Access the member's profile
        public function memberProfile($login){
            //$memberBookList = $this->memberManager->getMemberBookList($login);

            echo $this->twig->render('memberPages/memberProfileView.html.twig');
        }

        // Access to the member's book list
        public function getMemberBookList($login){
            $memberBookList = $this->memberManager->getMemberBookList($login);

            echo $this->twig->render('memberPages/bookListView.html.twig', array('memberBookList' => $memberBookList));
        }

        // Access to the member's page to search a book
        public function searchBookPage(){

            echo $this->twig->render('memberPages/searchBookView.html.twig');

        }

        // Access to the member's page to ask a friend to share their booklist
        public function friendsPage($login){
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('memberPages/friendsCircleView.html.twig', array('friends' => $friends));
        }

        public function reachFriend($login, $loginFriend){
            $message = $this->memberManager->reachFriend($login, $loginFriend);
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('memberPages/friendsCircleView.html.twig', array('message' => $message, 'friends' => $friends));
        }

        public function accountPage(){
            echo $this->twig->render('memberPages/accountView.html.twig');
        }

        public function deleteAccountPage(){
            echo $this->twig->render('memberPages/deletionAccountView.html.twig');
        }

        public function shareBookList($login, $loginFriend){
            echo $this->twig->render('memberPages/shareBookListView.html.twig', array('login' => $login, 'loginFriend' => $loginFriend));
        }

        public function shareBookListWithFriend($login, $loginFriend){
            $message = $this->memberManager->shareBookListWithFriend($login, $loginFriend);

            echo $this->twig->render('memberPages/resultAskShare.html.twig', array('message' => $message));
        }

        public function notShare($login, $loginFriend){
            $message = $this->memberManager->notShare($login, $loginFriend);

            echo $this->twig->render('memberPages/resultAskShare.html.twig', array('message' => $message));
        }

        public function searchBookFriendPage($login){
            $friends = $this->memberManager->reminderFriends($login);
            echo $this->twig->render('memberPages/searchBookFriendView.html.twig', array('friends' => $friends));
        }
    }