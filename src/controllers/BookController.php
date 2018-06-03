<?php
    namespace controllers;

    class BookController extends Controller{

        /**
         * @param $id
         * @param $bookTitle
         * @param $bookAuthors
         * @param $bookPublishedDate
         * @param $bookDescription
         * @param $bookISBN
         * @param $bookNbPages
         */
        // Record the datas of the selected book
        public function registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            $this->bookManager->registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages);

        }

        /**
         * @param $id
         */
        // Delete the book
        public function deleteBook($id){
            $this->bookManager->deleteBook($id);
        }

        /**
         * @param $bookId
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Display the book informations in order to edit them
        public function editBookDatas($bookId){
            $bookDatas = $this->bookManager->editBookDatas($bookId);

            echo $this->twig->render('commonPages/editBookDatas.html.twig', array('bookDatas' => $bookDatas));
        }

        /**
         * @param $bookId
         * @param $editTitle
         * @param $editAuthor
         * @param $editPublishingYear
         * @param $editSummary
         * @param $editISBN
         * @param $editNbPages
         */
        // Record the edited book informations
        public function registerEditBookDatas($bookId, $editTitle, $editAuthor, $editPublishingYear, $editSummary, $editISBN, $editNbPages){
            $this->bookManager->registerEditBookDatas($bookId, $editTitle, $editAuthor, $editPublishingYear, $editSummary, $editISBN, $editNbPages);
        }

        /**
         * @param $bookId
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Display the book informations
        public function displayBook($bookId){
            $bookDatas = $this->bookManager->displayBook($bookId);

            echo $this->twig->render('commonPages/displayBookView.html.twig', array('bookDatas' => $bookDatas));
        }


        /**
         * @param $id
         * @param $ISBN
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Verifies if the book has already been registered by the user
        public function searchBook($id, $ISBN){
            $message = $this->memberManager->searchBook($id, $ISBN);

            echo $this->twig->render('commonPages/searchBookView.html.twig', array('message' => $message));
        }

        /**
         * @param $login
         * @param $ISBN
         * @param $loginFriend
         * @throws \Twig_Error_Loader
         * @throws \Twig_Error_Runtime
         * @throws \Twig_Error_Syntax
         */
        // Check if the book has already been registered by the friend selected
        public function checkBookFriend($login, $ISBN, $loginFriend){
            $message = $this->bookManager->checkBookFriend($ISBN, $loginFriend);
            $friends = $this->memberManager->reminderFriends($login);

            echo $this->twig->render('commonPages/searchBookFriendView.html.twig', array('message' => $message, 'friends' => $friends));
        }
    }