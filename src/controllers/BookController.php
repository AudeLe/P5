<?php
    namespace controllers;

    class BookController extends Controller{

        public function registerBookDatas($bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages){
            $this->bookManager->registerBookDatas($bookTitle, $bookAuthors, $bookPublishedDate, $bookDescription, $bookISBN, $bookNbPages);

            echo $this->twig->render('memberPages/memberProfileView.html.twig');
        }

        public function deleteBook($id){
            $this->bookManager->deleteBook($id);

            //echo $this->twig->render('memberPages/memberProfileView.html.twig');
        }
    }