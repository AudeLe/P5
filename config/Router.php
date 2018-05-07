<?php

    namespace config;

    use controllers\FrontController;
    use Exception;

    class Router {

        private $frontController;

        public function __construct(){
            $this->frontController = new FrontController();
        }

        public function requestRouter(){

            try{
                if(isset($_GET['action'])){

                } else{
                    $this->frontController->welcome();
                }
            } catch(Exception $e){
                echo 'Erreur : ' . $e->getMessage();
            }
        }
    }
