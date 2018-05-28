<?php

    namespace controllers;

    class PersonalSpaceController extends Controller{
        // Access to the member's book list
        public function getMemberBookList($login){
            $memberBookList = $this->memberManager->getMemberBookList($login);
            $totalBooks = $this->memberManager->nbBooks();

            echo $this->twig->render('commonPages/bookListView.html.twig', array('memberBookList' => $memberBookList, 'totalBooks' => $totalBooks));
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
    }