<?php
    namespace controllers;

    class BookController extends Controller{

        // Record the datas of the selected book
        public function registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            $this->bookManager->registerBookDatas($id, $bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages);

            echo $this->twig->render('memberPages/memberProfileView.html.twig');
        }

        // Delete the book
        public function deleteBook($id){
            $this->bookManager->deleteBook($id);
        }

        // Verifies if the book has already been registered
        public function searchBook($id, $ISBN){
            // FONCTIONNE MAIS REDIRECTION A REVOIR !!!
            $message = $this->memberManager->searchBook($id, $ISBN);

            echo $this->twig->render('memberPages/memberProfileView.html.twig', array('message' => $message));
        }
    }