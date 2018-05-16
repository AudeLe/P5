<?php

    namespace controllers;

    use models\View;
    use DAO\BookDAO;

    class FrontController extends Controller{

        public function __construct(){
            $this->bookManager = new BookDAO();
        }

        public function welcome(){

            echo $this->twig->render('home.html.twig');
        }

    }