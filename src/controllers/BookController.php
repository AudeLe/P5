<?php
    namespace controllers;

    class BookController extends Controller{

        // Record the datas of the selected book
        public function registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            $this->bookManager->registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages);

        }

        // Delete the book
        public function deleteBook($id){
            $this->bookManager->deleteBook($id);
        }

        public function editBookDatas($bookId){
            $bookDatas = $this->bookManager->editBookDatas($bookId);

            echo $this->twig->render('commonPages/editBookDatas.html.twig', array('bookDatas' => $bookDatas));
        }

        public function displayBook($bookId){
            $bookDatas = $this->bookManager->displayBook($bookId);

            echo $this->twig->render('commonPages/displayBooKview.html.twig', array('bookDatas' => $bookDatas));
        }

        public function registerEditBookDatas($bookId, $editTitle, $editAuthor, $editPublishingYear, $editSummary, $editISBN, $editNbPages){
            $this->bookManager->registerEditBookDatas($bookId, $editTitle, $editAuthor, $editPublishingYear, $editSummary, $editISBN, $editNbPages);
        }

        // Verifies if the book has already been registered
        public function searchBook($id, $ISBN){

            $message = $this->memberManager->searchBook($id, $ISBN);

            echo $this->twig->render('memberPages/searchBookView.html.twig', array('message' => $message));
        }

        public function checkBookFriend($login, $ISBN, $loginFriend){
            $message = $this->bookManager->checkBookFriend($ISBN, $loginFriend);
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('memberPages/searchBookFriendView.html.twig', array('message' => $message, 'friends' => $friends));
        }
    }