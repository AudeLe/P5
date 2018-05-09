<?php

    namespace controllers;

    use models\View;
    use DAO\BookDAO;

    class FrontController{

        public function __construct(){
            $this->bookManager = new BookDAO();
        }

        public function welcome(){

            $view = new View('home');
            $view->render([]);
        }

    }