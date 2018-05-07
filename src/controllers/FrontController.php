<?php

    namespace HaB\src\controllers;

    use HaB\src\models\View;

    class FrontController{

        public function welcome(){

            $view = new View('home');
            $view->render([]);
        }
    }