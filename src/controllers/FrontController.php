<?php

    namespace controllers;

    use models\View;

    class FrontController{

        public function welcome(){

            $view = new View('home');
            $view->render([]);
        }
    }