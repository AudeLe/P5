<?php

    namespace controllers;

    use models\View;


    class FrontController extends Controller{

        public function welcome(){

            echo $this->twig->render('home.html.twig');
        }

    }